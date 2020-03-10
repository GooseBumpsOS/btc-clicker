<?php

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

//channel : amount
$options = getopt('c:a:');
$optionsCount = count($options);

if ($optionsCount == 1)
    getBalance($MadelineProto, $options['c']). PHP_EOL;
elseif ($optionsCount == 2)
    withdraw($MadelineProto, $options['c'], $options['a']);
else
    die('Ошибка в параметрах' . "\n");

echo 'Скрипт отработал успешно' . PHP_EOL;


function getBalance($MadelineProto, $channel){

    $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => 'Balance']);
    $msgArray = $MadelineProto->messages->getHistory(['peer' => $channel, 'offset_id' => 0, 'offset_date' => 0, 'add_offset' => 0, 'limit' => 3, 'max_id' => 0, 'min_id' => 0, 'hash' => 0]);

    preg_match('/[0-9.]+/m', $msgArray['messages'][0]['message'], $balance);
    return $balance[0][0];

}

function withdraw($MadelineProto, $channel, $amount){

    $chanelToWalletFile = ["@BitcoinClick_bot" => 'btc.txt', "@Litecoin_click_bot" => 'ltc.txt', "@BCH_clickbot" => 'bch.txt'];
    $wallets = file($chanelToWalletFile[$channel]);
    $btcAddress = $wallets[array_rand($wallets)];

    $messages = ['💰💰Balance', 'Withdraw', $btcAddress, $amount, '✅ Confirm'];

    foreach ($messages as $message){
        $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => $message]);
        sleep(1.5);
    }
}


//BTC min - 0.00003 (3000)
//LTC min - 0.0004
//BCH min - 0.0001