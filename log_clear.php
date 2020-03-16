<?php
$dir = '/var/www/clicker';
$files = scandir($dir);
$removeDirs = array(".","..");
$files = array_diff($files, $removeDirs);

for($i = 0; $i<count($files); $i++)
	exec('echo "" > /var/www/clicker/prod/'.$files[$i].'/log.txt');
