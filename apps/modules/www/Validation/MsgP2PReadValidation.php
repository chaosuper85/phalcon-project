<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class MsgP2PReadValidation extends Validation {
    public function initialize(){
        $this->add('id', new PresenceOf(array(
            'message' => 'The id is required'
        )));
    }
}