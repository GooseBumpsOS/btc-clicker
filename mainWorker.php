<?php

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

libxml_use_internal_errors(true);
$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

/*Settings vars*/
$channel = "@BitcoinClick_bot";
$limit = 10;
$offset_id = 0;
/*end of settings*/

$MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => '🖥 Visit sites']);
echo "\n\n\n\e[0;32mSend msg\n";

$countOfReq = 0;
for (; ;) { //inf loop

    do {
        $countOfReq++;

        echo "\e[1;33mWait for new link\n";

        sleep(2);

        $resData = $MadelineProto->messages->getHistory(['peer' => $channel, 'offset_id' => $offset_id, 'offset_date' => 0, 'add_offset' => 0, 'limit' => $limit, 'max_id' => 0, 'min_id' => 0, 'hash' => 0]);

        if ($countOfReq == 4 || $countOfReq == 8) {
            $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => '🖥 Visit sites']);
            sleep(1);
        }

        if ($countOfReq > 10) {
            echo "Sleep for 3 min\n";
            sleep(180);
            $countOfReq = 0;
            $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => '🖥 Visit sites']);

        }
    } while (!array_key_exists('reply_markup', $resData['messages'][0]));

    $url = $resData['messages'][0]['reply_markup']['rows'][0]['buttons'][0]['url'];
    sendRequest($url, 0);//отправлям для того чтобы получить время которое нужно оставиться на сайте
    if (($time = getTime($MadelineProto)) == false) {

        $MadelineProto->messages->getBotCallbackAnswer(['peer' => $channel, 'msg_id' => intval($resData['messages'][0]['id']), 'data' => $resData['messages'][0]['reply_markup']['rows'][1]['buttons']['1']['data']]);
        continue;
    } else {

        sendRequest($url, $time);

    }


}

function getTime($md, $channel = "@BitcoinClick_bot", $offset_id = 0, $limit = 3)
{

    $iterCount = 0;
    do {
        $msgArray = $md->messages->getHistory(['peer' => $channel, 'offset_id' => $offset_id, 'offset_date' => 0, 'add_offset' => 0, 'limit' => $limit, 'max_id' => 0, 'min_id' => 0, 'hash' => 0]);
        $msg = $msgArray['messages'][0]['message'];

        preg_match('/\d+\ssec/', $msg, $time);
        $iterCount++;
        sleep(1.2);
        echo "Wait for time var\n";
    } while (count($time) == 0 && $iterCount < 3);


    if (count($time) > 0)
        return intval(trim(substr($time[0], 0, -4)));
    else
        return false;

}

/*
 * @param array $postPar Здесь параметры для post запроса, идут в формате: { 1 - code ; 2 - token }
 */
function _simpleRequest($url, $sleep, $postPar = [])
{

    $ch = curl_init(); // Инициализация сеанса
    curl_setopt($ch, CURLOPT_URL, $url); // Куда данные послать
    curl_setopt($ch, CURLOPT_HEADER, 0); // получать заголовки
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); // это необходимо, чтобы cURL не высылал заголовок на ожидание
    if (count($postPar) == 2) {//isPost
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "code={$postPar['code']}&token={$postPar['token']}");
    }

    $tempRes = curl_exec($ch);
    sleep($sleep);

    curl_close($ch); // Завершаем сеанс

    return $tempRes;

}


function sendRequest($url, $sleep = 0)
{

    echo "Send request by curl and sleep for $sleep sec\n";

    $res = _simpleRequest($url, $sleep);


    $d = new DOMDocument();
    $d->loadHTML($res);
    $xpath = new DOMXPath($d);
    $datasConainer = $xpath->query("//div[contains(@id, 'headbar')]");
    if ($datasConainer->length != 0)
        if (!empty($datasConainer[0]->getAttribute('data-code'))) {//на всякий случай

            $code = $datasConainer[0]->getAttribute('data-code');
            $token = $datasConainer[0]->getAttribute('data-token');

            _simpleRequest('https://dogeclick.com/reward', 0, ['code' => $code, 'token' => $token]);

        }


}
