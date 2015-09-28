<?php


use Phalcon\Http\Response;
use Services\Services as Services;
use Library\Log\Logger;
use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/api/msgP2P")
 */
class ApiMsgP2PController extends ApiControllerBase {

    /**
     * @Route("/sendMsg", methods={"GET", "POST"})
     */
    public function sendMsgAction(){
        $user = $this->session->get('login_user');
        if(empty($user)){
            Logger::info("用户不存在！");
            return false;
        }
        $sender_id = $user->id;
        $rec_id = $this->request->getQuery('rec_id');
        $msg_type = $this->request->getQuery('msg_type');//邀请好友 还是 询价
        $msg_title = $this->request->getQuery('msg_title');
        $msg_content = $this->request->getQuery('msg_content');
        $deal_url = $this->request->getQuery('deal_url');
        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'date' => array()
        );

        $msg = $this->MsgP2PSendValidation->validate(array('rec_id'=>$rec_id, 'msg_type' => $msg_type, 'msg_title' => $msg_title, 'msg_content' => $msg_content, 'deal_url' => $deal_url));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $ret = $this->MsgP2PService->create($sender_id, $rec_id, $msg_type, $msg_title, $msg_content, $deal_url);
                if (!$ret) {
                    $result['error_code'] = '2001';
                    $result['error_msg'] = '创建站内信失败！';
                }
                Logger::info("send msgP2P result: " . var_export($result, true));
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100005',
                    'error_msg' => '站内信发送异常'
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return ;
    }


    /**
     * @Route("/getCountMsg", methods={"GET", "POST"})
     */
    public function getCountMsgAction(){
        $user = $this->session->get('login_user');
        if(empty($user)){
            Logger::info("用户不存在！");
            return false;
        }
        $rec_id = $user->id;
        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'msg_count' => 0
        );
        try {
            $restlt['msg_count'] = $this->MsgP2PService->getCountWithinTime($rec_id);
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code' => '100005',
                'error_msg' => '返回未读站内信异常',
            );
        }
        $this->response->setJsonContent($result)->send();
    }


    /**
     * @Route("/getAllMsg", methods={"GET", "POST"})
     */
    public function getAllMsgAction(){
        $user = $this->session->get('login_user');
        if(empty($user)){
            Logger::info('用户不存在!');
            return false;
        }
        $rec_id = $user->id;
        $page = $this->request->getQuery('page');
        try {
            $onePageMsg = $this->MsgP2PService->getOnePageMsg($rec_id, null, $page);
            $result = array(
                'error_code' => 0,
                'error_msg' => '',
                'page_count' => 0,
                'data' => array(),
            );
            if (empty($onePageMsg))
                $result['error_code'] = 1;
            else {
                $result['page_count'] = $this->MsgP2PService->getPageCount($rec_id);
                $result['data'] = $onePageMsg;
            }
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code' => '100005',
                'error_msg' => '站内信分页展示异常',
                'page_count' => 0,
                'data' => array()
            );
        }
        $this->response->setJsonContent($result)->send();
        return;
    }

    /**
     * @Route("/readMsg", methods={"GET", "POST"})
     */
    public function readMsgAction(){
        $id = $this->request->getQuery('id');

        $msg = $this->MsgP2PReadValidation->validate(array('id' => $id));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $result = array(
                    'error_code' => '0',
                    'error_msg' => '',
                    'data' => array(),
                );
                $reading_msg = $this->MsgP2PService->readMsg($id);
                if (empty($reading_msg)) {
                    Logger::info('将要读站内信不存在！');
                    return false;
                }
                $res = array(
                    'sender_id' => $reading_msg->sender_id,
                    'msg_type' => $reading_msg->msg_type,
                    'msg_title' => $reading_msg->msg_title,
                    'msg_content' => $reading_msg->msg_content,
                    'send_time' => $reading_msg->create_time,
                    'exp_time' => $reading_msg->exp_time,
                    'deal_url' => $reading_msg->deal_url
                );
                $result['data'] = $res;
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100005',
                    'error_msg' => '阅读站内信异常',
                    'data' => array(),
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return;
    }

    /**
     * @Route("/delMsg", methods={"GET", "POST"})
     */
    public function delMsgAction(){
        $id = $this->request->getQuery('id');

        $msg = $this->MsgP2PDelValidation->validate(array('id' => $id));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $this->MsgP2PService->delMsg($id);
                $result = array(
                    'error_code' => 0,
                    'error_msg' => ''
                );
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100005',
                    'error_msg' => '删除站内信异常',
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return;
    }
}

