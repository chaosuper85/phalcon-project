<?php namespace Modules\admin\Validation;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\InclusionIn;

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
class BasicStaticValidation extends Validation
{
//    public function initialize()
//    {
//        $this->add('userid', new PresenceOf(array(
//            'message' => 'userid is required'
//        )));
//
//        $this->add('name', new PresenceOf(array(
//            'message' => 'The 你妹 is required'
//        )));
//
//
//    }


    public function __construct($function)
    {

        $reg = array(
            'pwd' => "[0-9a-zA-Z]{2,}"
        );

        switch($function)
        {
            case 'page':
                $this->add('search_type', new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv','uv','all','',null)
                )));



            break;

            case 'user':
                $this->add('search_type', new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv','uv','all','',null)
                )));
            break;

            case 'msg':
                $this->add('search_type', new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv','uv','all','',null)
                )));
            break;

            case 'pic':
                $this->add('search_type', new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv','uv','all','',null)
                )));
            break;

            case 'log':
                $this->add('search_type', new InclusionIn(array(
                    'message' => 'The status must not pv uv all or null',
                    'domain' => array('pv','uv','all','',null),
                    'allowEmpty' => true
                )));
            break;

            case 'queryEventStatistic':
                $this->add('event_type', new PresenceOf(array( 'message' => 'The event_type is required' )));
                $this->add('time_type', new PresenceOf(array( 'message' => 'The time_type is required' )));
                $this->add('start_time', new PresenceOf(array( 'message' => 'The start_time is required' )));
                $this->add('end_time', new PresenceOf(array( 'message' => 'The end_time is required' )));
                break;
        }

    }





}
