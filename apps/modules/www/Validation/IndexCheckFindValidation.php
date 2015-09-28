<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class IndexCheckFindValidation extends Validation {
    public function initialize(){
        $this->add('mobileOrName', new PresenceOf(array(
            'message' => 'The mobileOrName is required'
        )));
    }
}