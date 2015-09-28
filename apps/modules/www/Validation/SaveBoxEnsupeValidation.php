<?php


namespace Modules\www\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

/*
 *
PresenceOf	检测字段的值是否为非空
Identical	检测字段的值是否和指定的相同
Email	检测值是否为合法的email地址
ExclusionIn	检测值是否不在列举的范围内
InclusionIn	检测值是否在列举的范围内
Regex	        检测值是否匹配正则表达式
StringLength	检测值的字符串长度
Between	        检测值是否位于两个值之间
Confirmation	检测两个值是否相等
Alnum
Alpha
Digit
File
Numericality
Uniqueness
Url
 */
class SaveBoxEnsupeValidation extends Validation
{
    public function initialize()
    {

        /*
        'order_box_id'      => '123',
                    'order_freight_id'  => '123',
                    'box_ensupe'        => '1234',
                    'box_code'          => '12345',
         */
        $this->add('order_box_id', new PresenceOf(array(
            'message' => 'The order_box_id is required'
        )));
        $this->add('order_freight_id', new PresenceOf(array(
            'message' => 'The order_box_id is required'
        )));
        $this->add('box_ensupe', new PresenceOf(array(
            'message' => 'The box_ensupe is required'
        )));
        $this->add('box_code', new PresenceOf(array(
            'message' => 'The box_ensupe is required'
        )));
    }

}