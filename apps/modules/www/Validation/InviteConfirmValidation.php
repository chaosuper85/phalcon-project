<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class InviteConfirmValidation extends Validation {
    public function initialize(){
        $this->add('invite_id', new PresenceOf(array(
            'message' => 'The invite_id is required'
        )));
    }
}