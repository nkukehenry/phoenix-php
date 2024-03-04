<?php

namespace Interswitch\Phoenix\Simulator\Utils;

use Exception;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\AES;
use Interswitch\Phoenix\Simulator\Dto\PhoenixResponseCodes;


class CryptoUtils
{
    private static $LOG;

    public static function encrypt($plaintext)
    {
        try {

            $sessionKey     = Constants::$sessionKey;

            $message    = utf8_encode($plaintext);
            $iv         = openssl_random_pseudo_bytes(16);
            $cipherText = openssl_encrypt($message, 'aes-256-cbc',base64_decode($sessionKey), 0, $iv);

            $encryptedValue = $iv . $cipherText;
            
            return base64_encode($encryptedValue);

        } 
        catch (Exception $e) {

            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to encrypt object");
        }
    }

    public static function encryptPassword($password) {

        $sessionKey     = Constants::$sessionKey;

        echo "<br><br>Key To encrypt:: ";
        echo $sessionKey."<br>";
       
        $base64PasswordHash   = base64_encode(hash('sha512', $password));
        $iv                   = openssl_random_pseudo_bytes(16);
      
        $encrypted = openssl_encrypt($base64PasswordHash,'aes-128-cbc',base64_decode($sessionKey),OPENSSL_RAW_DATA ,$iv);
        
        $combined  = $iv . $encrypted;
        $encrypted =  base64_encode($combined);

        return $encrypted;
    }

    public static function decrypt($encryptedValue)
    {
        try {

            $sessionKey     = Constants::$sessionKey;
            $encryptedValue = base64_decode($encryptedValue);

            $iv         = substr($encryptedValue, 0, 16);
            $cipherText = substr($encryptedValue, 16);
            
            echo $cipherText;

            $decryptedValue = openssl_decrypt($cipherText, 'aes-256-cbc', base64_decode($sessionKey), 0, $iv);

            return $decryptedValue;
        } 
        catch (Exception $e) {

            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to decrypt object");
        }
    }

   public static function decryptWithPrivate($encryptedString)
    {
        try {

           
            $decryptedData = '';
            $message = base64_decode($encryptedString);

            $privateKey = self::getRSAPrivate(Constants::PRIKEY);
        
            $private = RSA::load($privateKey);
            $decryptedData = $private->decrypt($message);
            return $decryptedData;

        } 
        catch (\phpseclib\Crypt\Exception $e) {
            // Handle phpseclib decryption errors
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to decryptWithPrivate: " . $e->getMessage());
        } 
        catch (Exception $e) {
            // Handle other exceptions
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to decryptWithPrivate: " . $e->getMessage());
        }
    }


    public static function encryptWithPrivate($plaintext)
    {
        try {

            $privateKey = self::getRSAPrivate(Constants::PRIKEY);

            $message = utf8_encode($plaintext);
            openssl_private_encrypt($message, $encrypted, $privateKey);
            return base64_encode($encrypted);
        } catch (Exception $e) {
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to encryptWithPrivate ");
        }
    }


    public static function getRSAPrivate($privateKey = Constants::PRIKEY)
    {
        try {
            $keyResource = RSA::loadPrivateKey($privateKey);
            
            if ($keyResource === false) {
                throw new Exception("Failed to get private key");
            }
            return $keyResource;
        } catch (Exception $e) {

            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to getRSAPrivate ");
        }
    }

    public static function signWithPrivateKey($data)
    {
        try {
            if ($data === "") {
                return "";
            }

            $privateKey = self::getRSAPrivate(Constants::PRIKEY);
           
            openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

            return base64_encode($signature);


        } catch (Exception $e) {
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to signWithPrivateKey ");
        }
    }

    public static function verifySignature($signature, $message)
    {
        try {

            $pubKey = self::getPublicKey(Constants::PUBKEY);

            $result = openssl_verify($message, base64_decode($signature), $pubKey, OPENSSL_ALGO_SHA256);
            return $result === 1;
        } catch (Exception $e) {
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to verifySignature ");
        }
    }

    public static function getPublicKey($publicKeyContent)
    {
        try {
            $keyResource = RSA::loadPublicKey($publicKeyContent);
            if ($keyResource === false) {
                throw new Exception("Failed to get public key");
            }
            return $keyResource;
        } catch (Exception $e) {
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to getPublicKey ");
        }
    }

    public static function generateKeyPair(int $size = 2048)
    {
        try {
            // $rsa = new RSA();
            // $keyPair = $rsa->createKey();

            $privateKey = RSA::createKey($size);

            
            return [
                $privateKey,
                $privateKey->getPublicKey(),
            ];

        } catch (Exception $e) {
            self::handleException($e, PhoenixResponseCodes::INTERNAL_ERROR['CODE'], "Failure to generateKeyPair ");
        }
    }

 
    private static function handleException($exception, $errorCode, $errorMessage)
    {
        print_r($exception);
        echo "Exception trace: ", $exception->getTraceAsString();
        throw new SystemApiException($errorCode, $errorMessage);
    }
}