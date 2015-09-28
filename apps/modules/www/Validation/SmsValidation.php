<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator;

class SmsValidation extends Validation
{
    public function initialize() {

        $typeArr = $this->constant->SMS_TYPE ;
        $types   = array();
        foreach( $typeArr as $key => $value ){
            $types[] = $key;
        }

        $notNull = array( "mobile","smsType" );// 非空
        $reg     = array(
            "mobile" => "/^1[34578]\d{9}$/"
        );
        $include = array(
            "smsType" => $types
        );

        foreach( $include as $key => $value){
            $this->add( $key , new InclusionIn(array('message' => $key.'参数格式不正确 。', 'domain'  => $value )));
        }

        foreach( $notNull as $key ){
            $this->add($key , new PresenceOf(array( 'message' => $key."不能为空。")));
        }

        foreach ( $reg as $key => $pattern ){
            $this->add($key, new Regex(array( 'pattern' =>  $pattern, 'message' =>  $key."格式不正确。")));
        }




    }


}