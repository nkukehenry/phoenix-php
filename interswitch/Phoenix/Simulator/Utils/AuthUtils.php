<?php

namespace Interswitch\Phoenix\Simulator\Utils;

use DateTime;
use DateTimeZone;

class AuthUtils
{
    private static $LOG;

    public static function generateInterswitchAuth($httpMethod, $resourceUrl, $additionalParameters)
    {
       
        $privateKey = CryptoUtils::getRSAPrivate(Constants::PRIKEY);
        $authToken  = Constants::$authToken;
        $sessionKey = Constants::$sessionKey;

        $interswitchAuth = [];

        $ugTimeZone = new DateTimeZone("Africa/Kampala");
        $calendar = new DateTime("now", $ugTimeZone);
        $timestamp = $calendar->getTimestamp();

        $uuid = uniqid();
        $nonce = str_replace("-", "", $uuid);

        $clientIdBase64 = base64_encode(Constants::CLIENT_ID);
        $authorization = Constants::AUTHORIZATION_REALM . " " . $clientIdBase64;

        $encodedResourceUrl = urlencode($resourceUrl);
        $signatureCipher = $httpMethod . "&" . $encodedResourceUrl . "&" . $timestamp . "&" . $nonce . "&"
            . Constants::CLIENT_ID . "&" . Constants::CLIENT_SECRET;

        if (!empty($additionalParameters)) {
            $signatureCipher .= "&" . $additionalParameters;
        }

        echo "\n------------------------------------------------------------------------------------------------\n";

        echo "signature cipher ", $signatureCipher;

        $interswitchAuth[Constants::AUTHORIZATION] = trim($authorization);
        $interswitchAuth[Constants::TIMESTAMP] = (string)$timestamp;
        $interswitchAuth[Constants::NONCE] = $nonce;

        if (empty($privateKey)) {
            $interswitchAuth[Constants::SIGNATURE] = CryptoUtils::signWithPrivateKey($signatureCipher, '');
        } else {
            $interswitchAuth[Constants::SIGNATURE] = CryptoUtils::signWithPrivateKey($signatureCipher, $privateKey);
        }


        $authToken = !empty($sessionKey) ? CryptoUtils::encrypt($authToken, $sessionKey) : "";
        $interswitchAuth[Constants::AUTH_TOKEN] = $authToken;

        return $interswitchAuth;
    }
}