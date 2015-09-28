<?php
namespace Services\DataService;


use Library\Helper\PageHelper;
use Library\Helper\QueryHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Users;
use OrderProductAddress;
use OrderProductTime;
use OrderAssignDriver;

/**
 *  订单服务
 */
class OrderService extends Component
{


    public function ordersForAdmin($order_id, $enterprisename, $supervisor_name, $order_freightagent_mobile, $order_teammobile,
                                   $order_create_time_start, $order_create_time_end, $tidan_code , $yundan_code,
                                   PageHelper $pageHelper = null, $order_status = null)
    {
        $params = array();

        $startRow = $pageHelper->getStartRow();
        $pageSize = $pageHelper->getPageSize();

        if (isset($order_id)) {

            $sql = "select orf.id  as id  from  order_freight  orf   where
                       orf.id =  ? ";
            $sqlCount = "select count(orf.id)  as id  from  order_freight  orf   where
                       orf.id =  ? ";
            $params[] = $order_id;
        } elseif (isset($enterprisename)) {

            $sql = "select orf.id  as id  from  order_freight  orf , users usr  where
                     usr.unverify_enterprisename  like CONCAT('%','$enterprisename','%')   and  ( orf.carrier_userid = usr.id  or  orf.freightagent_user = usr.id  )    ";

            $sqlCount = "select  count(orf.id)  from  order_freight  orf , users usr  where
                      usr.unverify_enterprisename  like CONCAT('%','$enterprisename','%') and  ( orf.carrier_userid = usr.id  or  orf.freightagent_user = usr.id  )    ";
//            $params[] = $enterprisename;
        } elseif (isset($supervisor_name)) {
            $sql = "select orf.id as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                      adu.username = ? and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $sqlCount = "select count( orf.id) as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                      adu.username = ? and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $params[] = $supervisor_name;

        }elseif(isset($tidan_code)){

            $sql = "select orf.id as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                      orf.tidan_code = ? and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $sqlCount = "select count( orf.id) as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                       orf.tidan_code = ?  and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $params[] = $tidan_code;

        }elseif(isset($yundan_code)){
            $sql = "select orf.id as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                      orf.yundan_code = ? and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $sqlCount = "select count( orf.id) as id  from  admin_users  adu , admin_order aod ,   order_freight  orf where
                       orf.yundan_code = ?  and   aod.admin_id = adu.id   and orf.id = aod.order_id   ";
            $params[] = $yundan_code;

        } else {
            $sql = "select orf.id as id  from  order_freight orf  where 1=1   ";
            $sqlCount = "select  count(orf.id)  as id  from  order_freight orf  where 1=1     ";
            if (isset($order_freightagent_mobile)) {
                $sql .= " and orf.freightagent_user =? ";
                $sqlCount .= " and orf.freightagent_user =? ";
                $params[] = $order_freightagent_mobile;
            }
            if (isset($order_teammobile)) {
                $sql .= "  and orf.carrier_userid =? ";
                $sqlCount .= " and orf.carrier_userid =? ";
                $params[] = $order_teammobile;
            }

        }

        if (isset($order_create_time_start)) {
            $sql .= "  and orf.create_time >= ? ";
            $sqlCount .= "  and orf.create_time >=  ? ";
            $params[] = $order_create_time_start;
        }

        if (isset($order_create_time_end)) {
            $sql .= "  and orf.create_time <= ? ";
            $sqlCount .= "  and orf.create_time <= ? ";
            $params[] = $order_create_time_end;
        }

        if (isset($order_status)) {
            $sql .= " and orf.order_status =? ";
            $sqlCount .= "  and orf.order_status =? ";
            $params[] = $order_status;
        }

        $sql .= " order by orf.create_time desc ";

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



    /**
     * 订单列表Service gyao 站在货代角度
     */
    public function getFreighterOrderList($userid, $orderStatus, &$pageCount, $page, $paramsArr = array())
    {
        $order = array();
        $orderStatusParam = empty($orderStatus) ? '' : $orderStatus;
        if (empty($paramsArr))
            $freighterOrderList = $this->OrderFreightService->getOrderWithFreighter($userid, $order, $pageCount, $page, $orderStatusParam);
        else {
            $mark = 0;
            foreach ($paramsArr as $key => $value) {
                if (!empty($value)) {
                    if (strcmp($key, '1') == 0)
                        $paramsArr[$key] = $this->UserService->getIdByUserid($value);
                    elseif (strcmp($key, '2') == 0)
                        $paramsArr[$key] = $this->Tbl_province->getOrderFreightIdByCityName($value);
                    $mark++;
                }
            }
            if ($mark > 1) {
                Logger::warn("传递查询参数 多余一个，检查代码及逻辑！");
                return false;
            }
            $freighterOrderList = $this->OrderFreightService->getOrderWithFreighter($userid, $order, $pageCount, $page, $orderStatusParam, $paramsArr);
        }
        if (empty($freighterOrderList)) {
            Logger::warn(" 订单列表查询错误， 请检查代码！");
            return false;
        }
        if (empty($this->OrderFreightService->getOrderFullMsg($freighterOrderList))) {
            Logger::warn("完善信息出错，检查代码！");
            return false;
        }
        if (empty($this->OrderFreightService->getPrintArr($freighterOrderList))) {
            Logger::warn("转换格式错误，检查代码!");
            return false;
        }
        return $freighterOrderList;
    }

    //站在 车队角度上 查看
    public function getCarrierOrderList($userid, $orderStatus, &$pageCount, $page, $paramsArr = array())
    {
        $order = array();
        $orderStatusParam = empty($orderStatus) ? '' : $orderStatus;
        if (empty($paramsArr))
            $carrierOrderList = $this->OrderFreightService->getOrderWithCarrier($userid, $order, $pageCount, $page, $orderStatusParam);
        else {
            $mark = 0;
            foreach ($paramsArr as $key => $value) {
                if (!empty($value)) {
                    if (strcmp($key, '1') == 0)
                        $paramsArr[$key] = $this->UserService->getIdByUserid($value);
                    elseif (strcmp($key, '2') == 0)
                        $paramsArr[$key] = $this->YardInfoService->getIdByCityName($value);
                    $mark++;
                }
            }
            if ($mark > 1) {
                Logger::warn("传递查询参数 多余一个，检查代码及逻辑！");
                return false;
            }
            $carrierOrderList = $this->OrderFreightService->getOrderWithCarrier($userid, $order, $pageCount, $page, $orderStatusParam, $paramsArr);
        }
        if (empty($carrierOrderList)) {
            Logger::warn(" 订单列表查询错误， 请检查代码！");
            return false;
        }
        if (empty($this->OrderFreightService->getOrderFullMsg($carrierOrderList, 1))) {
            Logger::warn("完善信息出错，检查代码！");
            return false;
        }
        if (empty($this->OrderFreightService->getPrintArr($carrierOrderList))) {
            Logger::warn("转换格式错误，检查代码!");
            return false;
        }
        return $carrierOrderList;
    }
    //返回 订单列表
    //获取 各种状态的 总页数 mark=null
    public function getTotalCount($userId, $mark = null)
    {
        $statusArr = $this->di->get('order_config')->order_status;
        $pageSize = $this->di->get('order_config')->order_list_pageSize;
        $pageCountArr = array();
        foreach ($statusArr as $key => $value)
            $pageCountArr[$key] = $this->OrderFreightService->getPageCount($userId, $key, $pageSize, $mark);
        return $pageCountArr;
    }


    public function getOrderOpList($orderid, $platformType)
    {
        //todo more test by haibo
        $log = array('data_sum' => 0);
        $admin_log = array('data_sum' => 0);
        $ret = array(
            'log' => &$log,
            'admin_log' => &$admin_log,
        );

        //查询条件
        $param = array();
        $type = $this->constant->ACTION_REAM_TYPE->ORDER;
        $param['conditions'][] = "targetReamType=$type";
        $param['conditions'][] = "targetReamId=$orderid";
        $param['order'][] = 'created_at asc';

        //查询前台log
        if ($platformType != 2) {
            $param = array();
            $param['columns'] = 'id,created_at,ip,reamId,reamType,targetReamId,targetReamType,platform,deviceId,jsonContent';
            $param['model'] = '\ActivityLog';
            $sum = QueryHelper::query($param, $log);
            Logger::info('getOrderOpList www_log-sum' . $sum);
        }

        //查询后台log
        if ($platformType != 1) {
            $param['columns'] = 'id,created_at,ip,targetReamId,targetReamType,json_content,action_type';
            $param['model'] = '\AdminLog';
            $sum = QueryHelper::query($param, $admin_log);
            Logger::info('getOrderOpList admin_log-sum' . $sum);
        }

        return $ret;
    }



    //用户 和 订单校验
    public function checkUserOrder($userid, $orderid, $order_freight_box_id = null){
        $sql1 = 'SELECT `freightagent_user`, `carrier_userid` FROM `order_freight` WHERE `id` = ?';
        $sql2 = 'SELECT `order_freight_id` FROM `order_freight_box` WHERE `id` = ?';
        $result = false;
        if(!empty($order_freight_box_id)){
            $arr1 = $this->db->fetchAll($sql2, 2, [$order_freight_box_id]);
            if(!empty($arr1))
                $orderFreightId = $arr1[0]['order_freight_id'];
        }
        else
            $orderFreightId = $orderid;
        if(!empty($orderFreightId)) {
            $arr = $this->db->fetchAll($sql1, 2, [$orderFreightId]);
            if (!empty($arr)) {
                $freighter = $arr[0]['freightagent_user'];
                $carrier = $arr[0]['carrier_userid'];
                if (strcmp($userid, $freighter) == 0 || strcmp($userid, $carrier) == 0) {
                    Logger::info('userid = %s, freighter = %s, carrier = %s', $userid, $freighter, $carrier);
                    $result = true;
                }
            }
        }
        return $result;
    }


}