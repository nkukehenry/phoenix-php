<?php

namespace Interswitch\Phoenix\Simulator;

use Interswitch\Phoenix\Simulator\Utils\AuthUtils;
use Interswitch\Phoenix\Simulator\Utils\Constants;
use Interswitch\Phoenix\Simulator\Utils\CryptoUtils;
use Interswitch\Phoenix\Simulator\Utils\EllipticCurveUtils;
use Interswitch\Phoenix\Simulator\Utils\HttpUtil;
use Interswitch\Phoenix\Simulator\Utils\JSONDataTransform;
use Interswitch\Phoenix\Simulator\Utils\PhoenixResponseCodes;
use Interswitch\Phoenix\Simulator\Utils\SystemApiException;
use Interswitch\Phoenix\Simulator\Utils\SystemResponse;
use Interswitch\Phoenix\Simulator\Utils\UtilMethods;
use Interswitch\Phoenix\Simulator\Dto\KeyExchangeRequest;

// require_once 'vendor/autoload.php'; // Adjust the path based on your project structure

class KeyExchange
{
    public static $endpointUrl = Constants::ROOT_LINK . "client/doKeyExchange";


    /**
     * This method should be run at intervals, and the generated authtoken saved for reuse till the set expiration period.
     *
     * @return SystemResponse<KeyExchangeResponse>
     * @throws Exception
     */
    public static function doKeyExchange(): SystemResponse
    {
        $curveUtils = new EllipticCurveUtils("ECDH");
        [$curvePrivateKey, $curvePublicKey] = $curveUtils->generateKeypair();
       
        $request    = (Object) array();
        $requestRef = self::generateUUID();

        $request->terminalId       = Constants::MY_TERMINAL_ID;
        $request->serialId         = Constants::MY_SERIAL_ID;
        $request->requestReference = self::generateUUID();
        $request->appVersion       = Constants::APP_VERSION;

        $passwordHash       = hash('sha512',Constants::ACCOUNT_PWD).$requestRef.Constants::MY_SERIAL_ID;
        $request->spassword = CryptoUtils::signWithPrivateKey($passwordHash);
        $request->clientSessionPublicKey = $curvePublicKey;

        $headers = AuthUtils::generateInterswitchAuth(Constants::POST_REQUEST, self::$endpointUrl, "", "", "");
        $json    = json_encode($request);

        try {

            $response = HttpUtil::postHTTPRequest(self::$endpointUrl, $headers, $json);
            
            $keyExchangeResponse =  json_decode($response);

            if ($keyExchangeResponse->responseCode === PhoenixResponseCodes::APPROVED) {

                $serverSessionKey       = $keyExchangeResponse->response->serverSessionPublicKey;
                $clearServerSessionKey  = CryptoUtils::decryptWithPrivate($serverSessionKey);
                $sessionKey             = $curveUtils->doECDH($curvePrivateKey, $clearServerSessionKey);
                $keyExchangeResponse->response->terminalKey = $terminalKey;
                Constants::setSessionKey($terminalKey);

                if (!empty($keyExchangeResponse->response->authToken)){

                    $authToken = CryptoUtils::decryptWithPrivate($keyExchangeResponse->response->authToken);
                    Constants::setAuthToken($authToken);
                }

                return $keyExchangeResponse;

            } else {

                throw new SystemApiException($keyExchangeResponse->responseCode, $keyExchangeResponse->responseMessage;
            }
        } catch (JsonException $e) {
            // Handle JSON serialization/deserialization exception
            echo "JSON error: " . $e->getMessage();
        } catch (SystemApiException $e) {
            // Handle SystemApiException
            echo "System API error: " . $e->getMessage();
        }
    }

    static function generateUUID() {
        if (function_exists('uuid_create')) {
            $uuid = uuid_create(UUID_TYPE_RANDOM);
    
            // Convert binary UUID to string representation
            $uuidString = bin2hex(uuid_parse($uuid));
    
            // Format UUID as per RFC 4122 (e.g., xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx)
            $formattedUUID = sprintf(
                '%s-%s-%s-%s-%s',
                substr($uuidString, 0, 8),
                substr($uuidString, 8, 4),
                substr($uuidString, 12, 4),
                substr($uuidString, 16, 4),
                substr($uuidString, 20)
            );
    
            return $formattedUUID;
        } else {
            // Fallback if uuid_create function is not available
            return uniqid();
        }
    }
}

// Example usage
//KeyExchange::main([]);
