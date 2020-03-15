#!/bin/bash
timeVar=date +%s;
mkdir $timeVar;
cp /home/georgy/MDInit/mainWorker.php $timeVar;
cp /home/georgy/MDInit/balance-cli.php $timeVar;
cd timeVar;
php -f init.php
rm init.php
