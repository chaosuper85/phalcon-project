<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class InviteSendValidation extends Validation {
    public function initialize(){
        $this->add('mobile', new PresenceOf(array(
            'message' => 'The mobile is required'
        )));
    }
}