<?php
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

//channel : amount
$options = getopt('c:a:');
$optionsCount = count($options);

if ($optionsCount == 1)
    file_put_contents('balance.txt', json_encode(getBalance($MadelineProto, $options['c'])));
elseif ($optionsCount == 2)
    withdraw($MadelineProto, $options['c'], $options['a']);
else
    die('Ошибка в параметрах' . "\n");

if (!file_exists('balance.txt'))
    echo 'Вывод произведен успешно' . PHP_EOL;
else {
    echo "Произошла ошибка";
}

function getBalance($MadelineProto, $channel)
{
    $resArr = [];
    $channels = ["@BitcoinClick_bot", "@Litecoin_click_bot", "@BCH_clickbot"];

    foreach ($channels as $channel){
        $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => 'Balance']);
        sleep(1);
        $msgArray = $MadelineProto->messages->getHistory(['peer' => $channel, 'offset_id' => 0, 'offset_date' => 0, 'add_offset' => 0, 'limit' => 3, 'max_id' => 0, 'min_id' => 0, 'hash' => 0]);

        preg_match('/[0-9.]+/m', $msgArray['messages'][0]['message'], $balance);
        $resArr[$channel] = $balance[0];
    }

    return $resArr;


}

function withdraw($MadelineProto, $channel, $amount)
{

    $chanelToWalletFile = ["@BitcoinClick_bot" => 'btc.txt', "@Litecoin_click_bot" => 'ltc.txt', "@BCH_clickbot" => 'bch.txt'];
    $wallets = file($chanelToWalletFile[$channel]);
    $btcAddress = $wallets[array_rand($wallets)];

    $messages = ['💰💰Balance', 'Withdraw', $btcAddress, $amount, '✅ Confirm'];

    foreach ($messages as $message) {
        $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => $message]);
        sleep(1.5);
    }
}


//BTC min - 0.00003 (3000)
//LTC min - 0.0004
//BCH min - 0.0001