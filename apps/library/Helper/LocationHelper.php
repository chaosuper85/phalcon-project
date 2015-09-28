<?php
/**
 * Created by PhpStorm.
 * User: xiaoqizhi
 */

namespace Library\Helper;

class LocationHelper
{
    const GAODE_GEO_KEY = "4f86a6b07ce995446e90869a6e9c5ef0";

    /**
     * @param $adress
     * @return bool
     */
    public static function getLocationByAdress($adress)
    {

        $url = "http://restapi.amap.com/v3/geocode/geo?address=" . $adress . "&key=" . SELF::GAODE_GEO_KEY;
        $my_curl = curl_init();
        curl_setopt($my_curl, CURLOPT_URL, $url);
        curl_setopt($my_curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($my_curl);    //执行请求
//      echo $str;    //输出抓取的结果
        $data = json_decode($str, true);

        if (isset($data['geocodes'][0]['location'])) {
            return $data['geocodes'][0]['location'];
        } else {
//          echo 'adress not found';
            return false;
        }

    }

    /**
     * @param $locationStr
     * @return bool
     */
    public static function getAdressByLocation($locationStr)
    {
        $url = "http://restapi.amap.com/v3/geocode/regeo?location=" . $locationStr . "&extensions=base&output=json&key=" . SELF::GAODE_GEO_KEY;
        $fhd = file_get_contents($url);
        // echo $fhd;
        $data = json_decode($fhd, true);
        if (isset($data['regeocode']['formatted_address'])) {
            return $data['regeocode']['formatted_address'];
        } else {
//            echo 'location not found';
            return false;
        }

    }


}