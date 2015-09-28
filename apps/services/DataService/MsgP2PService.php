<?php   namespace Services\DataService;

use Library\Log\Logger;
use MsgP2p;
use Phalcon\Mvc\User\Component;


/**
 *  站内信
 */
class MsgP2PService extends Component
{

    public function  create($sender_id, $rec_id, $msg_type, $msg_title, $msg_content, $deal_url)
    {
        $msgP2p = new MsgP2p();
        $register = $this->di->get('constant');
        $msgP2p->sender_id = $sender_id;
        $msgP2p->rec_id = $rec_id;
        $msgP2p->msg_type = $msg_type;
        $msgP2p->msg_status = $register->MSG_P2P_STATUS->INIT;
        $msgP2p->msg_title = $msg_title;
        $msgP2p->msg_content = $msg_content;
        $msgP2p->create_time = date('Y-m-d h:i:s', time());
        $msgP2p->exp_time = date('Y-m-d h:i:s', strtotime('+'.$register->MSG_P2P_SAVE_TIME.' day'));
        $msgP2p->deal_url = $deal_url;
        $res = $msgP2p->save();
        Logger::info(" create MsgP2p :" . var_export($msgP2p->getMessages(),true));
        return $res ? $msgP2p : false ;
    }

    /**
     *  获取收到的 站内信
     */
    public function getReceivedMsg( $userId, $status, $type = null, $startTime =null ,$endTime = null)
    {
        $this->isOverAllMsg($userId);
        $conditions = " rec_id =?1 and msg_status = ?2";
        $params =array(
            1 => $userId,
            2 => $status
        );
        if(!empty($type)){
            $conditions .= "and msg_type = ?3";
            $params[3] = $type;
        }
        if( !empty($startTime) ){
           $conditions.=" and create_time >= ?4" ;
            $params[4]  = $startTime;
        }
        if( !empty($endTime) ){
            $conditions.=" and create_time < ?5" ;
            $params[5]  = $endTime;
        }

        return MsgP2p::find(array(
            "conditions" => $conditions,
            "bind"       => $params
        ))->toArray();
    }
    /*
     * 获取唯一的一条站内信
     */
    public function getMsgById($id){
        $conditions = "id = ?1";
        $params = array(
            1 => $id
        );
        return MsgP2p::find(array(
            "conditions" => $conditions,
            "bind" => $params
        ))->getFirst();
    }
    /*
     * 站内信阅读
     */
    public function readMsg($id){
        $read_msg = $this->getMsgById($id);
        if(!empty($read_msg) && $read_msg->msg_status == 1) {
            $read_msg->msg_status = 2;
            $read_msg->read_time = date('Y-m-d h:m:s', time());
            $res = $read_msg->update();
            Logger::info("read Msg: ".var_export($read_msg->getMessages(), true));
        }
        else
            Logger::info("Msg alerady read");
        return $read_msg;
    }
    /*
     * 站内信是否过期
     * return true--没有过期, false--过期
     */
    public function overExpTime($id){
        $cur_msg = $this->getMsgById($id);
        $cur_time = date('Y-m-d h:i:s', time());
        if(!empty($cur_msg) && strtotime($cur_msg->exp_time) < strtotime($cur_time)){
            //过期
            $cur_msg->msg_status = 3;
            $res = $cur_msg->update();
            Logger::info("over Exp_time:".var_export($cur_msg->getMessages(), true));
            return false;
        }
        else{
            Logger::info("站内信没有过期");
            return true;
        }
    }
    /*
     * 返回 当前时间 之前所有未读站内信数量
     */
    public function getCountBeforeCur($rec_id){
        $cur_time = time();
        $conditions = "rec_id = ?1 and msg_status = ?2";
        $params = array(
            1 => $rec_id,
            2 => 1
        );
        $values = MsgP2p::find(array(
            'conditions' => $conditions,
            'bind' => $params
        ))->toArray();
        $count = 0;
        foreach($values as $value)
            $count++;
        return $count;
    }

    public function getCount($rec_id){
        $cur_time = date('Y-m-d h:m:s', time());
        $conditions = "rec_id = ?1 and create_time <= ?2 and msg_status in(?3, ?4)";
        $params = array(
            1 => $rec_id,
            2 => $cur_time,
            3 => 1,
            4 => 2
        );
        return MsgP2p::count(array(
            'conditions' => $conditions,
            'bind' => $params
        ));
    }
    /**
     * 通过 user_id返回所有Msg
     */
    public function getMsgByUserId($rec_id = null, $sender_id = null){
        if($sender_id == null){
            $conditions = "rec_id = ?1";
            $params = array(
                1 => $rec_id
            );
        }else{
            $conditions = "sender_id = ?1";
            $params = array(
                1 => $sender_id
            );
        }
        return MsgP2p::find(array(
            'conditions' => $conditions,
            'bind' => $params
        ))->toArray();
    }
    /**
     * 校验所有站内信是否过期
     */
    public function isOverAllMsg($rec_id = null, $sender_id = null){
        $allMsg = $this->getMsgByUserId($rec_id, $sender_id);
        $mark = 0;
        foreach($allMsg as $oneMsg) {
            if ($this->overExpTime($oneMsg['id']))
                $mark++;
        }
        if($mark==0)
            return false;
        return true;
    }
    /**
     * 返回接受者一页信息
     */
    public function getOnePageMsg($rec_id = null, $sender_id = null, $page = 1){
        if(!$this->isOverAllMsg($rec_id, $sender_id))
            return false;
        $register = $this->di->get('constant')->MSG_P2P_SHOWNUM_ONEPAGE;
        $offset = ($page - 1) * $register;
        if($rec_id != null) {
            if($sender_id != null)
                $sql = "select * from msg_p2p where rec_id = '$rec_id' and sender_id = '$sender_id' and msg_status in(1, 2)";
            else
                $sql = "select * from msg_p2p where rec_id = '$rec_id' and msg_status in(1, 2)";
        }
        else{
            if($sender_id != null)
                $sql = "select * from msg_p2p where sender_id = '$sender_id' and msg_status in(1, 2)";
            else
                return false;
        }
        $sql .= " order by create_time desc";
        $sql .= " limit $register offset $offset";
        return  $this->di->get('db')->query($sql)->fetchAll();
    }
    /**
     * 删除站内信 改变站内信状态
     */
    public function delMsg($id){
        $del_msg = $this->getMsgById($id);
        if(empty($del_msg)) {
            Logger::info("此站内信不存在");
            return false;
        }
        $del_msg->msg_status = 4;
        $res = $del_msg->save();
        Logger::info("del Msg: ".var_export($del_msg->getMessages(), true));
        return true;
    }
    /**
     *  返回总页数
     */
    public function getPageCount($rec_id){
        $count = $this->getCount($rec_id);
        $register = $this->di->get('constant')->MSG_P2P_SHOWNUM_ONEPAGE;
        if($count == 0)
            Logger::info('此人没有站内信');
        else
            $count = (int)($count / $register) + 1;
        return $count;
    }
}