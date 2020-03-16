<?php 
$dir = '/var/www/clicker';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

$btc = 0;
$ltc = 0;
$bch = 0;

for($i = 0; $i<count($files); $i++){
	$cash = json_decode(file_get_contents('/var/www/clicker/prod/'.$files[$i].'/balance.txt'),true);
	$btc += $cash[0]['@BitcoinClick_bot'];
	$ltc += $cash[0]['@Litecoin_click_bot'];
	$bch += $cash[0]['@BCH_clickbot'];
}

send_message(urlencode('BTC sum: '.$btc.', LTC sum: '.$ltc.', BCH sum: '.$bch));

function send_message($text, $chatId = -327595262){
 file_get_contents("https://api.telegram.org/bot863176881:AAHBJ2IUoNAIkxv9fLKpOfQop5eQe9p68gk/sendMessage?chat_id=$chatId&text=$text");
}
