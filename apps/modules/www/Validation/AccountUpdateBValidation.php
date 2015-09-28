<?php namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;


class AccountUpdateBValidation extends Validation
{
    public function initialize()
    {

        $notNull = array( 'avatarPicUrl','realName','contactMobile_city','contactMobile_number' );
        $reg     = array(
            "contactMobile"    => "/^1[34578]\d{9}$/",
        );

        foreach( $notNull as $key ){
            $this->add($key , new PresenceOf(array( 'message' => $key."不能为空。")));
        }


        foreach ( $reg as $key => $pattern ){
            $this->add( $key, new Regex(array( 'pattern' =>  $pattern, 'message' =>  "手机号或座机号格式不正确。")));
        }
    }
}
