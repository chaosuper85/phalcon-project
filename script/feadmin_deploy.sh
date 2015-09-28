#!/bin/sh
source ~/.bash_profile
t=`date "+%Y-%m-%d %H:%M:%S"`;
out=/home/work/zhuchao/deploy/log/log_feadmin_$t;

echo "begin: git pull origin staging" >> $out;

cd  /home/work/deploydir/frontend_admin   &&  git pull origin staging ;
cd  /home/work/deploydir/frontend_admin && fis3 release staging -d /home/work/phpweb ;


echo "end: realase " >> $out;

