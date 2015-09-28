<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alnum ;
use Phalcon\Validation\Validator\InclusionIn;


/*
 *
    PresenceOf	    检测字段的值是否为非空
    Identical	    检测字段的值是否和指定的相同
    Email	        检测值是否为合法的email地址
    ExclusionIn	    检测值是否不在列举的范围内
    InclusionIn	    检测值是否在列举的范围内
    Regex	        检测值是否匹配正则表达式
    StringLength	检测值的字符串长度
    Between	        检测值是否位于两个值之间
    Confirmation	检测两个值是否相等
 */
class RegisterValidation extends Validation
{


    public function initialize() {
        $min = $this->constant->USER_NAME->MIN_LEN;
        $max = $this->constant->USER_NAME->MAX_LEN;

        $userTypes = array("carteam", "freight_agent");
        $notNull = array( "mobile","pwd","userName","smsCode","userType" );// 非空
        $reg     = array(
            "mobile" => "/^1[34578]\d{9}$/"
        );
        $strlen  = array( "userName" =>  array("max" => $max, "min" =>$min ),"pwd" =>array( "max" => 12, "min" =>6 ) );
        $alpha   = array( "userName" );
        $include = array( "userType" =>$userTypes);
        foreach( $notNull as $key ){
            $this->add($key , new PresenceOf(array( 'message' => $key."不能为空。")));
       }

        foreach ( $reg as $key => $pattern ){
            $this->add($key, new Regex(array( 'pattern' =>  $pattern, 'message' =>  $key."格式不正确。")));
        }
        foreach( $strlen as $key => $value ){
            $this->add($key, new StringLength(array(
                'max' => $value['max'],
                'min' => $value['min'],
                'messageMaximum' => $key." 长度在".$value['min']."-".$value['max']."个字符之间。",
                'messageMinimum' => $key." 长度在".$value['min']."-".$value['max']."个字符之间。"
            )));
        }

        foreach( $alpha as $key ){
            $this->add( $key , new Alnum(array('message' => $key."只能包含数字、字母等字符。")));
        }

        foreach( $include as $key => $value){
            $this->add( $key , new InclusionIn(array('message' => $key.'参数格式不正确 。', 'domain'  => $value )));
        }
    }




}