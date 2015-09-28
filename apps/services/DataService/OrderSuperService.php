<?php
namespace Services\DataService;

use Library\Helper\PageHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Users;
use OrderProductAddress;
use OrderProductTime;
use OrderAssignDriver;
use AdminOrder;
use AdminGroup;

/**
 *
 *  订单跟踪服务
 *
 */
class OrderSuperService extends Component
{


    public function queryOrderSupers($page_no, $ordersupername)
    {

        empty($page_no) && $page_no = 1;
        $page_size = 10;
        $page_start = ($page_no - 1) * $page_size;
        $adminGroup = AdminGroup::findFirst(array(
            "conditions" => " group_name= ?1",
            "bind" => array(1 => '跟单组')
        ));
        $sql = "";
        $sql .= " LIMIT $page_start,$page_size";
        $data = $this->db->fetchAll($sql);
        Logger::info('queryOrderSupers ' . $adminGroup);
        return $data;
    }


    public function queryOrderOfSuper($adminuserid, $conParas, PageHelper $pageHelper)
    {
        return $pageHelper;
    }


    /** 查询某个跟单员的订单信息
     * @param $admin_supervisorid
     * @param $order_freightagent_mobile
     * @param $order_teammobile
     * @param $order_create_time_start
     * @param $order_create_time_end
     * @param PageHelper $pageHelper
     * @param null $order_status
     */
    public function getOrdersBySupervisor($admin_supervisorid, $order_freightagent_mobile, $order_teammobile,
                                          $order_create_time_start, $order_create_time_end, $tidan_code, $yundan_code,
                                          PageHelper $pageHelper = null, $order_status = null)
    {


        $params = array();
        $sql = "select orf.id as id   from  admin_order ao ,  order_freight orf     where orf.id=ao.order_id  and  ao.admin_id = ?  ";
        $sqlCount = "select count(orf.id)  from  admin_order ao ,  order_freight orf     where orf.id=ao.order_id  and  ao.admin_id = ?  ";

        $params[] = $admin_supervisorid;

        if (isset($order_freightagent_mobile)) {
            $sql .= " and orf.freightagent_user =? ";
            $sqlCount .= "  and orf.freightagent_user =? ";
            $params[] = $order_freightagent_mobile;
        }
        if (isset($order_teammobile)) {
            $sql .= "  and orf.carrier_userid =? ";
            $sqlCount .= " and orf.carrier_userid =? ";
            $params[] = $order_teammobile;
        }

        if (isset($order_create_time_start)) {
            $sql .= "  and orf.submit_time >= ? ";
            $sqlCount .= "  and orf.submit_time >= ? ";
            $params[] = $order_create_time_start;
        }
        if (isset($order_create_time_end)) {
            $sql .= "  and orf.submit_time <= ? ";
            $sqlCount .= "  and orf.submit_time <= ? ";
            $params[] = $order_create_time_end;
        }
        if (isset($order_status)) {
            $sql .= " and orf.order_status =? ";
            $sqlCount .= "  and orf.order_status =? ";
            $params[] = $order_status;
        }
        if (isset($tidan_code)) {
            $sql .= " and orf.tidan_code =? ";
            $sqlCount .= "  and orf.tidan_code =? ";
            $params[] = $tidan_code;
        }
        if (isset($yundan_code)) {
            $sql .= " and orf.yundan_code = ? ";
            $sqlCount .= "  and orf.yundan_code = ? ";
            $params[] = $yundan_code;
        }

        $sql .= " order by orf.submit_time desc ";

        $startRow = $pageHelper->getStartRow();
        $pageSize = $pageHelper->getPageSize();
        $sql .= "  limit $startRow ,$pageSize";
        $orderArr = array();

        $orderidArr = $this->db->fetchAll($sql, 2, $params);

        if (count($orderidArr) > 0) {
            foreach ($orderidArr as $index => $orderid) {
                $orderInfo = $this->OrderFreightListService->getOrderMsg($orderid['id']);
                $orderArr[] = $orderInfo;
            }
        }
        $countRet = $this->db->query($sqlCount, $params)->fetch();
        $counts = $countRet[0];
        $pageHelper->setData($orderArr)->setTotalRows($counts);
        return $pageHelper;

    }


