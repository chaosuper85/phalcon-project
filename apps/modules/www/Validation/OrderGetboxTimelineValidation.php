<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;

class OrderGetboxTimelineValidation extends Validation{
    public function initialize(){
        $noNull = array('orderboxid');
        foreach($noNull as $key)
            $this->add($key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
    }
}