<?php
namespace Services\DataService;

use Library\Helper\ObjectHelper;
use Phalcon\Mvc\User\Component;
use OrderFreight;
use Library\Log\Logger;

class OrderFreightListService extends Component {
    /**
     * order_freight 获取信息 by id
     */
    public function getOrderInfoById($order_freight_id){
        $sql = 'SELECT id, yundan_code, freightagent_user, cock_city_code, import_type, carrier_userid,
                tidan_code, ship_name_id, shipping_company_id, ship_ticket, ship_ticket_desc, yard_id,
                product_name, product_desc, product_weight, product_box_type, box_20gp_count, box_40gp_count, box_40hq_count,
                 order_status, order_total_percent, create_time, submit_time FROM `order_freight` WHERE `id` = ?';
        return $this->db->fetchAll($sql, 2, [$order_freight_id]);
    }

    //通过 船名字获取 ship
    public function getShipIdByName($china_name){
        $sql = 'SELECT id FROM `shipping_company` WHERE `china_name` = ?';
        $shipIdArr = $this->db->fetchAll($sql, 2, [$china_name]);
        if(!empty($shipIdArr))
            $shipId = $shipIdArr[0]['id'];
        else
            $shipId = -1;
        return $shipId;
    }
    //通过 userid 获取 公司名字 联系人 电话
    public function getCompanyInfo($userid){
        $userInfo = array();
        $sql = 'SELECT id, contactName, contactNumber, enterpriseid, unverify_enterprisename FROM `users` WHERE `id` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$userid]);
        if(!empty($arr))
            $userInfo = array(
                'contactName' => $arr[0]['contactName'],
                'contactNumber' => $arr[0]['contactNumber'],
                'unverify_enterprisename' => $arr[0]['unverify_enterprisename'],
            );
        return $userInfo;
    }

    //order_freight_id 获取产装地址 和 产装时间 产装信息
    public function getOrderProductInfo($order_freight_id){
        $result = array();
        $sql = 'SELECT id, order_freight_id, contact_name, contact_number, address_provinceid, address_cityid, address_townid, address_detail FROM `order_product_address` WHERE `order_freight_id` = ? AND `enable` = 1';
        $arr = $this->db->fetchAll($sql, 2, [$order_freight_id]);
        if(!empty($arr)){
            foreach($arr as $key => $value) {
                $result[$key] = array(
                    'order_freight_id' => $value['order_freight_id'],
                    'contactName' => $value['contact_name'],
                    'contactNumber' => $value['contact_number'],
                    'address' => ($this->OrderFreightBoxDetailService->getProfectCityName($value['address_provinceid'], $value['address_cityid'], $value['address_townid']) . ' ' . $value['address_detail']),
                );
                $sql1 = 'SELECT product_supply_time FROM `order_product_time` WHERE `order_product_addressid` = ? AND `enable` = 1';
                $arr1 = $this->db->fetchAll($sql1, 2, [$value['id']]);
                $arr2 = array();
                if (!empty($arr1))
                    foreach ($arr1 as $key1 => $value1)
                        $arr2[$key1] = $value1['product_supply_time'];
                $result[$key]['supply_time'] = $arr2;
            }
        }
        return $result;
    }

