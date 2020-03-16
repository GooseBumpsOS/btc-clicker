<?php

$pid = $argv[1];

while (1) {
    if (!file_exists("/proc/{$pid}")) {
        //exec('nohup php update.php > /dev/null 2>&1 &');
        send_message(urlencode('Процесс в папке ' . getcwd() . ' упал'));
        die();
    }
    sleep(20);
}

function send_message($text, $chatId = -327595262){


    file_get_contents("https://api.telegram.org/bot863176881:AAHBJ2IUoNAIkxv9fLKpOfQop5eQe9p68gk/sendMessage?chat_id=$chatId&text=$text");

}