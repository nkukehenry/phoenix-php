<?php

namespace Interswitch\Phoenix\Simulator;

use Interswitch\Phoenix\Simulator\Dto\ClientRegistrationDetail;
use Interswitch\Phoenix\Simulator\Dto\ClientRegistrationResponse;
use Interswitch\Phoenix\Simulator\Dto\CompleteClientRegistration;
use Interswitch\Phoenix\Simulator\Dto\JsonDataTransform;
use Interswitch\Phoenix\Simulator\Dto\LoginResponse;
use Interswitch\Phoenix\Simulator\Dto\PhoenixResponseCodes;
use Interswitch\Phoenix\Simulator\Dto\SystemResponse;
use Interswitch\Phoenix\Simulator\Utils\AuthUtils;
use Interswitch\Phoenix\Simulator\Utils\Constants;
use Interswitch\Phoenix\Simulator\Utils\CryptoUtils;
use Interswitch\Phoenix\Simulator\Utils\EllipticCurveUtils;
use Interswitch\Phoenix\Simulator\Utils\HttpUtil;
use Interswitch\Phoenix\Simulator\Utils\UtilMethods;


class ClientRegistration
{
    private static $LOG;

    public const BASE_URL = Constants::ROOT_LINK . "client/";
    public static $registrationEndpointUrl = self::BASE_URL . "clientRegistration";
    public static $registrationCompletionEndpointUrl = self::BASE_URL . "completeClientRegistration";

    public static function main()
    {


        [$privKey,$pubKey] = [Constants::PRIKEY,Constants::PUBKEY];
       
        $privateKey = CryptoUtils::getRSAPrivate($privKey);
        $publicKey  = CryptoUtils::getPublicKey($pubKey);

       echo "<br><br>***PUB KEY****</br>", PHP_EOL;
        print_r($publicKey->toString('PKCS8'));
         echo "<br><br>***PRIVATE Key****</br>", PHP_EOL;
        print_r($privateKey->toString('PKCS8'));


        $curveUtils = new EllipticCurveUtils("ECDH");
        [$curvePrivateKey, $curvePublicKey ] = $curveUtils->generateKeypair();
       
        $response = self::clientRegistrationRequest($publicKey, $curvePublicKey);

        $registrationResponse = UtilMethods::unMarshallSystemResponseObject($response, ClientRegistrationResponse::class);

        if ($registrationResponse->responseCode !== PhoenixResponseCodes::APPROVED['CODE']) {
            echo "Client Registration failed: ", $registrationResponse->responseMessage, PHP_EOL;
        } 
        else 
        {

            $decryptedServerSessionKey = CryptoUtils::decryptWithPrivate($registrationResponse->response->serverSessionPublicKey);

            UtilMethods::log($decryptedServerSessionKey,"Decrypted Server Session Key");

            echo "<br><br>***Decrypted Server Session Key****", PHP_EOL;
            echo "<br>", $decryptedServerSessionKey, PHP_EOL;

            echo "<br><br>***Curve Public key****", PHP_EOL;
            UtilMethods::log($curvePublicKey,"Curve Public key");
            echo "<br>", $curvePublicKey, PHP_EOL;

            $sessionKey = $curveUtils->doECDH($curvePrivateKey, $decryptedServerSessionKey);

            Constants::setSessionKey($sessionKey);
            
            echo "<br>==============sessionKey==============<br>", PHP_EOL;
            echo  $sessionKey, PHP_EOL;

            $authToken = CryptoUtils::decryptWithPrivate($registrationResponse->response->authToken);

            Constants::setAuthToken($authToken);

            echo "<br>authToken ", $authToken, PHP_EOL;
            $transactionReference = $registrationResponse->response->transactionReference;
         
            $finalResponse = self::completeRegistration($transactionReference);

            $response = json_decode($finalResponse);
            
            UtilMethods::log($response,"CompleteClient Registration Response");

            if ($response->responseCode === PhoenixResponseCodes::APPROVED['CODE']) {

                if ($response->response->clientSecret !== null && strlen($response->response->clientSecret) > 5) {

                    $clientSecret = CryptoUtils::decryptWithPrivate($response->response->clientSecret);
                    echo "<br><br>clientSecret: ", $clientSecret, PHP_EOL;
                }
            } else {
                echo "<br><br>finalResponse: ", $response->responseMessage, PHP_EOL;
            }
        }

        return $response;
    }

    private static function clientRegistrationRequest($publicKey, $clientSessionPublicKey)
    {
        $setup = new ClientRegistrationDetail();
        $setup->setSerialId(Constants::MY_SERIAL_ID);
        $setup->setName("API Client");
        $setup->setNin("123456");
        $setup->setOwnerPhoneNumber(Constants::PHONE_NUMBER);
        $setup->setPhoneNumber(Constants::PHONE_NUMBER);
        $setup->setPublicKey($publicKey);
        $setup->setRequestReference(self::generateUUID());
        $setup->setTerminalId(Constants::TERMINAL_ID);
        $setup->setGprsCoordinate("");
        $setup->setClientSessionPublicKey($clientSessionPublicKey);

        $headers = AuthUtils::generateInterswitchAuth(Constants::POST_REQUEST, self::$registrationEndpointUrl, "");
        
        $json = $setup;

        return HttpUtil::postHTTPRequest(self::$registrationEndpointUrl, $headers, $json);
    }

    private static function completeRegistration($transactionReference)
    {
        $completeReg = (Object) [];
        $completeReg->terminalId = Constants::TERMINAL_ID;
        $completeReg->serialId   = Constants::MY_SERIAL_ID;
        $completeReg->otp        = "";
        $completeReg->requestReference = self::generateUUID();
        $completeReg->password = CryptoUtils::encryptPassword(Constants::ACCOUNT_PWD);
        $completeReg->transactionReference = $transactionReference;
        $completeReg->appVersion = Constants::APP_VERSION;
        $completeReg->gprsCoordinate ="";

        $headers = AuthUtils::generateInterswitchAuth(Constants::POST_REQUEST, self::$registrationCompletionEndpointUrl, "");

        return HttpUtil::postHTTPRequest(self::$registrationCompletionEndpointUrl, $headers, $completeReg);
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