    //通过 ship_name_id, shipping_company_id, ship_ticket, ship_ticket_desc 获取船信息
    public function getShipInfo($ship_name_id, $shipping_company_id, $ship_ticket){
        $shipInfoArr= array();
        $sql1 = 'SELECT china_name, english_name FROM `shipping_company` WHERE `id` = ?';
        $chinaName = '';
        $engName = '';
        $shipComArr = $this->db->fetchAll($sql1, 2, [$shipping_company_id]);
        if(!empty($shipComArr)) {
            $chinaName .= $shipComArr[0]['china_name'];
            $engName .= $shipComArr[0]['english_name'];
            $shipInfoArr['ship_company_china_name'] = $shipComArr[0]['china_name'];
            $shipInfoArr['ship_company_english_name'] = $shipComArr[0]['english_name'];
        }
        $sql2 = 'SELECT china_name, eng_name FROM `ship_name` WHERE `id` = ?';
        $shipArr = $this->db->fetchAll($sql2, 2, [$ship_name_id]);
        if(!empty($shipArr)){
            if(!empty($chinaName)) {
                if (!empty($shipArr[0]['china_name']))
                    $chinaName .= (' ' . $shipArr[0]['china_name']);
            }
            else{
                if (!empty($shipArr[0]['china_name']))
                    $chinaName .= $shipArr[0]['china_name'];
            }
            if(!empty($engName)) {
                if (!empty($shipArr[0]['eng_name']))
                    $engName .= (' ' . $shipArr[0]['eng_name']);
            }
            else{
                if (!empty($shipArr[0]['eng_name']))
                    $engName .= $shipArr[0]['eng_name'];
            }
            $shipInfoArr['ship_china_name'] = $shipArr[0]['china_name'];
            $shipInfoArr['ship_english_name'] = $shipArr[0]['eng_name'];
        }
        if(!empty($chinaName)){
            if(!empty($ship_ticket))
                $chinaName .= (' ' . $ship_ticket);
        }
        else{
            if(!empty($ship_ticket))
                $chinaName .= $ship_ticket;
        }
        if(!empty($engName)) {
            if(!empty($ship_ticket))
                $engName .= (' ' . $ship_ticket);
        }
        else{
            if(!empty($ship_ticket))
                $engName .= $ship_ticket;
        }
        if(strcmp($engName, ' ') == 0)
            $engName = '';
        $shipInfoArr['full_china_name'] = $chinaName;
        $shipInfoArr['full_english_name'] = $engName;
        return $shipInfoArr;
    }
    //通过堆场id获取堆场名字
    public function getYardName($yard_id){
        $sql = 'SELECT `yard_name` FROM `yard_info` WHERE `id` = ?';
        $str = '';
        $arr = $this->db->fetchAll($sql, 2, [$yard_id]);
        if(!empty($arr))
            $str .= $arr[0]['yard_name'];
        return $str;
    }
    //通过id 获取订单详情
    public function getOrderMsg($order_freight_id){
        $arr = $this->getOrderInfoById($order_freight_id);
        $result = array();
        if(!empty($arr)){
            $result = $arr[0];
            $result['freight_agent_company_info'] = $this->getCompanyInfo($arr[0]['freightagent_user']);
            $result['carrier_company_info'] = $this->getCompanyInfo($arr[0]['carrier_userid']);
            $result['order_product_info'] = $this->getOrderProductInfo($order_freight_id);
            $shipInfo = $this->getShipInfo($arr[0]['ship_name_id'], $arr[0]['shipping_company_id'], $arr[0]['ship_ticket']);
            $result['ship_info'] = empty($shipInfo) ? '' : $shipInfo;
            $result['supervisor_info'] =  $this->OrderSuperService->getSupervisorInfoByOrderid($order_freight_id) ;
            $result['yard_name'] = $this->getYardName($arr[0]['yard_id']);
        }
        return $result;
    }

    //获取 userid 的 order_freight_id 集合 mark = 0 表示站在货代角度上看待问题
    public function getOrderFreightIdArr($user_id, $order_status, $mark = 0, $searchType = 0, $searchValue = 0){
        $result = array();
        if($searchValue != -1){
            if ($mark == 0) {
                if ($order_status == 0) {
                    if ($searchType == 1 && $searchValue != 0)//承运方
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `carrier_userid` = ?';
                    elseif ($searchType == 2 && $searchValue != 0)//提单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `tidan_code` = ?';
                    elseif ($searchType == 3 && $searchValue != 0)//运单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `yundan_code` = ?';
                    elseif ($searchType == 4 && $searchValue != 0)
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `shipping_company_id` = ?';
                    else
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ?';
                } else {
                    if ($searchType == 1 && $searchValue != 0)//承运方
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `carrier_userid` = ? AND `order_status` = ?';
                    elseif ($searchType == 2 && $searchValue != 0)//提单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `tidan_code` = ? AND `order_status` = ?';
                    elseif ($searchType == 3 && $searchValue != 0)//运单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `yundan_code` = ? AND `order_status` = ?';
                    elseif ($searchType == 4 && $searchValue != 0)//船 名 id
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `shipping_company_id` = ? AND `order_status` = ?';
                    else
                        $sql = 'SELECT id FROM `order_freight` WHERE `freightagent_user` = ? AND `order_status` = ?';
                }
            } else {
                if ($order_status == 0) {
                    if ($searchType == 1 && $searchValue != 0)//承运方
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `freightagent_user` = ?';
                    elseif ($searchType == 2 && $searchValue != 0)//提单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `tidan_code` = ?';
                    elseif ($searchType == 3 && $searchValue != 0)//运单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `yundan_code` = ?';
                    elseif ($searchType == 4 && $searchValue != 0)//船名id
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `shipping_company_id` = ?';
                    else
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ?';
                } else {
                    if ($searchType == 1 && $searchValue != 0)//承运方
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `freightagent_user` = ? AND `order_status` = ?';
                    elseif ($searchType == 2 && $searchValue != 0)//提单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `tidan_code` = ? AND `order_status` = ?';
                    elseif ($searchType == 3 && $searchValue != 0)//运单号
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `yundan_code` = ? AND `order_status` = ?';
                    elseif ($searchType == 4 && $searchValue != 0)//船 名 id
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `shipping_company_id` = ? AND `order_status` = ?';
                    else
                        $sql = 'SELECT id FROM `order_freight` WHERE `carrier_userid` = ? AND `order_status` = ?';
                }
            }
            if ($order_status == 0) {
                if (($searchType == 1 || $searchType == 2 || $searchType == 3 || $searchType == 4) && $searchValue != 0)
                    $arr = $this->db->fetchAll($sql, 2, [$user_id, $searchValue]);
                else
                    $arr = $this->db->fetchAll($sql, 2, [$user_id]);
            } else {
                if (($searchType == 1 || $searchType == 2 || $searchType == 3 || $searchType == 4) && $searchValue != 0)
                    $arr = $this->db->fetchAll($sql, 2, [$user_id, $searchValue, $order_status]);
                else
                    $arr = $this->db->fetchAll($sql, 2, [$user_id, $order_status]);
            }
            if (!empty($arr))
                foreach ($arr as $key => $value)
                    $result[$key] = $value['id'];
        }
        return $result;
    }

