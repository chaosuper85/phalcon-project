<?php namespace Modules\admin\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\InclusionIn,
    Phalcon\Validation\Validator\Regex;

/*
 *
PresenceOf	检测字段的值是否为非空
Identical	检测字段的值是否和指定的相同
Email	检测值是否为合法的email地址
ExclusionIn	检测值是否不在列举的范围内
InclusionIn	检测值是否在列举的范围内
Regex	检测值是否匹配正则表达式
StringLength	检测值的字符串长度
Between	检测值是否位于两个值之间
Confirmation	检测两个值是否相等
 */
class ApiAccountValidation extends Validation
{
    const PWD_REG = "/^[0-9a-zA-Z]{2,}$/";
    const MOBILE_REG = "/^1[34578]\d{9}$/";

    public function __construct($function='')
    {
        switch($function)
        {
//            case 'list':
//                $this->add('msg', new PresenceOf(array(
//                    'message' => 'The reject:msg is required'
//                )));

//            case 'drivers':
//            case 'auditDetail':
            case 'add' :
                $this->add('name', new PresenceOf(array(
                    'message' => 'The name is required'
                )));
                $this->add('pwd', new PresenceOf(array(
                    'message' => "密码格式不正确,只允许数字字母的组合",
                    'pattern' =>  self::PWD_REG,
                )));
                $this->add('mobile', new Regex(array(
                    'message' => "手机格式不正确",
                    'pattern' =>  self::MOBILE_REG,
                    'allowEmpty' => true
                )));
                $this->add('real_name', new PresenceOf(array(
                    'message' => 'The real_name is required'
                )));
            break;

            case 'alter' :
                $this->add('mobile', new Regex(array(
                    'message' => "手机格式不正确",
                    'pattern' => self::MOBILE_REG,
                    'allowEmpty' => true
                )));
            break;

            case 'setStatus':
                $this->add('id', new PresenceOf(array(
                    'message' => 'The idCardPic is required'
                )));
                $this->add('status', new PresenceOf(array(
                    'message' => 'The idCardPic is required'
                )));
            break;

            default :
                $this->add('has no param verify',  new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv',null),
                    //'allowEmpty' => true
                )));
            break;
        }

    }


}
