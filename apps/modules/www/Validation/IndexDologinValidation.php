<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class IndexDologinValidation extends Validation {
    public function initialize(){
        $this->add('username', new PresenceOf(array(
            'message' => 'The username is required'
        )));
        $this->add('password', new PresenceOf(array(
            'message' => 'The password is required'
        )));
    }
}