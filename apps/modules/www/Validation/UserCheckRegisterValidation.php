<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;

class UserCheckRegisterValidation extends Validation{
    public function initialize(){
        $noNull = array('register_code');
        foreach($noNull as $key)
            $this->add($key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
    }
}