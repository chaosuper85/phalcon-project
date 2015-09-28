<?php
namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alnum ;

class UserChangePwdValidation extends Validation {
    public function initialize(){
        $notNull = array('newPwd', 'mobile');
        $reg = array(
            'mobile' => "/^1[34578]\d{9}$/"
        );

        $strlen  = array( "newPwd" =>array( "max" => 12, "min" =>6 ) );
        foreach( $notNull as $key )
            $this->add($key , new PresenceOf(array(
                'message' => $key."不能为空"
            )));

        foreach ( $reg as $key => $pattern )
            $this->add($key, new Regex(array(
                'pattern' =>  $pattern,
                'message' =>  $key."格式不正确"
            )));

        foreach( $strlen as $key => $value ){
            $this->add($key, new StringLength(array(
                'max' => $value['max'],
                'min' => $value['min'],
                'messageMaximum' => $key." 长度在".$value['min']."-".$value['max']."个字符之间。",
                'messageMinimum' => $key." 长度在".$value['min']."-".$value['max']."个字符之间。"
            )));
        }
    }
}