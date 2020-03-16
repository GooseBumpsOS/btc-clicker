#!/bin/bash
timeVar=`date +%s`;
mkdir $timeVar;
cp /var/www/clicker/mainWorker.php /var/www/clicker/prod/$timeVar;
cp /var/www/clicker/balance-cli.php /var/www/clicker/prod/$timeVar;
cd $timeVar;
php -f init.php
rm init.php
