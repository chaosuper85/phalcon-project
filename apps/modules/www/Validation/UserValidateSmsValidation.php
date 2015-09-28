<?php
namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alnum ;

class UserValidateSmsValidation extends Validation {
    public function initialize(){
        $reg = array(
            'mobile' => "/^1[34578]\d{9}$/",
            'code' => "/^\d{4}$/"
        );
        foreach ( $reg as $key => $pattern )
            $this->add($key, new Regex(array(
                'pattern' =>  $pattern,
                'message' =>  $key."格式不正确"
            )));
    }
}