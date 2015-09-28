<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alnum ;



class AgentCreateOrderValidation extends Validation
{
    public function __construct( )
    {
        $data = array("tidan","carteamId","tixiang","chanzhuang","yundan");
        $alpha   = array( "tidan","yundan" ,"carteamId" );
        foreach( $alpha as $key ){
            $this->add( $key , new Alnum(array('message' => $key."只能包含数字、字母等字符。")));
        }
        foreach( $data as $key ){
            $this->add( $key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
        }

    }
}