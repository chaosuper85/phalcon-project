<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;


class AccountUpdateAValidation extends Validation
{
    public function initialize()
    {
        $this->add('enterpriseName', new PresenceOf(array(
            'message' => 'The enterpriseName is required'
        )));

        $this->add('idCardPic', new PresenceOf(array(
            'message' => 'The idCardPic is required'
        )));

        $this->add('backPic', new PresenceOf(array(
            'message' => 'The backPic is required'
        )));

        $this->add('licensePic', new PresenceOf(array(
            'message' => 'The licensePic is required'
        )));

        $this->add('teamName', new PresenceOf(array(
            'message' => 'The teamName is required'
        )));

        $this->add('teamType', new PresenceOf(array(
            'message' => 'The teamType is required'
        )));

        $this->add('teamPic', new PresenceOf(array(
            'message' => 'The teamPic is required'
        )));

        $this->add('contactName', new PresenceOf(array(
            'message' => 'The contactName is required'
        )));

        $this->add('contactNumber', new PresenceOf(array(
            'message' => 'The contactNumber is required'
        )));

        $this->add('licenseNumber', new PresenceOf(array(
            'message' => 'The licenseNumber is required'
        )));

        $this->add('ownerName', new PresenceOf(array(
            'message' => 'The ownerName is required'
        )));

        $this->add('ownerIdCard', new PresenceOf(array(
            'message' => 'The ownerIdCard is required'
        )));
    }
}