    //返回 各种状态总页数
    public function getCountPage($user_id, $order_status, $mark = 0, $searchType = 0, $searchValue = 0){
        $arr = $this->getOrderFreightIdArr($user_id, $order_status, $mark, $searchType, $searchValue);
        $pageSize = $this->di->get('order_config')->order_list_pageSize;
        $shang = (int)(count($arr) / $pageSize);
        $yushu = count($arr) % $pageSize;
        return  $yushu > 0 ? ($shang + 1) : $shang;
    }
    //orderList 格式化
    public function getPrintOrderList($orderList, $mark = 0){
        $resultList = array();
        if(!empty($orderList)) {
            foreach ($orderList as $key => $value) {
                $one = array();
                $one['import_type'] = $value['import_type'];
                $one['orderid'] = $value['id'];
                $one['isDisplayTransport'] = $value['isDisplayTransport'];
                $one['tidan_code'] = $value['tidan_code'];
                if ($mark == 0) {
                    $one['create_time'] = $value['create_time'];
                    $one['contactName'] = $value['carrier_company_info']['contactName'];
                    $one['contactNumber'] = $value['carrier_company_info']['contactNumber'];
                    $one['company_name'] = $value['carrier_company_info']['unverify_enterprisename'];
                } else {
                    $one['create_time'] = $value['submit_time'];
                    $one['contactName'] = $value['freight_agent_company_info']['contactName'];
                    $one['contactNumber'] = $value['freight_agent_company_info']['contactNumber'];
                    $one['company_name'] = $value['freight_agent_company_info']['unverify_enterprisename'];
                }
                $one['product_adress_detail'] = $value['order_product_info'];
                $one['ship_info'] = $value['ship_info'];
                $one['box_info'] = array(
                    array(
                        'box_type' => 1,
                        'box_number' => $value['box_20gp_count'],
                    ),
                    array(
                        'box_type' => 2,
                        'box_number' => $value['box_40gp_count'],
                    ),
                    array(
                        'box_type' => 3,
                        'box_number' => $value['box_40hq_count'],
                    ),
                    array(
                        'box_type' => 4,
                        'box_number' => $value['box_45hq_count'],
                    )
                );
                $one['status'] = $value['order_status'];
                $one['order_total_percent'] = $value['order_total_percent'];
                $one['yundan_code'] = $value['yundan_code'];
                $resultList[$key] = $one;
            }
        }
        Logger::info('resultList: '.var_export($resultList,true));
        return $resultList;
    }

