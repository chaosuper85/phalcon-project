<?php namespace Services\Service;

use Aw\Nusoap\NusoapClient as NusoapClient;
use Aw\Nusoap\Soapval as Soapval;
use Library\Log\Logger;

use \Phalcon\DiInterface;

use Pheanstalk\Pheanstalk as Pheanstalk;
use Phalcon\Mvc\User\Component;

class SmsService extends Component
{


    /**
     * 网关地址
     */
    private $url;

    /**
     * 序列号,请通过亿美销售人员获取
     */
    private $serialNumber;

    /**
     * 密码,请通过亿美销售人员获取
     */
    private $password;

    /**
     * 登录后所持有的SESSION KEY，即可通过login方法时创建
     */
    private $sessionKey;

    /**
     * webservice客户端
     */
    private $soap;

    /**
     * 默认命名空间
     */
    private $namespace = 'http://sdkhttp.eucp.b2m.cn/';

    /**
     * 往外发送的内容的编码,默认为 GBK
     */
    private $outgoingEncoding = "GBK";

    /**
     * 往外发送的内容的编码,默认为 GBK
     */
    private $incomingEncoding = 'GBK';



    /**
     * @param string $url 网关地址
     * @param string $serialNumber 序列号,请通过亿美销售人员获取
     * @param string $password 密码,请通过亿美销售人员获取
     * @param string $sessionKey 登录后所持有的SESSION KEY，即可通过login方法时创建
     *
     * @param string $proxyhost 可选，代理服务器地址，默认为 false ,则不使用代理服务器
     * @param string $proxyport 可选，代理服务器端口，默认为 false
     * @param string $proxyusername 可选，代理服务器用户名，默认为 false
     * @param string $proxypassword 可选，代理服务器密码，默认为 false
     * @param string $timeout 连接超时时间，默认0，为不超时
     * @param string $response_timeout 信息返回超时时间，默认30
     *
     *
     */
    function initClient($config)
    {
        $gwUrl = $config->gwUrl;
        $serialNumber = $config->serialNumber;
        $password = $config->password;
        $sessionKey = $config->sessionKey;
        $proxyhost = $config->proxyhost;
        $proxyport = $config->proxyport;
        $proxyusername = $config->proxyusername;
        $proxypassword = $config->proxypassword;
        $connectTimeOut = $config->connectTimeOut;
        $readTimeOut = $config->readTimeOut;


        $this->url = $gwUrl;
        $this->serialNumber = $serialNumber;
        $this->password = $password;
        if ($sessionKey != '') {
            $this->sessionKey = $sessionKey;
        }

        /**
         * 初始化 webservice 客户端
         */
        $this->soap = new NusoapClient($gwUrl, false, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $this->soap->soap_defencoding = $this->outgoingEncoding;
        $this->soap->decode_utf8 = false;
    }

    /**
     * 设置发送内容 的字符编码
     * @param string $outgoingEncoding 发送内容字符集编码
     */
    function setOutgoingEncoding($outgoingEncoding)
    {
        $this->outgoingEncoding = $outgoingEncoding;
        $this->soap->soap_defencoding = $this->outgoingEncoding;
    }


    /**
     * 设置接收内容 的字符编码
     * @param string $incomingEncoding 接收内容字符集编码
     */
    function setIncomingEncoding($incomingEncoding)
    {
        $this->incomingEncoding = $incomingEncoding;
        $this->soap->xml_encoding = $this->incomingEncoding;
    }


    function setNameSpace($ns)
    {
        $this->namespace = $ns;
    }

    function getSessionKey()
    {
        return $this->sessionKey;
    }

    function getError()
    {
        return $this->soap->getError();
    }


    /**
     *
     * 指定一个 session key 并 进行登录操作
     *
     * @param string $sessionKey 指定一个session key
     * @return int 操作结果状态码
     *
     * 代码如:
     *
     * $sessionKey = $client->generateKey(); //产生随机6位数 session key
     *
     * if ($client->login($sessionKey)==0)
     * {
     *     //登录成功，并且做保存 $sessionKey 的操作，用于以后相关操作的使用
     * }else{
     *     //登录失败处理
     * }
     *
     *
     */
    function login($sessionKey = '')
    {
        if ($sessionKey != '') {
            $this->sessionKey = $sessionKey;
        }

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey, 'arg2' => $this->password);

        $result = $this->soap->call("registEx", $params, $this->namespace);
        echo "result: " . var_export($result, true);

        return $result;
    }


    /**
     * 注销操作  (注:此方法必须为已登录状态下方可操作)
     *
     * @return int 操作结果状态码
     *
     * 之前保存的sessionKey将被作废
     * 如需要，可重新login
     */
    function logout()
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
        print_r($params);
        $result = $this->soap->call("logout", $params,
            $this->namespace
        );

