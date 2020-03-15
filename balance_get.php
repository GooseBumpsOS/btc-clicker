<?php 
$dir = '/var/www/clicker';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

$btc = 0;
$ltc = 0;
$bch = 0;

for($i = 0; $i<count($files); $i++){
	exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c true > /dev/null 2>&1 &');
	$cash = json_decode(file_get_contents('/var/www/clicker/'.$files[$i].'/balance.txt'),true);
	$btc = $cash[0]['@BitcoinClick_bot'];
	$ltc = $cash[0]['@Litecoin_click_bot'];
	$bch = $cash[0]['@BCH_clickbot'];

	if($btc >= 0.00003){
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c @BitcoinClick_bot -a '.$btc.' > /dev/null 2>&1 &');
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c true > /dev/null 2>&1 &');
		send_message(urlencode('С аккаунта: '.$files[$i].' было выведено: '.$btc.' BTC'));
	}
	if($ltc >= 0.0004){
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c @Litecoin_click_bot -a '.$ltc.' > /dev/null 2>&1 &');
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c true > /dev/null 2>&1 &');
		send_message(urlencode('С аккаунта: '.$files[$i].' было выведено: '.$ltc.' LTC'));
	}

	if($bch >= 0.0001){
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c @BCH_clickbot -a '.$bch.' > /dev/null 2>&1 &');
		exec('nohup php /var/www/clicker/'.$files[$i].'/balance-cli.php -c true > /dev/null 2>&1 &');
		send_message(urlencode('С аккаунта: '.$files[$i].' было выведено: '.$bch.' BCH'));
	}
	
}


function send_message($text, $chatId = -327595262){
 file_get_contents("https://api.telegram.org/bot863176881:AAHBJ2IUoNAIkxv9fLKpOfQop5eQe9p68gk/sendMessage?chat_id=$chatId&text=$text");
}
