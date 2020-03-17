<?php

$proxys =  getProxy();

foreach ($proxys as $proxy){

	echo $proxy;
	
}	

function getProxy()
{
    copy('https://api.proxyscrape.com/?request=getproxies&proxytype=socks5&timeout=10000&country=all', 'proxy.txt');
    $ipArr = file('proxy.txt');
    while(1){
        $randEl = rand(0, count($ipArr)-1);
        $proxy = $ipArr[$randEl];
//todo проверка по базе занят ли прокси другим
        if(strlen(sendCurl('http://telegram.org', $proxy)) != 0){
//todo запись в базу
            yield $proxy;
        } 

    }
}



function sendCurl($url,$proxy){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);

    return $curl_scraped_page;
}

