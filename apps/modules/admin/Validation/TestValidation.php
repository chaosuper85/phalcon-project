<?php namespace Modules\admin\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;

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
class TestValidation extends Validation
{
    public function initialize()
    {
        $this->add('userid', new PresenceOf(array(
            'message' => 'userid is required'
        )));

        $this->add('name', new PresenceOf(array(
            'message' => 'The 你妹 is required'
        )));


    }
}
