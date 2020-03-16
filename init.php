<?php

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$MadelineProto->messages->sendMessage(['peer' => '-327595262', 'message' => 'Новый скрипт запущен']);

system('nohup php -f mainWorker.php > log.txt 2>/dev/null &');