        return $result;
    }

    /**
     * 获取版本信息
     * @return string 版本信息
     */
    function getVersion()
    {
        $result = $this->soap->call("getVersion",
            array(),
            $this->namespace
        );
        return $result;
    }


    function sendSMSWithCacheCode($mobile, $content, $code, $mins = 10)
    {

        $ret_code = $this->sendSMS(array($mobile), $content);
        if ($ret_code == '0') {

            $this->cache->set('smscode_' . $mobile, $code, $mins * 60);
            Logger::info('send sms to ' . $mobile . ' success ');
            return true;
        } else {

            Logger::info('send sms to ' . $mobile . ' fail ');
            return false;
        }
    }

    function sendMs($mobile, $content)
    {
        if( $this->constant->application_mode != 'prod' ){ // todo wanghui
            return true ;
        }

        $text = iconv('UTF-8', 'GBK', $content);
        $ret_code = $this->sendSMS(array($mobile), $text);
        if ($ret_code == '0') {
            Logger::info('send sms to ' . $mobile . ' success ');
            return true;
        } else {
            Logger::info('send sms to ' . $mobile . ' fail ');
            return false;
        }
    }


    /**
     *  异步短信发送  (注:此方法必须为已登录状态下方可操作)
     *
     * @param array $mobiles 手机号, 如 array('159xxxxxxxx'),如果需要多个手机号群发,如 array('159xxxxxxxx','159xxxxxxx2')
     * @param string $content 短信内容
     * @param string $sendTime 定时发送时间，格式为 yyyymmddHHiiss, 即为 年年年年月月日日时时分分秒秒,例如:20090504111010 代表2009年5月4日 11时10分10秒
     *                                如果不需要定时发送，请为'' (默认)
     *
     * @param string $addSerial 扩展号, 默认为 ''
     * @param string $charset 内容字符集, 默认GBK
     * @param int $priority 优先级, 默认5
     * @param int $priority 信息序列ID(唯一的正整数)
     * @return int 操作结果状态码
     */
    function asyncSendSMS($mobiles = array(), $content, $sendTime = '', $addSerial = '', $charset = 'GBK', $priority = 5, $smsId = 8888)
    {
        if( $this->constant->application_mode != 'prod' ){ // todo wanghui
            return true ;
        }

        $config = $this->queue;

        $beanstalkdIp = $config->beanstalkd->ip;
        $beanstalkdTube = $config->beanstalkd->smstube;


        $pheanstalk = new Pheanstalk($beanstalkdIp);

        $content = iconv('UTF-8', 'GBK', $content);

        $data = array(
            'mobiles' => $mobiles,
            'content' => $content,
        );

        $ret = $pheanstalk->useTube($beanstalkdTube)->put(json_encode($data));

        return $ret;
    }


    /**
     * 短信发送  (注:此方法必须为已登录状态下方可操作)
     *
     * @param array $mobiles 手机号, 如 array('159xxxxxxxx'),如果需要多个手机号群发,如 array('159xxxxxxxx','159xxxxxxx2')
     * @param string $content 短信内容
     * @param string $sendTime 定时发送时间，格式为 yyyymmddHHiiss, 即为 年年年年月月日日时时分分秒秒,例如:20090504111010 代表2009年5月4日 11时10分10秒
     *                                如果不需要定时发送，请为'' (默认)
     *
     * @param string $addSerial 扩展号, 默认为 ''
     * @param string $charset 内容字符集, 默认GBK
     * @param int $priority 优先级, 默认5
     * @param int $priority 信息序列ID(唯一的正整数)
     * @return int 操作结果状态码
     */
    function sendSMS($mobiles = array(), $content, $sendTime = '', $addSerial = '', $charset = 'GBK', $priority = 5, $smsId = 8888)
    {

        $config = $this->common_config;

        $this->initClient($config->sms_config->single_sdk);
        $this->setOutgoingEncoding("GBK");

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey, 'arg2' => $sendTime,
            'arg4' => $content, 'arg5' => $addSerial, 'arg6' => $charset, 'arg7' => $priority, 'arg8' => $smsId
        );

        /**
         * 多个号码发送的xml内容格式是
         * <arg3>159xxxxxxxx</arg3>
         * <arg3>159xxxxxxx2</arg3>
         * ....
         * 所以需要下面的单独处理
         *
         */
        foreach ($mobiles as $mobile) {
            array_push($params, new Soapval("arg3", false, $mobile));
        }
        $result = $this->soap->call("sendSMS", $params, $this->namespace);
        return $result;

    }


    /**
     * 发送语音验证码  (注:此方法必须为已登录状态下方可操作)
     *
     * @param array $mobiles 手机号, 如 array('159xxxxxxxx'),如果需要多个手机号群发,如 array('159xxxxxxxx','159xxxxxxx2')
     * @param string $content 语音验证码内容，最多不要超过6个字符，最少不要小于4个字符;字符必须为0至9的全英文半角数字字符
     * @param string $sendTime 定时发送时间，格式为 yyyymmddHHiiss, 即为 年年年年月月日日时时分分秒秒,例如:20090504111010 代表2009年5月4日 11时10分10秒
     *                                如果不需要定时发送，请为'' (默认)
     *
     * @param string $addSerial 扩展号, 默认为 ''，在此处没有实际意义可填写为''
     * @param string $charset 内容字符集, 默认GBK
     * @param int $priority 优先级, 默认5
     * @param int $priority 语音验证码序列ID(唯一的正整数)
     * @return int 操作结果状态码
     */
    function sendVoice($mobiles = array(), $content, $sendTime = '', $addSerial = '', $charset = 'GBK', $priority = 5, $smsId = 8888)
    {

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey, 'arg2' => $sendTime,
            'arg4' => $content, 'arg5' => $addSerial, 'arg6' => $charset, 'arg7' => $priority, 'arg8' => $smsId
        );

        /**
         * 单个号码发送的xml内容格式是
         * <arg3>159xxxxxxxx</arg3>
         * 所以需要下面的单独处理
         * 注：实际应用中只用到了单号码语音验证码,即采用单一手机号码发送
         */
        foreach ($mobiles as $mobile) {
            array_push($params, new Soapval("arg3", false, $mobile));
        }
        $result = $this->soap->call("sendVoice", $params, $this->namespace);
        return $result;

    }

    /**
     * 余额查询  (注:此方法必须为已登录状态下方可操作)
     * @return double 余额
     */
    function getBalance()
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
        $result = $this->soap->call("getBalance", $params, $this->namespace);
        return $result;

    }

    /**
     * 取消短信转发  (注:此方法必须为已登录状态下方可操作)
     * @return int 操作结果状态码
     */
    function cancelMOForward()
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
        $result = $this->soap->call("cancelMOForward", $params, $this->namespace);
        return $result;
    }

    /**
     * 短信充值  (注:此方法必须为已登录状态下方可操作)
     * @param string $cardId [充值卡卡号]
     * @param string $cardPass [密码]
     * @return int 操作结果状态码
     *
     * 请通过亿美销售人员获取 [充值卡卡号]长度为20内 [密码]长度为6
     */
    function chargeUp($cardId, $cardPass)
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey, 'arg2' => $cardId, 'arg3' => $cardPass);
        $result = $this->soap->call("chargeUp", $params, $this->namespace);
        return $result;
    }


    /**
     * 查询单条费用  (注:此方法必须为已登录状态下方可操作)
     * @return double 单条费用
     */
    function getEachFee()
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
        $result = $this->soap->call("getEachFee", $params, $this->namespace);
        return $result;
    }


    /**
     * 得到上行短信  (注:此方法必须为已登录状态下方可操作)
     *
     * @return array 上行短信列表, 每个元素是Mo对象, Mo对象内容参考最下面
     *
     *
     * 如:
     *
     * $moResult = $client->getMO();
     * echo "返回数量:".count($moResult);
     * foreach($moResult as $mo)
     * {
     *      //$mo 是位于 Client.php 里的 Mo 对象
     *      echo "发送者附加码:".$mo->getAddSerial();
     *      echo "接收者附加码:".$mo->getAddSerialRev();
     *      echo "通道号:".$mo->getChannelnumber();
     *      echo "手机号:".$mo->getMobileNumber();
     *      echo "发送时间:".$mo->getSentTime();
     *      echo "短信内容:".$mo->getSmsContent();
     * }
     *
     *
     */

    /**
     * 得到上行短信状态报告  (注:此方法必须为已登录状态下方可操作)
     *
     * @return array 状态报告列表, 每个元素是StatusReport对象, StatusReport对象内容参考最下面
     *
     *
     * 如:
     *
     * $reportResult = $client->getReport();
     * echo "返回数量:".count($reportResult);
     * foreach($reportResult as $report)
     * {
     * //获取状态报告的信息
     * }
     *
     *
     *
     *
     * Array
     * (
     * [0] => Array
     * (
     * [addSerial] =>
     * [addSerialRev] =>
     * [channelnumber] => 10690037cm
     * [mobileNumber] => 18210286771
     * [sentTime] => 20150730232439
     * [smsContent] => 你好
     * )
     *
     * [1] => Array
     * (
     * [addSerial] =>
     * [addSerialRev] =>
     * [channelnumber] => 10690037cm
     * [mobileNumber] => 18210286771
     * [sentTime] => 20150730232432
     * [smsContent] => 12
     * )
     *
     * )
     *
     *
     */
    function getMO()
    {

        $config = $this->common_config;

        $this->initClient($config->sms_config->single_sdk);
        $this->setIncomingEncoding("utf-8");

        $ret = array();
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
//       print_r($params);
        $result = $this->soap->call("getMO", $params, $this->namespace);
//      print_r($this->soap->response);
//      print_r($result);

        if (is_array($result) && count($result) > 0) {
            if (is_array($result[0])) {
                foreach ($result as $moArray)
                    $ret[] = new Mo($moArray);
            } else {
                $ret[] = new Mo($result);
            }
        }
        return $ret;
    }

    /**
     * 得到状态报告  (注:此方法必须为已登录状态下方可操作)
     * @return array 状态报告列表, 一次最多取5个
     */
    function getReport()
    {
        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);
        $result = $this->soap->call("getReport", $params, $this->namespace);
        return $result;
    }


    /**
     * 企业注册  [邮政编码]长度为6 其它参数长度为20以内
     *
     * @param string $eName 企业名称
     * @param string $linkMan 联系人姓名
     * @param string $phoneNum 联系电话
     * @param string $mobile 联系手机号码
     * @param string $email 联系电子邮件
     * @param string $fax 传真号码
     * @param string $address 联系地址
     * @param string $postcode 邮政编码
     *
     * @return int 操作结果状态码
     *
     */
    function registDetailInfo($eName, $linkMan, $phoneNum, $mobile, $email, $fax, $address, $postcode)
    {

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey,
            'arg2' => $eName, 'arg3' => $linkMan, 'arg4' => $phoneNum,
            'arg5' => $mobile, 'arg6' => $email, 'arg7' => $fax, 'arg8' => $address, 'arg9' => $postcode
        );

        $result = $this->soap->call("registDetailInfo", $params, $this->namespace);
        return $result;

    }


    /**
     * 修改密码  (注:此方法必须为已登录状态下方可操作)
     * @param string $newPassword 新密码
     * @return int 操作结果状态码
     */
    function updatePassword($newPassword)
    {

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey,
            'arg2' => $this->password, 'arg3' => $newPassword
        );

        $result = $this->soap->call("serialPwdUpd", $params, $this->namespace);
        return $result;

    }

    /**
     *
     * 短信转发
     * @param string $forwardMobile 转发的手机号码
     * @return int 操作结果状态码
     *
     */
    function setMOForward($forwardMobile)
    {

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey,
            'arg2' => $forwardMobile
        );

        $result = $this->soap->call("setMOForward", $params, $this->namespace);
        return $result;
    }

    /**
     * 短信转发扩展
     * @param array $forwardMobiles 转发的手机号码列表, 如 array('159xxxxxxxx','159xxxxxxxx');
     * @return int 操作结果状态码
     */
    function setMOForwardEx($forwardMobiles = array())
    {

        $params = array('arg0' => $this->serialNumber, 'arg1' => $this->sessionKey);

        /**
         * 多个号码发送的xml内容格式是
         * <arg2>159xxxxxxxx</arg2>
         * <arg2>159xxxxxxx2</arg2>
         * ....
         * 所以需要下面的单独处理
         *
         */
        foreach ($forwardMobiles as $mobile) {
            array_push($params, new Soapval("arg2", false, $mobile));
        }

        $result = $this->soap->call("setMOForwardEx", $params, $this->namespace);
        return $result;


    }


    /**
     * 生成6位随机数
     */
    function generateKey()
    {
        return rand(100000, 999999);
    }
}


class Mo
{

    /**
     * 发送者附加码
     */
    var $addSerial;

    /**
     * 接收者附加码
     */
    var $addSerialRev;

    /**
     * 通道号
     */
    var $channelnumber;

    /**
     * 手机号
     */
    var $mobileNumber;

    /**
     * 发送时间
     */
    var $sentTime;

    /**
     * 短信内容
     */
    var $smsContent;

    function Mo(&$ret = array())
    {
        $this->addSerial = $ret[addSerial];
        $this->addSerialRev = $ret[addSerialRev];
        $this->channelnumber = $ret[channelnumber];
        $this->mobileNumber = $ret[mobileNumber];
        $this->sentTime = $ret[sentTime];
        $this->smsContent = $ret[smsContent];

    }

    function getAddSerial()
    {
        return $this->addSerial;
    }

    function getAddSerialRev()
    {
        return $this->addSerialRev;
    }

    function getChannelnumber()
    {
        return $this->channelnumber;
    }

    function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    function getSentTime()
    {
        return $this->sentTime;
    }

    function getSmsContent()
    {
        return $this->smsContent;
    }


}
