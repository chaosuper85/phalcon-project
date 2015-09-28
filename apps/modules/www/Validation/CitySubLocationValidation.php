<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class CitySubLocationValidation extends Validation {
    public function initialize(){
        $this->add('codeid', new PresenceOf(array(
            'message' => 'The codeid is required'
        )));
    }
}