    public function getUserSuperDetail($admin_userid, $startTime, $endTime)
    {
        $userSuperDetailArr = array();
        $params = array();
        $commentParas = array();

        // 跟进的订单状态数
        $sql = "select orf.order_status , sum(1) as statusCount
            from  admin_order ao ,  order_freight orf     where orf.id=ao.order_id  and  ao.admin_id = ? GROUP by orf.order_status ";

        $sqlorderCount = "select count(orf.id)
            from  admin_order ao ,  order_freight orf     where orf.id=ao.order_id  and  ao.admin_id = ?  ";

        $sqlCommentCount = "select count(orf.id)
            from  admin_order ao ,  order_freight orf
             where orf.id=ao.order_id  and  ao.admin_id =  ?  and  orf.order_total_percent >0   ";

        $params[] = $admin_userid;
        $commentParas[] = $admin_userid;

        if (isset($startTime)) {
            $sql .= " and orf.submit_time>= ? ";
            $sqlorderCount .= " and orf.submit_time>= ? ";
            $sqlCommentCount .= " and orf.submit_time>= ? ";
            $params[] = $startTime;
            $commentParas[] = $startTime;
        }
        if (isset($endTime)) {
            $sql .= " and orf.submit_time <= ? ";
            $sqlorderCount .= " and orf.submit_time <= ? ";
            $params[] = $endTime;
            $commentParas[] = $endTime;
        }

        $commentRet = $this->db->query($sqlCommentCount, $commentParas)->fetch();
        $totalRet = $this->db->query($sqlorderCount, $params)->fetch();
        $orderStatusDetail = $this->db->fetchAll($sql, 2, $params);

        $userSuperDetailArr['commentCount'] = $commentRet[0];
        $userSuperDetailArr['totalOrderCount'] = $totalRet[0];
        $userSuperDetailArr['orderStatusCount'] = $orderStatusDetail;
        $userSuperDetailArr['opCount'] = 15; //todo

        return $userSuperDetailArr;

    }

    /**
     * 功能: 轮询分配跟单员
     * @return bool
     */
    private function pollAssign()
    {
        //跟单组id
        $group = \AdminGroup::findFirst(["group_name = '跟单组'", 'columns' => 'id']);
        if (!$group) {
            Logger::warn('assginAuto-pollAssgin 查询不到跟单组id');
            return false;
        }
        $order_group = $group->id;

        //上一次跟单员id
        $status = $this->admin_cfg->ORDER_SUPER->NORMAL;
        $sql = "SELECT ORDER1.admin_id FROM admin_order as ORDER1 WHERE id=(SELECT MAX(ORDER2.id) FROM admin_order as ORDER2 WHERE ORDER2.status=$status)";
        $ret = $this->db->fetchOne($sql, 2);
        if (!$ret) {
            $ret['admin_id'] = 0;
        }

        Logger::info('轮询分配跟单员:admin_id ' . $ret['admin_id']);
        //顺序后移，下一个跟单员id todo status筛选，某人被删除他的权限status置为2
        $admin_id = $ret['admin_id'];
        $sql = "SELECT MIN(user_id) AS admin_id FROM admin_user_group WHERE group_id = $order_group AND user_id > $admin_id AND enable=1";
        $ret = $this->db->fetchOne($sql, 2);

        if (!$ret['admin_id']) {
            $sql = "SELECT MIN(user_id) AS admin_id FROM admin_user_group WHERE group_id = $order_group AND enable=1";
            $ret = $this->db->fetchOne($sql, 2);
        }

        return $ret['admin_id'];
    }

