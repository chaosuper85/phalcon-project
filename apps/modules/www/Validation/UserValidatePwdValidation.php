<?php
namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alnum ;

class UserValidatePwdValidation extends Validation {
    public function initialize(){
        $notNull = array('pwd');
        $reg = array(
            'pwd' => "/^[0-9a-zA-Z]{2,}$/"
        );
        foreach( $notNull as $key )
            $this->add($key , new PresenceOf(array(
                'message' => $key."不能为空"
            )));
        foreach ( $reg as $key => $pattern )
            $this->add($key, new Regex(array(
                'pattern' =>  $pattern,
                'message' =>  $key."格式不正确"
            )));
    }
}