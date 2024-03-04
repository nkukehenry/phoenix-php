<?php

use Interswitch\Phoenix\Simulator\ClientRegistration;
use Interswitch\Phoenix\Simulator\AccountInquiry;
use Interswitch\Phoenix\Simulator\BalanceInquiry;
use Interswitch\Phoenix\Simulator\PaymentNotification;
use Interswitch\Phoenix\Simulator\Utils\CryptoUtils;

require "vendor/autoload.php";


$results = ClientRegistration::main();
// $accountinquiry = AccountInquiry::main();
// $balanceInquiry = BalanceInquiry::main();
// $paymentNotification = PaymentNotification::main();

$encryptedClientId="bQnJ2Tp6YxadqPaTtBS8auBO3RibDm7eqmEHQdNQYcnZ4og2o9Oa3JouWDiz7e2siRatO0CWuEfH0h6+B2W8ERri4hDlZ59gLlaq1sX5jDKp4kTp3YF3daBNKTjZzm\/ogSuckcxru4wQhe+PJi8bCxh+nRP428Vrk\/ir88+nN6I6ftfkJCQ2+C68EneCqgj3PM\/3+GpiNYyu+V4Y9PTtl7i0mw6jQd63tNX37y7RMFg\/e8e1QZ\/9S1iMMFyMvAvefppv1QSoE2zvUsYqL6GrWgxY7eevacGgNfVDKXOxjjNU+a0zPJC5l28SaJuU11E93wb+uSU05QZPab4hi22CAw==";

$data = (is_array($results) || is_object($results))?json_encode($results):$results;

file_put_contents("logs.log", date('Y-m-d H:i:s')."\n\n".$data."\n\n\n", FILE_APPEND);

var_dump($results);
