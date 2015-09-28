<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Digit ;


class AccountDoapplyValidation extends Validation
{
    public function initialize()
    {
        // builddate  provinceId
        $this->add('enterpriseName', new PresenceOf(array(
            'message' => 'The enterpriseName is required'
        )));

        $this->add('provinceId', new PresenceOf(array(
            'message' => 'The provinceId is required'
        )));


        $this->add('builddate', new PresenceOf(array(
            'message' => 'The builddate is required'
        )));

        $this->add('licenceNumber', new PresenceOf(array(
            'message' => 'The licenceNumber is required'
        )));


        $alpha   = array( "licenceNumber" );
        foreach( $alpha as $key ){
            $this->add( $key , new Digit(array('message' => $key."只能是数字。")));
        }


        $this->add('licencePic', new PresenceOf(array(
            'message' => 'The licencePic is required'
        )));


        $this->add('cityCode', new PresenceOf(array(
            'message' => 'The cityCode is required'
        )));

//        $this->add('contactMobile_city', new PresenceOf(array(
//            'message' => 'The contactMobile is required'
//        )));
//
//        $reg     = array(
//            "contactMobile" =>"/^((0\d{2,3}-\d{7,8})|(1[34578]\d{9}))$/"
//        );
//
//        foreach ( $reg as $key => $pattern ){
//            $this->add($key, new Regex(array( 'pattern' =>  $pattern, 'message' =>  $key."格式不正确。")));
//        }

        /**
         *      'carteam' =>1 ,
                'freight_agent' =>2 ,
                'driver' =>3,
         */
        $userTypes = array("carteam", "freight_agent");
        $include = array( "type" => $userTypes);
        foreach( $include as $key => $value){
            $this->add( $key , new InclusionIn(array('message' => $key.'参数格式不正确 。', 'domain'  => $value )));
        }


    }
}
