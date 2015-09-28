<?php
/**
 * 自定义错误状态码
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/28
 * Time: 上午11:16
 */

return new \Phalcon\Config(array(
    'app'   => [
        # common
        'NEED_LOGIN'            => [-10000, '需要登陆才可以访问'],
        'INVALID_PARAMS'        => [10000, '参数错误'],

        # user
        'INVALID_MOBILE'        => [11001, '请输入正确的手机号'],
        'INVALID_CODE'          => [11002, '请输入正确的验证码'],
        'USER_IS_NOT_EXISTS'    => [11003, '服务处于封闭测试期，您的手机号已经记录，业务开放之后会主动跟您联系，多谢支持'],
        'BEYOND_SEND_SMS_COUNT' => [11004, '短信发送次数超限'],
        'RECONFIRM_MOBILE'      => [11005, '请确认手机号码是否正确'],
        'FEEDBACK_ERROR'        => [11006, '内容不能为空且长度不可超过140'],

        # order
        'BOX_NOT_BELONG_YOU'        => [11101, '此箱不属于该司机'],
        'BOX_PRODUCT_CONFIRM_FAIL'  => [11102, '产装完成状态更新失败'],
        'BOX_LUOXIANG_CONFIRM_FAIL' => [11103, '落箱完成状态更新失败'],
    ]
));