#!/bin/sh
source ~/.bash_profile

t=`date "+%Y-%m-%d"`;
out=/home/work/deploydir/logs/log_$t;

su work ;

echo "begin: git pull origin staging" >> $out;
cd /home/work/phpweb &&  git pull origin staging;
unset GIT_DIR && cd /home/work/fe_test/yk/phpweb  &&   git pull origin staging;
unset GIT_DIR && cd /home/work/fe_test/wll/phpweb  &&   git pull origin staging;
unset GIT_DIR && cd /home/work/fe_test/wsl/phpweb  &&   git pull origin staging;
unset GIT_DIR && cd /home/work/fe_test/wzq/phpweb  &&   git pull origin staging;

echo "end: git pull origin staging" >> $out;