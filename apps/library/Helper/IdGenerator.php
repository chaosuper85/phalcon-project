<?php
/**
 * Created by PhpStorm.
 * User: xiaoqizhi
 * Date: 15/7/24
 * Time: 下午4:53
 */

namespace Library\Helper;

class IdGenerator
{
    public static function guid()
    {
        if (function_exists('com_create_guid')) {
//            echo 'has funxtion';
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(64);// "@"
            $uuid = chr(123)// "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);// "}"
             $subUuid = substr($uuid,1,strlen($uuid)-2);
            return $subUuid;
        }
    }


}