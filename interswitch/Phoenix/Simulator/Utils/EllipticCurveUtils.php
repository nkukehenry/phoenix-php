<?php

namespace Interswitch\Phoenix\Simulator\Utils;

use Elliptic\EC;

class EllipticCurveUtils
{
    
    public function doECDH( $ecdhPrivate,$serverPublicKeyBase64) {
        
        $ec = new EC('secp256r1');

        $publicKeyBinary = base64_decode($serverPublicKeyBase64);
        $pub_hex = strtoupper(bin2hex($publicKeyBinary));
        
        $publicHexPadded  = $pub_hex;
        $privateHexPadded = str_pad($ecdhPrivate, 64, "0", STR_PAD_LEFT);

        $privateKey     = $ec->keyFromPrivate($privateHexPadded);
        $servePublickey = $ec->keyFromPublic($publicHexPadded, 'hex');
        $servePublickeyObject =$servePublickey->getPublic();

        $sessionKey     = null;

        echo "Private Key";
        print_r($privateKey);
        echo "<br><br>";
        echo "Public Key";
        print_r($servePublickey);
        echo "<br><br>";
         
        if ($sessionKey = $privateKey->derive($servePublickeyObject)) {

            echo "<br><br>Raw Session Key <br>";
            print_r($sessionKey);
            echo '<br><br>';

            
            $cleanedSessionKey         =  strtoupper(str_replace("\"","",json_encode($sessionKey)));
           
            echo "<br><br>Session Key Length ".strlen($cleanedSessionKey).'<br><br>';

            $sessionKeyHexPadded  = str_pad($cleanedSessionKey, 64, "0", STR_PAD_LEFT);
            $sessionKeyBinData    =  hex2bin($sessionKeyHexPadded);

            $sessionKey = base64_encode($sessionKeyBinData);

             echo "DO ECDH Success\n";
             echo "Session Key:: \n $sessionKey";
        } 
        else {

          echo "ECDH Failed\n";
        }

        return $sessionKey;
    }

    public function generateKeypair()
    {
        
        $ec   = new EC('secp256r1');
        $privateKeyObject = $ec->genKeyPair();

        echo "<br><br>Key Pair:: ";
        print_r($privateKeyObject);
        echo "<br><br>";

        $pub_hex    = strtoupper($privateKeyObject->getPublic(true, "hex"));

        echo "<br><br>Public Key Hex ".$pub_hex.'<br><br>';
         echo "<br><br>Public Key Length ".strlen($pub_hex).'<br><br>';


        //$publicHexPadded = str_pad($pub_hex, 64, "0", STR_PAD_LEFT);
        $publicKey  =  hex2bin($pub_hex);
        $privateKey =  str_replace("\"","",json_encode($privateKeyObject->getPrivate()));

        $pair = [$privateKey, base64_encode($publicKey)];

        echo "<br><br>Key Pair Array:: ";
        print_r($pair);

        return $pair;
    }

 
}