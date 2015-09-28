<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

class MsgP2PSendValidation extends Validation {
    public function initialize(){
        $this->add('rec_id', new PresenceOf(array(
            'message' => 'The rec_id is required'
        )));

        $this->add('msg_type', new PresenceOf(array(
            'message' => 'The msg_type is required'
        )));

        $this->add('msg_title', new PresenceOf(array(
            'message' => 'The msg_title is required'
        )));

        $this->add('msg_content', new PresenceOf(array(
            'message' => 'The msg_content is required'
        )));

        $this->add('deal_url', new PresenceOf(array(
            'message' => 'The deal_url is required'
        )));
    }
}