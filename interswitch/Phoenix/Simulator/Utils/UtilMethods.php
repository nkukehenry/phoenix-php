<?php

namespace Interswitch\Phoenix\Simulator\Utils;

class UtilMethods
{
    private static $LOG;

    public static function hash512($plainText)
    {
        try {
            $hash = hash('sha512', $plainText);
            return base64_encode(hex2bin($hash));
        } catch (Exception $e) {
            self::$LOG->error("Exception trace {} ", ExceptionUtils::getStackTrace($e));
            throw new SystemApiException(PhoenixResponseCodes::INTERNAL_ERROR, "Failure to hash512 object");
        }
    }

    public static function unMarshallSystemResponseObject($response, $theClass)
    {
        try {
            return json_decode($response);
        } catch (Exception $e) {
            self::$LOG->error("Exception trace {} ", ExceptionUtils::getStackTrace($e));
            throw new SystemApiException(PhoenixResponseCodes::INTERNAL_ERROR, "Failure to unmarshall json string from systemresponse object");
        }
    }

    public static function randomBytesHexEncoded($count)
    {
        try {
            $bytes = random_bytes($count);
            return bin2hex($bytes);
        } catch (Exception $e) {
            self::$LOG->error("Exception trace {} ", ExceptionUtils::getStackTrace($e));
            return null;
        }
    }

    public static function isEmptyString($str = null, $defaultReturn = null)
    {
        return self::isEmptyString($str) ? $defaultReturn : $str;
    }

    public static function log($log,$key=''){

        $data = (is_array($log) || is_object($log))?json_encode($log):$log;
        file_put_contents("../../../../logs.txt", date('Y-m-d H:i:s')." ".$key."\n\n".$data, FILE_APPEND);
    }
}