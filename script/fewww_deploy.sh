#!/bin/sh
source ~/.bash_profile
t=`date "+%Y-%m-%d %H:%M:%S"`;

## test

out=/home/work/deploydir/logs/log_fewww_$t;

echo "begin: git pull origin staging" >> $out;
cd  /home/work/deploydir/frontend_www   &&  git pull origin staging ;
cd  /home/work/deploydir/frontend_www  && fis3 release staging -d /home/work/phpweb ;


echo "end: realase " >> $out;

