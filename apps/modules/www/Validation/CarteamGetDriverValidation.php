<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class CarteamGetDriverValidation extends Validation{
    public function initialize(){
        $noNull = array('login_user');
        foreach($noNull as $key)
            $this->add($key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
    }
}