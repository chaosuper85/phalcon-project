<?php
namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alnum ;
use Phalcon\Validation\Validator\InclusionIn;

class UserChangeBindValidation extends Validation {
    public function initialize(){
        $reg = array(
            'mobile' => "/^1[34578]\d{9}$/",
            'code' => "/^\d{4}$/"
        );
        $include = array( "smsType" => array('CHANGE_MOBILE'));
        foreach ( $reg as $key => $pattern )
            $this->add($key, new Regex(array(
                'pattern' =>  $pattern,
                'message' =>  $key."格式不正确"
            )));

        foreach( $include as $key => $value){
            $this->add( $key , new InclusionIn(array('message' => $key.'参数格式不正确 。', 'domain'  => $value )));
        }
    }
}