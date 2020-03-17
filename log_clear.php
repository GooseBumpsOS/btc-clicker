<?php
$dir = '/var/www/clicker/prod/';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

for($files as $i => $item)
	exec('echo "" > /var/www/clicker/prod/'.$files[$i].'/log.txt');
