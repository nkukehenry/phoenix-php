<?php
use Elliptic\EC;

require "vendor/autoload.php";

$base64String = "AnA0ygflnFhx0dsXt2XG0IWp835nlYh3bTfDmEUS+fIg";
$publicKeyBinary = base64_decode($base64String);
$pub = bin2hex($publicKeyBinary);

$ec   = new EC('secp256r1');
//$key  = $ec->keyFromPublic($pub, 'hex');

if ($key = $ec->keyFromPublic($pub, 'hex')) {
	echo "Success\n";
    print_r($key);
} else {
    echo "Fail\n";
}

$priv_hex = "209f6117069dd58449a67c0a295507a1af6b328e765c89328508b507dcd76463";

if ($pri_key = $ec->keyFromPrivate($priv_hex)) {
	echo "Success\n";
    print_r($pri_key);
} else {
    echo "Fail\n";
}