    //获得sql语句
    public function getSQL($order_status, $page, $mark, $searchType, $searchValue){
        $sql = 'SELECT id, yundan_code, freightagent_user, cock_city_code, import_type, carrier_userid,
                tidan_code, ship_name_id, shipping_company_id, ship_ticket, ship_ticket_desc, yard_id,
                product_name, product_desc, product_weight, box_20gp_count, box_40gp_count, box_40hq_count,
                box_45hq_count, order_status, order_total_percent, create_time, submit_time FROM `order_freight` WHERE';
        $pageSize = $this->di->get('order_config')->order_list_pageSize;
        $offset = ($page - 1) * $pageSize;
        if(empty($mark))
            $sql .= ' `freightagent_user` = ?';
        else
            $sql .= ' `carrier_userid` = ?';
        if($order_status == 2 || $order_status == 3){
            $sql .= ' AND `order_status` IN (2, 3)';
        }
        elseif($order_status != 0)
            $sql .= ' AND `order_status` = ?';
        if($searchType == 1 && $searchValue != 0)
            $sql .= empty($mark) ? ' AND `carrier_userid` = ?' : ' AND `freightagent_user` = ?';
        elseif($searchType == 2 && $searchValue != 0)
            $sql .= ' AND `tidan_code` = ?';
        elseif($searchType == 3 && $searchValue != 0)
            $sql .= ' AND `yundan_code` = ?';
        elseif($searchType == 4 && $searchValue != 0)
            $sql .= ' AND `shipping_company_id` = ?';
        if($mark == 0)
            $sql .= " ORDER BY `create_time` DESC LIMIT $pageSize OFFSET $offset";
        else
            $sql .= " ORDER BY `submit_time` DESC LIMIT $pageSize OFFSET $offset";
        return $sql;
    }
    //返回分页信息列表
    public function getOrderList($user_id, $order_status, $page, $mark, $searchType, $searchValue){
        $result = array();
        if($searchType != -1){
            $arr = array($user_id);
            if ($order_status != 0 && $order_status != 2 && $order_status != 3)
                $arr[] = $order_status;
            if ($searchType != 0 && $searchValue != 0)
                $arr[] = $searchValue;
            $sql = $this->getSQL($order_status, $page, $mark, $searchType, $searchValue);
            $result = $this->db->fetchAll($sql, 2, $arr);
            Logger::info('通过数据库分页' . var_export($result, true));
        }
        return $result;
    }
    //对一个 order 进行 完善
    public function completeOrder($order){
        if(!empty($order)){
            $order['freight_agent_company_info'] = $this->getCompanyInfo($order['freightagent_user']);
            $order['carrier_company_info'] = $this->getCompanyInfo($order['carrier_userid']);
            $order['order_product_info'] = $this->getOrderProductInfo($order['id']);
            $order['isDisplayTransport'] = $this->isDisplayTransport($order['id']);
            $shipInfo = $this->getShipInfo($order['ship_name_id'], $order['shipping_company_id'], $order['ship_ticket']);
            $order['ship_info'] = empty($shipInfo) ? '' : $shipInfo;
            $order['supervisor_info'] =  $this->OrderSuperService->getSupervisorInfoByOrderid($order['id']) ;
        }
        return $order;
    }
    //利用 公司名字 返回 userid
    public function getUserid($company_name){
        $sql = 'SELECT `id` FROM `users` WHERE `unverify_enterprisename` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$company_name]);
        $result = -1;
        if(!empty($arr))
            $result = $arr[0]['id'];
        return $result;
    }
    //是否展示 订单详情
    public function isDisplayTransport($order_freight_id){
        $sql = 'SELECT `id` FROM `order_assign_driver` WHERE `order_freight_id` = ?';
        $result = false;
        $arr = $this->db->fetchOne($sql, 2, [$order_freight_id]);
        if(!empty($arr))
            $result = true;
        return $result;
    }
    //不考虑 搜索条件
    public function getMsg($user_id, $user_type, $order_status, $page, $searchParams = array()){
        $orderList = array();
        if($user_type == 1)
            $mark = 1;
        elseif($user_type == 2)
            $mark = 0;
        else
            return $orderList;
        if(!empty($searchParams)) {
            $search = 1;
            $searchType = $searchParams['searchType'];
            $searchValue = $searchParams['searchValue'];
            if ($searchType == 1)
                $searchValue = $this->getUserid($searchValue);
            if ($searchType == 4)
                $searchValue = $this->getShipIdByName($searchValue);
        }else{
            $search = 0;
            $searchType = 0;
            $searchValue = 0;
        }
        $arr = $this->getOrderList($user_id, $order_status, $page, $mark, $searchType, $searchValue);
        $pageArr = array();
        for($i = 0; $i < 9; $i++)
            $pageArr[$i] = count($this->getOrderFreightIdArr($user_id, $i, $mark, $searchType, $searchValue));
        foreach($arr as $key => $value)
            $orderList[$key] = $this->completeOrder($value);
        $thisStatusPage = $this->getCountPage($user_id, $order_status, $mark, $searchType, $searchValue); //返回此状态下得 总页数
        $result = array(
            'search' => $search,
            'searchType' => $searchParams['searchType'],
            'searchValue' => $searchParams['searchValue'],
            'total_count' => $pageArr,
            'total_page' => $thisStatusPage,
            'page' => $page,
            'orderStatus' => (int)$order_status,
            'former_order_list' => $orderList,
            'order_list' => $this->getPrintOrderList($orderList, $mark),
        );
        return $result;
    }
}