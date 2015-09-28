#!/bin/sh

table=$1;
mysqldump -uroot -pwork -h123.59.59.233 -P3306 phalcon $table > $table.sql
