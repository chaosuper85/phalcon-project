<?php

namespace Library\Helper;

use Library\Log\Logger;

class StringHelper
{

    public  static  function isMobileNumber( $t )
    {
        if( preg_match("/^1[34578]\d{9}$/", $t) ){

            return true;
        }
        return false;

    }

    public  static  function getUri( $url )
    {
        $retUrl = $url;
        $p = strpos($url, '?');
        if( $p ){
            $retUrl = substr($url, 0, $p);
        }
        return $retUrl;
    }

    /**
     * Generate a "random" alpha-numeric string.

     * Should not be considered sufficient for cryptography, etc.
     *
     * @param  int  $length
     * @return string
     */
    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     *
     */
    public static function  markStr($string,$begin,$length)
    {
        if( empty($string) ){
            return $string;
        }
        $markStr = "";
        for($i=0 ;$i < $length;$i++){
            $markStr.="*";
        }
        return substr_replace($string,$markStr,$begin,$length);
    }

    /**
     *  判断字符串是  字母和数字的结合体 (只包含数字 或 字母)
     *   是 (只包含数字或字母)  true
     *   否 false
     */
     public static  function isNumORChar( $string ){
         if( preg_match('/^[0-9a-zA-Z\\s]+$/',$string) ){
              return true;
         }else{
             return  false;
         }
     }

    /**
     *  判断字符串 是否包含 （字母和数字）
     *  包含 true
     *  不包含 或者 只有字母 或 只有数字 =》 false
     */
    public static function hasNumAndChar( $string ){
        if( preg_match('/([a-zA-Z])/',$string ) <= 0 || preg_match('/([0-9])/',$string) <= 0 ){
            return false ;
        }else{
            return true  ;
        }
    }

    /**
     *  过滤数字，返回只包含 字母的 数组
     */
    public static function filterNum( $string ){
        $pattern = '/([0-9]+)/';
        $results = preg_split($pattern,$string);
        $data = array();
        foreach( $results as $res){
            if( !empty( trim($res)) ){
                $data[] = $res;
            }
        }
        return $data;
    }
    /**
     *  用 非文字字符 为刀对字符进行分割 返回 数组， 能够过滤‘的’‘与’‘和’
     */
    public static function filterNoChar($string){
        $pattern = '[^\u4e00-\u9fa5a-zA-Z0-9]';
        $results = preg_split($pattern, $string);
        $data = array();
        foreach($results as $res)
            if(!empty(trim($res)))
                $data[] = $res;
        return $data;
    }
    /**
     *  现在 只管 拿一个字符串去查数据
     *  如果匹配返回 true
     */
    public static function isMatch($arr1, $arr2){
        return ($len = strlen($arr1)) == 0 ? false : strcmp($arr1, mb_substr($arr2, 0, $len));
    }
    /**
     *  提单号 生成器
     */
    public static function autoBillNum(){

    }
    /**
     *  sql 语句优化
     */
    public  static  function  buildSql( &$sql , $paramsArr){
        /*
         * @wh, 代码逻辑有点问题，抽空一起看下
         */
        if( !empty( $paramsArr) ){
            $flag = true;
            foreach( $paramsArr as $key => $value ){
                if( $flag && is_int($key)){ // 替换 ？
                    $sql = str_replace("?","%s",$sql);
                    $sql = vsprintf( $sql,$paramsArr);
                    break;
                }else{ // 替换  ?1 =>{ :1 => "" }    :params: => { params }
                    $flag = false;
                    if( preg_match('/(\:'.$key.')/',$sql)){
                        $sql = str_replace(":".$key,$value, $sql);
                    }else{
                        $sql = str_replace($key,$value,$sql);
                    }
                }
            }
        }
    }


    /**
     *  过滤URl
     */
    public static  function  filterUri( $uri ){
       $result = preg_replace('/(http:\/\/*)|(www\.*)/',"",$uri);
        if( !self::startWith( $uri,"/") ){
            $result = "/".$result;
        }
        return $result;
    }

    /**
     *   判断字符串 $str 是否以 $prefix 开头
     *    是 true
     *    否则 false
     */
    public static function  startWith( $str ,$prefix ){
        $ret = strchr( $str, $prefix ,true);
        if(  $ret === "" ){
            return true;
        }else{
            return false;
        }
    }

    /**
     *   判断字符串 $str 是否以 $suffix 结尾
     *      是 true
     *    否则 false
     */
    public static function  endWith( $str ,$suffix ){
        $ret = strchr( $str,$suffix);
        if(  $ret === $suffix ){
            return true;
        }else{
            return false;
        }
    }

    /**
     *  string convert to Date()
     */
    public static function strToDate( $str ,$format ="Y-m-d H:i:s"){
        if( empty($str) ){
            return "";
        }else if( is_int( $str )){
            return  date($format,intval( $str));
        }else{
            return date( $format, strtotime( $str ));
        }
    }
    //检查字符串是否含有汉字
    public static  function isHaveHanzi($str){
        return preg_match("/[\x7f-\xff]/", $str);
    }

    /**
     *  获取文件扩展名 doc  docx
     */
    public static  function getExtension( $str ){
        if( empty( $str ) ){
            return "";
        }else{
            $start = strrpos( $str,".",-1 )+1 ;
            return  substr( $str, empty( $start )? 0: $start ,strlen( $str ) );
        }
    }
}