    /**
     * 功能: 系统为订单分配跟单员
     * @param $orderid
     * @return bool
     */
    public function assignAuto($orderid)
    {
        //是否有此订单
        $orderid = intval($orderid);
        $freightOrder = \OrderFreight::findFirst("id = $orderid");
        if (empty($freightOrder)) {
            Logger::warn('assignOrderAdminAction  orderid null: ' . $orderid);
            return false;
        }

        //已经分配的话就返回
        $qorder = AdminOrder::findFirst(" order_id = $orderid");
        if (!empty($qorder)) {
            Logger::warn('assginAuto err_info:has been assgined: ' . $orderid);
            return false;
        }

        //根据分配策略获取跟单员id
        $admin_id = $this->pollAssign();
        if (!$admin_id) {
            Logger::warn('assginAuto 轮询分配跟单员失败');
            return false;
        }

        //创建跟单
        $orderSuper = new AdminOrder();
        $ret = $orderSuper->save(['admin_id' => $admin_id, 'order_id' => $orderid]);

        Logger::info('assignAuto save:' . var_export($orderSuper->getMessages(), true));
        return $ret;
    }


    public function assignOrderSupers($admin_userid, $orderid)
    {

        $ret = array(
            'error_code' => 0,
            'error_msg' => '成功',
            'data' => array(),
        );

        $adminUser = \AdminUsers::findFirst(array("id = ?1", 'bind' => [1 => $admin_userid]));
        if (empty($adminUser)) {
            Logger::warn('assignOrderAdminAction  adminuserid not exist: ' . $admin_userid);
            $ret['error_msg'] = 'adminuserid参数错误';
            $ret['error_code'] = 2;
            return $ret;
        }

        $freightOrder = \OrderFreight::findFirst(array("id = ?1", 'bind' => [1 => $orderid]));
        if (empty($freightOrder)) {
            Logger::warn('assignOrderAdminAction  orderid not exist: ' . $orderid);
            $ret['error_msg'] = 'orderid参数错误';
            $ret['error_code'] = 2;
            return $ret;
        }

        $conditions = " order_id = ?1";
        $parameters = array(1 => $orderid);
        $qorder = AdminOrder::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));
        if (!empty($qorder)) {
            Logger::warn('assignOrderAdminAction  orderid not exist: ' . $orderid);
            $ret['error_msg'] = '订单已经被分配跟单员，请不要重复分配';
            $ret['error_code'] = 3;
            return $ret;
        }

        $orderSuper = new AdminOrder();
        $orderSuper->admin_id = $admin_userid;
        $orderSuper->order_id = $orderid;
        $orderSuper->save();
        return $ret;
    }

    /**
     * 根据orderid 获取跟单员信息
     * @param $orderid
     * @return array
     */
    public function getSupervisorInfoByOrderid($orderid)
    {
        $sql = "SELECT au.id, au.username  ,au.phone_number   from  admin_users au ,  admin_order ao
                WHERE   ao.order_id = ? and  ao.admin_id = au.id  ";
        $bind[] = $orderid;
        $sql .= " limit 1 ";
        $results = $this->db->fetchAll($sql, 2, $bind);

        if (count($results) > 0) {
            $results = $results[0];
        }

        return $results;
    }

    public function changeOrderSupers($admin_userid, $orderid)
    {

        $ret = array(
            'error_code' => 0,
            'error_msg' => '成功',
            'data' => array(),
        );

        $adminUser = \AdminUsers::findFirst(array("id = ?1", 'bind' => [1 => $admin_userid]));
        if (empty($adminUser)) {
            Logger::warn('assignOrderAdminAction  adminuserid not exist: ' . $admin_userid);
            $ret['error_msg'] = 'adminuserid参数错误';
            $ret['error_code'] = 2;
            return $ret;
        }

        $freightOrder = \OrderFreight::findFirst(array("id = ?1", 'bind' => [1 => $orderid]));
        if (empty($freightOrder)) {
            Logger::warn('assignOrderAdminAction  orderid not exist: ' . $orderid);
            $ret['error_msg'] = 'orderid参数错误';
            $ret['error_code'] = 2;
            return $ret;
        }

        $conditions = " order_id = ?1";
        $parameters = array(1 => $orderid);
        $qorder = AdminOrder::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        if (!empty($qorder)) {
            $qorder->admin_id = $admin_userid;
            $qorder->status = $this->admin_cfg->ORDER_SUPER->CHANGE;
            $qorder->update();

        } else {
            $orderSuper = new AdminOrder();
            $orderSuper->admin_id = $admin_userid;
            $orderSuper->order_id = $orderid;
            $orderSuper->save();
        }
        return $ret;
    }


}