<?php

namespace Library\Helper;

use Library\Log\Logger;

class ArrayHelper
{

    public  static  function  objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }

        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }

        return array_map( 'self::objectToArray', $object );
    }


    public  static  function  validateMessages( $messages )
    {
        $error_msg = array();

        foreach ($messages as $message) {
            $field = $message->getField();
            $msg =  $message->getMessage();

            $error_msg[$field] =  $msg;
        }

        return  $error_msg;
    }

    /**
     * 根据数组中的某个键值大小进行排序，仅支持二维数组
     *
     * @param array $array 排序数组
     * @param string $key 键值
     * @param bool $asc 默认正序
     * @return array 排序后数组
     */
    public static function arraySortByKey(array $array, $key, $asc = true)
    {
        $result = array();
        // 整理出准备排序的数组
        foreach ( $array as $k => &$v ) {
            $values[$k] = isset($v[$key]) ? $v[$key] : '';
        }
        unset($v);
        // 对需要排序键值进行排序
        $asc ? asort($values) : arsort($values);
        // 重新排列原有数组
        foreach ( $values as $k => $v ) {
            $result[$k] = $array[$k];
        }

        return $result;
    }


    /**
     *  多级Array to  一级
     */
    public static  function toArray( $arr ){
        $result = array();
        if(!empty( $arr ) && is_array($arr)){
            foreach( $arr as $key => $value ){
                $result[ $key ] = $value;
                if( is_array($value) ){
                    $result = array_merge( $result,self::toArray( $value ));
                }
            }
        }else{
            $result = $arr;
        }
        return $result;
    }

    /**
     * 忽略大小写
     */
    public static function inArray( $value, $array ){
        if( empty( $array ) ){
            return false;
        }
        foreach( $array as $key ){
            $res = strcasecmp( $value, $key);
            if( $res == 0 ){ // 相等
                return true;
            }
        }
        return false;
    }

    /**
     *  过滤 相同的时间 数组
     */
    public static function filterSameTime( $timeArr  ){
        $result = array();
        $cache  = array();
        try{
            if( !empty( $timeArr ) ){
                foreach( $timeArr as $time ){
                    $timeInt = strtotime( $time );
                    if( !in_array( $timeInt, $cache )){
                        $result[] = StringHelper::strToDate( $timeInt );
                        $cache [] = $timeInt;
                    }
                }
            }else{
                $result = $timeArr;
            }
        }catch (\Exception $e){
            Logger::warn("filter Same time error:%s",$e->getMessage());
            $result = $timeArr;
        }
        return $result;
    }






}