<?php 
$dir = '/var/www/clicker/prod/';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

$btc = 0;
$ltc = 0;
$bch = 0;

foreach($files as $i => $item){
	$cash = json_decode(file_get_contents('/var/www/clicker/prod/'.$files[$i].'/balance.txt'),true);
	$btc = $btc+$cash['@BitcoinClick_bot'];
	$ltc += $cash['@Litecoin_click_bot'];
	$bch += $cash['@BCH_clickbot'];
//echo $btc.'/'.$ltc.'/'.$bch.PHP_EOL;
}


$btc = rtrim(number_format($btc, 10, '.', ''), '0');
$bch = rtrim(number_format($bch, 10, '.', ''), '0');

send_message(urlencode('BTC sum: '.$btc.', LTC sum: '.$ltc.', BCH sum: '.$bch));

function send_message($text, $chatId = -327595262){
 file_get_contents("https://api.telegram.org/bot863176881:AAHBJ2IUoNAIkxv9fLKpOfQop5eQe9p68gk/sendMessage?chat_id=$chatId&text=$text");
}
