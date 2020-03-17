#!/bin/bash

for d in prod/*/ ; do
	cd /var/www/clicker/"$d";
	#nohup php -f mainWorker.php > log.txt 2>/dev/null &
	php init.php;
	sleep 20;
	cd /var/www/clicker;
done
