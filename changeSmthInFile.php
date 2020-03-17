<?php


$dirs = scandir('/var/www/clicker/prod');
$dirs = array_diff($dirs, ['.', '..']);


foreach ($dirs as $dir){
	system("cp /var/www/clicker/mainWorker.php /var/www/clicker/prod/$dir/"); 
	}
