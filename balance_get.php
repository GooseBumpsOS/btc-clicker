<?php 
$dir = '/var/www/clicker';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

$btc = 0;
$ltc = 0;
$bch = 0;

for($i = 0; $i<count($files); $i++){
	exec('nohup php /var/www/clicker/prod/'.$files[$i].'/balance-cli.php -c true > /var/www/clicker/prod/'.$files[$i].'/log.txt 2>&1 &');
	$pid = file_get_contents('/var/www/clicker/prod/'.$files[$i].'/pid.txt');
	$cash = json_decode(file_get_contents('/var/www/clicker/prod/'.$files[$i].'/balance.txt'),true);
	
	$btc = $cash[0]['@BitcoinClick_bot'];
	$ltc = $cash[0]['@Litecoin_click_bot'];
	$bch = $cash[0]['@BCH_clickbot'];

	if($btc >= 0.00003)
		checkBalance($pid, $files[$i], '@BitcoinClick_bot', $btc, 'BTC');
	if($ltc >= 0.0004)
		checkBalance($pid, $files[$i], '@Litecoin_click_bot', $ltc, 'LTC');
	if($bch >= 0.0001)
		checkBalance($pid, $files[$i], '@BCH_clickbot', $bch, 'BCH');
	
}

function checkBalance($pid, $dir, $channel, $balance, $coin){
	exec('kill -s SIGSTOP '.$pid);
	exec('nohup php /var/www/clicker/prod/'.$dir.'/balance-cli.php -c '.$channel.' -a '.$balance.' > /var/www/clicker/prod/'.$dir.'/log.txt 2>&1 &');
	exec('nohup php /var/www/clicker/prod/'.$dir.'/balance-cli.php -c true > /var/www/clicker/prod/'.$dir.'/log.txt 2>&1 &');
	exec('kill -s SIGCONT '.$pid);
	send_message(urlencode('С аккаунта: '.$dir.' было выведено: '.$balance.' '.$coin));
}

function send_message($text, $chatId = -327595262){
 file_get_contents("https://api.telegram.org/bot863176881:AAHBJ2IUoNAIkxv9fLKpOfQop5eQe9p68gk/sendMessage?chat_id=$chatId&text=$text");
}
