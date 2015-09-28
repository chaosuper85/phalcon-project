<?php

namespace Library\Helper;

/**
 *
 */
class ObjectHelper
{
    /** 如果 null 就默认
     * @param $object
     * @param string $default
     */
    public static  function ifNullDefault( &$object, $default = ""){
        if( !empty($object) ){
            if( is_array( $object ) ){ //
                foreach( $object as $key => $value ){
                    if( null === $value ){
                        $object[ $key ] = $default;
                    }
                }
            }else if(is_object($object)){
               $propsArr = get_object_vars($object);
                foreach( $propsArr as $key => $value ){
                     if( null === $value ){
                         $object->$key = $default;
                     }
                }
            }else{
                $object = $object === null ? $default: $object;
            }
        }
    }
}