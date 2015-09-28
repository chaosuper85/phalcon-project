<?php


namespace Services\Service;


use Library\Log\Logger;
use Phalcon\Mvc\User\Component;


use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;



/*
 *  setOptions(array(param))
 * param::
    sendno	int	可选	推送序号	纯粹用来作为 API 调用标识，API 返回时被原样返回，以方便 API 调用方匹配请求与返回。
    time_to_live	int	可选	离线消息保留时长(秒)默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到。
    override_msg_id	long 可选 消息ID	如果当前的推送要覆盖之前的一条推送，这里填写前一条推送的 msg_id 就会产生覆盖效果，即：1）该 msg_id 离线收到的消息是覆盖后的内容；2）即使该 msg_id Android 端用户已经收到，如果通知栏还未清除，则新的消息内容会覆盖之前这条通知；覆盖功能起作用的时限是：1 天。如果在覆盖指定时限内该 msg_id 不存在，则返回 1003 错误，提示不是一次有效的消息覆盖操作，当前的消息不会被推送。
    apns_production	boolean	可选	APNs是否生产环境	True 表示推送生产环境，False 表示要推送开发环境；如果不指定则为推送生产环境。JPush 官方 API LIbrary (SDK) 默认设置为推送 “开发环境”。
    big_push_duration	int	可选	定速推送时长(分钟)	又名缓慢推送，把原本尽可能快的推送速度，降低下来，给定的n分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送。
*/





class JpushService extends Component
{

    # alias标示前缀
    const ALIAS_PRREFIX = 'debug_';

    private  $app_key;
    private  $master_secret;
    private  $sender;
    private  $ret;

    public function __construct()
    {

        $this->app_key = $this->common_config->jpush->app_key;
        $this->master_secret = $this->common_config->jpush->master_secret;

        $client = new JPushClient($this->app_key,  $this->master_secret);
        $this->sender = $client->push();

        $this->ret = array(
            'error_code' => 0,
            'error_msg' => '推送成功',
            'send_id' => 0,
            'data' => array(),
        );
    }

    /**
     * 功能: 通过手机号推送通知
     * @param $mobiles mix
     * @param $notice mix
     * @return array
     */
    public function toMobile($mobiles, $notice)
    {
        if( !is_array($mobiles))
            $mobiles = array($mobiles);

        if( !is_array($notice)) {
            $notice = array(
                'msg'=>$notice,
                'op'=>false
            );
        }

        //todo mobile to register_id
        $reg_id = array();

        //todo 发送方式
        $notice['op'] && $this->sender->setOptions($notice['op']);

        $dest = M\registration_id($reg_id);
        return $this->push($dest, $notice['msg']);
    }


    /**
     * to alias
     *
     */

    public function toAlias($template_type, $params, $action_type = 'app', $platform = 'android') {
        $data = array();
        $p = json_decode($params, true);

        # check driver_id
        if (empty($p['driver_id'])) return false;
        $alias_key = array(self::ALIAS_PRREFIX . $p['driver_id']);

        # check template_type
        $template = $this->order_config->push_template[$template_type];
        if (empty($template)) return false;

        # check assign_id when template_type is TO_GET_TASK and BOX_STATUS_CHANGED
        if (!empty($p['order_id']) && !empty($p['box_id'])) {
            $assign_info = \OrderAssignDriver::findFirst(
                array(
                    'conditions' => 'order_freight_id = ?1 AND order_freight_boxid = ?2',
                    'bind' => array(1 => $p['order_id'], 2 => $p['box_id'])
                )
            );
            $p['assign_id'] = $assign_info->id ? $assign_info->id : 0;
        }

        if (($template_type == 'TO_GET_TASK' || $template_type == 'BOX_STATUS_CHANGED' || $template_type == 'BOX_INFO_CHANGED') && empty($p['assign_id'])) {
            return false;
        }

        #check weburl when template_type is TO_WEB
        if ($template_type == 'TO_WEB' && empty($p['weburl'])) {
            return false;
        }

        # push
        $data = array(
            'platform' => $platform,
            'alert' => $template,
            'title' => '',
            'builder_id' => 0,
            'extras' => array(
                'action_type'   => $action_type,
                'data' => array(
                    'towhere' => $this->order_config->push_to_where[$template_type],
                    'params' => $p
                )
            )
        );
        $dest = M\alias($alias_key);
        $res = $this->push($dest, $data);

        # activity log
        $this->di->get('ActivityLogService')->insertActionLog(
            $this->constant->ACTION_TYPE->PUSH_DATA,
            $this->request->getClientAddress(),
            'jpush',
            $this->constant->ACTION_REAM_TYPE->SYSTEM_AUTO,
            $p['driver_id'],
            0,
            json_encode($data),
            "",
            $this->constant->PLATFORM_TYPE->ANDROID
        );
        # end

        # log
        Logger::info(
            $this->constant->LOG_SEPORATER . "PUSH: driver_id " . $p['driver_id'] . ", template_type " . $template_type .
            $this->constant->LOG_SEPORATER . var_export($res, true)
        );

        return $res;
    }


    /**
     * 功能: 通过标签推送
     * @param $tag mix
     * @param $notice mix
     * @return array
     */
    public function toGroup($tag, $notice)
    {
        if( !is_array($tag))
            $tag = array($tag);

        if( !is_array($notice)) {
            $notice = array(
                'msg'=>$notice,
                'op'=>false
            );
        }

        //todo 发送方式
        $notice['op'] && $this->sender->setOptions($notice['op']);

        $dest = M\tag($tag);
        return $this->push($dest, $notice['msg']);
    }



    private function push(&$dest, &$msg)
    {
        $ret = &$this->ret;

        //send
        try {
            $result = $this->sender
                        ->setPlatform(M\all)
                        ->setAudience($dest)
                        ->setNotification(M\notification( $msg))
                        ->send();
            $result = json_decode($result->json);

            if( isset($result->error)) {
                $ret['error_code'] = $result->error->code;
                $ret['error_msg']  = $result->error->msg;
            }else {
                $ret['send_id'] = $result->msg_id;
            }
        }
        catch (APIRequestException $e) {
            $ret['error_code'] = $e->code;
            $ret['error_msg']  = $e->message;
        }
        catch (APIConnectionException $e) {
            $ret['error_code'] = $e->getCode();
            $ret['error_msg']  = $e->getMessage();
        }

        //todo log

        return $ret;
    }

}