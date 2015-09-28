<?php
namespace Services\DataService;

use Library\Helper\YuDanNoHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

//退载重建
class OrderFreightReconstructService extends Component{
    //获取老订单信息
    public function getOldOrderInfo($old_order_freight_id){
        return $this->OrderFreightListService->getOrderMsg($old_order_freight_id);
    }

    //获取现在最大id
    public function getMaxId(){
        $sql = 'SELECT `id` FROM `order_freight` ORDER BY `id` DESC';
        $arr = $this->db->fetchAll($sql, 2);
        $num = 1;
        if(!empty($arr))
            $num = $arr[0]['id'];
        return $num;
    }
    //将老订单拷贝至新订单 并且返回新订单id
    public function copyToNewOrder($old_order_freight_id, $yundan_code){
        $sql1 = 'SELECT freightagent_user, cock_city_code, import_type, carrier_userid, tixiangdan_file_url, addresscontact_file_urls, tidan_code,
                 ship_name_id, shipping_company_id, ship_ticket, ship_ticket_desc, yard_id, product_name, product_desc, product_weight, product_box_type, box_20gp_count,
                 box_40gp_count, box_40hq_count, order_status, order_total_percent, submit_time FROM `order_freight` WHERE `id` = ?';
        $sql2 = 'INSERT INTO `order_freight` (`id`, `freightagent_user`, `cock_city_code`, `import_type`, `carrier_userid`, `tixiangdan_file_url`, `addresscontact_file_urls`, `tidan_code`,
                 `ship_name_id`, `shipping_company_id`, `ship_ticket`, `ship_ticket_desc`, `yard_id`, `product_name`, `product_desc`, `product_weight`, `product_box_type`, `box_20gp_count`,
                 `box_40gp_count`, `box_40hq_count`, `order_status`, `order_total_percent`, `submit_time`, `yundan_code`, `create_time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $res = array();
        $arr1 = $this->db->fetchAll($sql1, 2, [$old_order_freight_id]);
        if(!empty($arr1)){
            $id = $this->getMaxId() + 1;
            $freightagent_user = $arr1[0]['freightagent_user'];
            $cock_city_code = $arr1[0]['cock_city_code'];
            $import_type = $arr1[0]['import_type'];
            $carrier_userid = $arr1[0]['carrier_userid'];
            $tixiangdan_file_url = $arr1[0]['tixiangdan_file_url'];
            $addresscontact_file_urls = $arr1[0]['addresscontact_file_urls'];
            $tidan_code = $arr1[0]['tidan_code'];
            $ship_name_id = $arr1[0]['ship_name_id'];
            $shipping_company_id = $arr1[0]['shipping_company_id'];
            $ship_ticket = $arr1[0]['ship_ticket'];
            $ship_ticket_desc = $arr1[0]['ship_ticket_desc'];
            $yard_id = $arr1[0]['yard_id'];
            $product_name = $arr1[0]['product_name'];
            $product_desc = $arr1[0]['product_desc'];
            $product_weight = $arr1[0]['product_weight'];
            $product_box_type = $arr1[0]['product_box_type'];
            $box_20gp_count = $arr1[0]['box_20gp_count'];
            $box_40gp_count = $arr1[0]['box_40gp_count'];
            $box_40hq_count = $arr1[0]['box_40hq_count'];
            $order_status = $arr1[0]['order_status'];
            $order_total_percent = $arr1[0]['order_total_percent'];
            $submit_time = $arr1[0]['submit_time'];
            $create_time = date('Y-m-d h:i:s', time());
            $paramsArr = array($id, $freightagent_user, $cock_city_code, $import_type, $carrier_userid, $tixiangdan_file_url, $addresscontact_file_urls, $tidan_code,
                $ship_name_id, $shipping_company_id, $ship_ticket, $ship_ticket_desc, $yard_id, $product_name, $product_desc, $product_weight,
                $product_box_type, $box_20gp_count, $box_40gp_count, $box_40hq_count, $order_status, $order_total_percent, $submit_time, $yundan_code, $create_time);
            $res = array(
                'order_status' => $order_status,
                'id' => $id,
                'sql' => $sql2,
                'params' => $paramsArr,
            );
        }
        return $res;
    }

    //修改新订单 船信息 货物信息
    public function changeNewOrderInfo($new_order_freight_id, $ship_info_arr, $product_info_arr){
        $shipCompanyId = $ship_info_arr['shipping_company_id'];
        $ship_name_id = $ship_info_arr['ship_name_id'];
        $ship_ticket = $ship_info_arr['ship_ticket'];
        $tidan_code = $ship_info_arr['tidan_code'];
        $yard_id = $ship_info_arr['yard_id'];
        $product_box_type = $product_info_arr['product_box_type'];
        $box_20gp_count = $product_info_arr['box_20gp_count'];
        $box_40gp_count = $product_info_arr['box_40gp_count'];
        $box_40hq_count = $product_info_arr['box_40hq_count'];
        $product_name = $product_info_arr['product_name'];
        $product_desc = $product_info_arr['product_desc'];
        $product_weight = $product_info_arr['product_weight'];//shipping_company_id = ?, ship_name_id = ?,yard_id = ?,
        $sql = 'UPDATE `order_freight` SET  ship_ticket = ?, tidan_code = ?, product_box_type = ?, box_20gp_count = ?, box_40gp_count = ?, box_40hq_count = ?, product_weight = ? ';//WHERE `id` = ?
        $arr = array($ship_ticket, $tidan_code, $product_box_type, $box_20gp_count, $box_40gp_count,
                    $box_40hq_count, $product_weight);
        if(!empty($shipCompanyId)){
            $sql .= ', shipping_company_id = ?';
            $arr[] = $shipCompanyId;
        }
        if(!empty($ship_name_id)){
            $sql .= ', ship_name_id = ?';
            $arr[] = $ship_name_id;
        }
        if(!empty($yard_id)){
            $sql .= ', yard_id = ?';
            $arr[] = $yard_id;
        }
        if(!empty($product_name)){
            $sql .= ', product_name = ?';
            $arr[] = $product_name;
        }
        if(!empty($product_desc)){
            $sql .= ', product_desc = ?';
            $arr[] = $product_desc;
        }
        $sql .= ' WHERE `id` = ?';
        $arr[] = $new_order_freight_id;
        $result = array(
            'sql' => $sql,
            'params' => $arr,
        );
        return $result;
    }

    //获取箱子最新状态 通过 order_freight_box_id
    public function getBoxStatus($order_freight_box_id){
        $sql = 'SELECT `box_status` FROM `order_freight_box` WHERE `id` = ?';
        $arr = $this->db->fetchOne($sql, 2, [$order_freight_box_id]);
        $res = true;
        if(!empty($arr))
            if($arr['box_status'] > 4)
                $res = false;
        return $res;
    }
    //通过old_order_freight_id 获取全部箱子id
    public function getOldOrderFreightBox($old_order_freight_id){
        $sql = 'SELECT `id` FROM `order_freight_box` WHERE `order_freight_id` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$old_order_freight_id]);
        $result = array();
        if(!empty($arr))
            foreach($arr as $key => $value) {
                $boxStatus = $this->getBoxStatus($value['id']);
                if($boxStatus)
                    $result[] = $value['id'];
            }
        return $result;
    }
    //通过 order_freight_id 获取所有箱子id
    public function getAllBoxId($order_freight_id){
        $sql = 'SELECT `id`, `box_size_type` FROM `order_freight_box` WHERE `order_freight_id` = ?';
        return $this->db->fetchAll($sql, 2, [$order_freight_id]);
    }
    //获取 order_freight_id 下 已运抵状态之前 各种箱子数目
    public function getBoxNumberBeforeYundi($order_freight_id){
        $boxArr = $this->getAllBoxId($order_freight_id);
        $result = array(
            '20gp' => 0,
            '40gp' => 0,
            '40hq' => 0,
        );
        if(!empty($boxArr))
            foreach($boxArr as $key => $value){
                $boxStatus = $this->getBoxStatus($value['id']);
                if($boxStatus){
                    if($value['box_size_type'] == 1)
                        $result['20gp']++;
                    elseif($value['box_size_type'] == 2)
                        $result['40gp']++;
                    elseif($value['box_size_type'] == 3)
                        $result['40hq']++;
                }
            }
        return $result;
    }
    //die old order
    public function dieOldOrder($order_id){
        $sql2 = 'UPDATE `order_freight` SET `order_status` = 8 WHERE `id` = ?';
        return $this->db->execute($sql2, [$order_id]);
    }
    //changeInfo Transaction
    public function change($old_order_freight_id, $ship_info_arr, $product_info_arr, $yundan_code){
        $time = time().rand(0, 99999999);
        $boxArr = $this->getOldOrderFreightBox($old_order_freight_id);
        $sql1 = 'UPDATE `order_freight_box` SET order_freight_id = ? WHERE `id` = ?';
        $sql2 = "UPDATE `order_freight` SET `order_status` = 8, `yundan_code` = $time, `tidan_code` = $time WHERE `id` = ?";
        $sql3 = 'INSERT INTO `activity_log` (`created_at`, `updated_at`, `action_type`, `reamId`, `targetReamId`, `reamType`) VALUES (NOW(), NOW(), ?, ?, ?, ?)';
        $sql40 = 'SELECT `id` FROM `order_product_address` WHERE `order_freight_id` = ? AND `enable` = 1';
        $sql41 = 'UPDATE `order_product_address` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql50 = 'SELECT `id` FROM `order_product_time` WHERE `order_freight_id` = ? AND `enable` = 1';
        $sql51 = 'UPDATE `order_product_time` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql60 = 'SELECT `id` FROM `order_assign_driver` WHERE `order_freight_id` = ? AND `enable` = 1';
        $sql61 = 'UPDATE `order_assign_driver` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql70 = 'SELECT `id` FROM `order_box_timeline` WHERE `order_freight_id` = ?';
        $sql71 = 'UPDATE `order_box_timeline` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql80 = 'SELECT `id` FROM `order_box_location` WHERE `order_freight_id` = ?';
        $sql81 = 'UPDATE `order_box_location` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql90 = 'SELECT `id` FROM `order_freight_timeline` WHERE `order_freight_id` = ? ORDER BY `create_time` DESC';
        $sql91 = 'UPDATE `order_freight_timeline` SET `order_freight_id` = ? WHERE `id` = ?';
        $sql100 = 'INSERT INTO `order_freight_timeline` (`order_freight_id`, `ordertimeline_type`, `create_time`, `update_time`) VALUES (?, ?, NOW(), NOW())';
        $sql101 = 'INSERT INTO `order_box_timeline` (`order_freight_id`, `order_freight_boxid`, `location_msg`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $result = false;
        $arr1 = $this->copyToNewOrder($old_order_freight_id, $yundan_code);
        if(!empty($arr1)){
            $new_order_freight_id = $arr1['id'];
            $db = $this->db;
            do{
                try {
                    $db->begin();
                    if($arr1['order_status'] > 5) {
                        Logger::warn('退载重建--订单状态不允许重建');
                        break;
                    }
                    $isContinue1 = $db->execute($arr1['sql'], $arr1['params']);
                    if(!$isContinue1){
                        Logger::warn('退载重建--写入数据库--创建新订单出错');
                        break;
                    }
                    $arr2 = $this->changeNewOrderInfo($new_order_freight_id, $ship_info_arr, $product_info_arr);
                    $isContinue2 = true;
                    if (!empty($arr2))
                        $isContinue2 = $db->execute($arr2['sql'], $arr2['params']);
                    if(!$isContinue2){
                        Logger::warn('退载重建--写入数据库--更新新订单出错');
                        break;
                    }
                    $register = $this->di->get('order_config')->box_timeline_template->TUIZAICHONGJIAN;
                    $tidanCode = $this->getTidanCode($new_order_freight_id);
                    $location_msg = str_replace(array('{tidan_code}'), array($tidanCode), $register);
                    $isContinue3 = true;
                    if (!empty($boxArr))
                        foreach ($boxArr as $key => $value) {
                            $isContinue3 = $db->execute($sql1, [$new_order_freight_id, $value]);
                            if (!$isContinue3)
                                break;
                            $isContinue3 = $db->execute($sql101, [$new_order_freight_id, $value, $location_msg]);
                            if (!$isContinue3) {
                                Logger::warn('退载重建--写入数据库--更新表 order_box_timeline 错误');
                                break;
                            }
                        }
                    if(!$isContinue3){
                        Logger::warn('退载重建--写入数据库--更新box表错误');
                        break;
                    }
                    $isContinue5 = $db->execute($sql3, [19, $old_order_freight_id, $new_order_freight_id, 4]);
                    if(!$isContinue5) {
                        Logger::warn('退载重建--写入数据库--插入记录错误');
                        break;
                    }
                    $arr6 = $db->fetchAll($sql40, 2, [$old_order_freight_id]);
                    $isContinue6 = true;
                    if(!empty($arr6)) {
                        foreach ($arr6 as $key => $value) {
                            $isContinue6 = $db->execute($sql41, [$new_order_freight_id, $value['id']]);
                            if (!$isContinue6)
                                break;
                        }
                    }
                    if(!$isContinue6) {
                        Logger::warn('退载重建--写入数据库--更新产装地址错误');
                        break;
                    }
                    $arr7 = $db->fetchAll($sql50, 2, [$old_order_freight_id]);
                    $isContinue7 = true;
                    if(!empty($arr7)) {
                        foreach ($arr7 as $key => $value) {
                            $isContinue7 = $db->execute($sql51, [$new_order_freight_id, $value['id']]);
                            if (!$isContinue7)
                                break;
                        }
                    }
                    if(!$isContinue7) {
                        Logger::warn('退载重建--写入数据库--更新产装时间错误');
                        break;
                    }
                    $arr8 = $db->fetchAll($sql60, 2, [$old_order_freight_id]);
                    $isContinue8 = true;
                    if(!empty($arr8)) {
                        foreach ($arr8 as $key => $value) {
                            $isContinue8 = $db->execute($sql61, [$new_order_freight_id, $value['id']]);
                            if (!$isContinue8)
                                break;
                        }
                    }
                    if(!$isContinue8) {
                        Logger::warn('退载重建--写入数据库--更新司机表错误');
                        break;
                    }
                    $arr9 = $db->fetchAll($sql70, 2, [$old_order_freight_id]);
                    $isContinue9 = true;
                    if(!empty($arr9)) {
                        foreach ($arr9 as $key => $value) {
                            $isContinue9 = $db->execute($sql71, [$new_order_freight_id, $value['id']]);
                            if (!$isContinue9)
                                break;
                        }
                    }
                    if(!$isContinue9) {
                        Logger::warn('退载重建--写入数据库--更新箱子时间线错误');
                        break;
                    }
                    $arr10 = $db->fetchAll($sql80, 2, [$old_order_freight_id]);
                    $isContinue10 = true;
                    if(!empty($arr10)) {
                        foreach ($arr10 as $key => $value) {
                            $isContinue10 = $db->execute($sql81, [$new_order_freight_id, $value['id']]);
                            if (!$isContinue10)
                                break;
                        }
                    }
                    if(!$isContinue10) {
                        Logger::warn('退载重建--写入数据库--更新 order_box_location 表错误');
                        break;
                    }
                    $arr11 = $db->fetchAll($sql90, 2, [$old_order_freight_id]);
                    $isContinue11 = true;
                    if(!empty($arr11))
                        $isContinue11 = $db->execute($sql91, [$new_order_freight_id, $arr11[0]['id']]);
                    if(!$isContinue11) {
                        Logger::warn('退载重建--写入数据库--更新 order_freight_timeline 表错误');
                        break;
                    }
                    $isContinue12 = $db->execute($sql100, [$old_order_freight_id, 0]);
                    if(!$isContinue12){
                        Logger::warn('退载重建--写入数据库--更新order_freight_timeline 出错');
                        break;
                    }
                    $isContinue4 = $db->execute($sql2, [$old_order_freight_id]);
                    if(!$isContinue4){
                        Logger::warn('退载重建--写入数据库--关闭老订单出错');
                        break;
                    }
                    $db->commit();
                    $result = true;
                }
                catch (\Exception $e) {
                    Logger::warn('退载重建--重建更改数据库出错！' . $e->getMessage());
                }
            }while(false);
            if(!$result){
                $db->rollback();
                Logger::warn('退载重建--写入数据库错误');
            }
            if($result){
                $params = $this->getTuizaiParams($new_order_freight_id);
                if(!empty($params)){
                    foreach($params as $key => $value)
                        $this->JpushService->toAlias('TUIZAICHONGJIAN', json_encode($value));
                }
                $this->OrderSuperService->assignAuto($new_order_freight_id);
            }
        }
        return $result;
    }
    //退载重建
    public function reConstruct($old_order_freight_id, $ship_info_arr, $product_info_arr){
        $yuDanNoHelper = new YuDanNoHelper(10);
        $yundanCode = $yuDanNoHelper->nextId();
        $result = $this->change($old_order_freight_id, $ship_info_arr, $product_info_arr, $yundanCode);
        return $result;
    }

    //根据订单 返回退载重建展示信息
    public function getMsg($order_feight_id){
        $result = $this->OrderFreightListService->getOrderMsg($order_feight_id);
        $arr = $this->getBoxNumberBeforeYundi($order_feight_id);
        $result['box_20gp_count'] = $arr['20gp'];
        $result['box_40gp_count'] = $arr['40gp'];
        $result['box_40hq_count'] = $arr['40hq'];
        return $result;
    }
    //退载
    public function tuizaiOrder($order_freight_id){
        $time = time().rand(0, 99999999);
        $sqlDieOrder = "UPDATE `order_freight` SET `order_status` = 8, `yundan_code` = $time, `tidan_code` = $time WHERE `id` = ?";
        $sqlGetBox = 'SELECT `id` FROM `order_freight_box` WHERE `order_freight_id` = ?';
        $sqlDieOrderBox = 'UPDATE `order_freight_box` SET `box_status` = 6 WHERE `id` = ?';
        $sqlGetAssign = 'SELECT `id` FROM `order_assign_driver` WHERE `order_freight_boxid` = ?';
        $sqlDieAssign = 'UPDATE `order_assign_driver` SET `assign_status` = 6 WHERE `id` = ?';
        $sql0 = 'INSERT INTO `order_box_timeline` (`order_freight_id`, `order_freight_boxid`, `location_msg`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $sql1 = 'INSERT INTO `order_freight_timeline` (`order_freight_id`, `ordertimeline_type`, `create_time`, `update_time`) VALUES (?, ?, NOW(), NOW())';
        $sql2 = 'INSERT INTO `activity_log` (`created_at`, `updated_at`, `action_type`, `reamId`) VALUES (NOW(), NOW(), ?, ?)';
        $actionType = $this->di->get('constant')->ACTION_TYPE->ORDER_TUIZAI;
        $result = false;
        do{
            $db = $this->db;
            $db->begin();
            try {
                $arr1 = $db->fetchAll($sqlGetBox, 2, [$order_freight_id]);
                $isContinue3 = true;
                if (!empty($arr1)) {
                    foreach ($arr1 as $key => $value) {
                        $isContinue2 = true;
                        $orderFreightBoxId = $value['id'];
                        $arr2 = $db->fetchAll($sqlGetAssign, 2, [$orderFreightBoxId]);
                        foreach ($arr2 as $key1 => $value1) {
                            $isContinue1 = $db->execute($sqlDieAssign, [$value1['id']]);
                            if (!$isContinue1) {
                                $isContinue2 = false;
                                Logger::warn('订单退载————修改 assign表错误');
                                break;
                            }
                        }
                        if (!$isContinue2) {
                            $isContinue3 = false;
                            break;
                        }
                        $location_msg = $this->di->get('order_config')->box_timeline_template->TUIZAI;
                        $isContinue2 = $db->execute($sql0, [$order_freight_id, $orderFreightBoxId, $location_msg]);
                        if (!$isContinue2) {
                            $isContinue3 = false;
                            Logger::warn('订单退载————修改 order_box_timeline 表错误');
                            break;
                        }
                        $isContinue2 = $db->execute($sqlDieOrderBox, [$orderFreightBoxId]);
                        if (!$isContinue2) {
                            $isContinue3 = false;
                            Logger::warn('订单退载————修改 order_freight_box 表错误');
                            break;
                        }
                    }
                }
                if (!$isContinue3)
                    break;
                $isContinue3 = $db->execute($sql1, [$order_freight_id, 0]);
                if (!$isContinue3) {
                    Logger::warn('订单退载————修改 order_freight_timeline 表错误');
                    break;
                }
                $isContinue3 = $db->execute($sql2, [$actionType, $order_freight_id]);
                if (!$isContinue3) {
                    Logger::warn('订单退载————插入 activity_log 表错误');
                    break;
                }
                $isContinue3 = $db->execute($sqlDieOrder, [$order_freight_id]);
                if (!$isContinue3) {
                    Logger::warn('订单退载————修改 order_freight 表错误');
                    break;
                }
                $db->commit();
                $result = true;
            }catch (\Exception $e) {
                Logger::warn($e->getMessage());
            }
        }while(false);
        if(!$result){
            $db->rollback();
            Logger::warn('订单退载--插入数据库错误');
        }
        if($result){
            $params = $this->getTuizaiParams($order_freight_id, 1);
            if(!empty($params)){
                foreach($params as $key => $value)
                    $this->JpushService->toAlias('TUIZAI', json_encode($value));
            }
        }
        return $result;
    }

    //通过船名字、船公司名字、堆场名字  来获取对应的id
    //通过船公司名字 获取船公司id
    public function getShipCompanyId($ship_company_name){
        $sql1 = 'SELECT `id` FROM `shipping_company` WHERE `china_name` = ?';
        $sql2 = 'SELECT `id` FROM `shipping_company` WHERE `english_name` = ?';
        $sql3 = 'INSERT INTO `shipping_company` (`id`, `china_name`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $sql4 = 'INSERT INTO `shipping_company` (`id`, `english_name`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $isChina = $this->isHaveHanzi($ship_company_name);
        $newId = $this->getNewId('shipping_company');
        $result = 0;
        if($isChina){
            $arr = $this->db->fetchAll($sql1, 2, [$ship_company_name]);
            if(empty($arr)){
                $isContinue = $this->db->execute($sql3, [$newId, $ship_company_name, 1]);
                if($isContinue)
                    $result = $newId;
                else
                    Logger::warn('退载重建--新建船公司错误');
            }
            else
                $result = $arr[0]['id'];
        }
        else{
            $arr = $this->db->fetchAll($sql2, 2, [$ship_company_name]);
            if(empty($arr)){
                $isContinue = $this->db->execute($sql4, [$newId, $ship_company_name, 1]);
                if($isContinue)
                    $result = $newId;
                else
                    Logger::warn('退载重建--新建船公司错误');
            }
            else
                $result = $arr[0]['id'];
        }
        return $result;
    }

    //通过船名字 获取船名字id
    public function getShipId($ship_name){
        $sql1 = 'SELECT `id` FROM `ship_name` WHERE `china_name` = ?';
        $sql2 = 'SELECT `id` FROM `ship_name` WHERE `eng_name` = ?';
        $sql3 = 'INSERT INTO `ship_name` (`id`, `china_name`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $sql4 = 'INSERT INTO `ship_name` (`id`, `eng_name`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $isChina = $this->isHaveHanzi($ship_name);
        $newId = $this->getNewId('ship_name');
        $result = 0;
        if($isChina){
            $arr = $this->db->fetchAll($sql1, 2, [$ship_name]);
            if(empty($arr)){
                $isContinue = $this->db->execute($sql3, [$newId, $ship_name, 1]);
                if($isContinue)
                    $result = $newId;
                else
                    Logger::warn('退载重建--新建船公司错误');
            }
            else
                $result = $arr[0]['id'];
        }
        else{
            $arr = $this->db->fetchAll($sql2, 2, [$ship_name]);
            if(empty($arr)){
                $isContinue = $this->db->execute($sql4, [$newId, $ship_name, 1]);
                if($isContinue)
                    $result = $newId;
                else
                    Logger::warn('退载重建--新建船公司错误');
            }
            else
                $result = $arr[0]['id'];
        }
        return $result;
    }

    //通过堆场名字获取堆场id
    public function getYardId($yard_name){
        $sql1 = 'SELECT `id` FROM `yard_info` WHERE `yard_name` = ?';
        $sql2 = 'INSERT INTO `yard_info` (`id`, `yard_name`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())';
        $newId = $this->getNewId('yard_info');
        $arr = $this->db->fetchAll($sql1, 2, [$yard_name]);
        $result = 0;
        if(empty($arr)){
            $isContinue = $this->db->execute($sql2, [$newId, $yard_name, 1]);
            if($isContinue)
                $result = $newId;
            else
                Logger::warn('退载重建--新建船公司错误');
        }
        else
            $result = $arr[0]['id'];
        return $result;
    }
    //检查字符串是否含有汉字
    public function isHaveHanzi($str){
        return preg_match("/[\x7f-\xff]/", $str);
    }
    //获取新的id
    public function getNewId($tableName){
        $sql = 'SELECT `id` FROM '.$tableName.' ORDER BY `id` DESC';
        $result = 1;
        $arr = $this->db->fetchOne($sql);
        if(!empty($arr))
            $result = $arr['id'] + 1;
        return $result;
    }
    //获取提单号
    public function getTidanCode($order_freight_id){
        $sql = 'SELECT `tidan_code` FROM `order_freight` WHERE `id` = ?';
        $result = 0;
        $arr = $this->db->fetchOne($sql, 2, [$order_freight_id]);
        if(!empty($arr))
            $result = $arr['tidan_code'];
        return $result;
    }
    //通过 order_freight_id 获取 driver_user_id 及 id
    public function getTuizaiParams($order_freight_id, $type = 0){
        $sql = 'SELECT `id`, `driver_user_id` FROM `order_assign_driver` WHERE `order_freight_id` = ?';
        $result = array();
        $arr = $this->db->fetchAll($sql, 2, [$order_freight_id]);
        if(!empty($arr)){
            foreach($arr as $key => $value){
                if($type == 0)
                    $arr2 = array(
                        'driver_id' => $value['driver_user_id'],
                        'assign_id' => $value['id'],
                        'weburl' => 'man',
                    );
                else
                    $arr2 = array(
                        'driver_id' => $value['driver_user_id'],
                        'assign_id' => $value['id'],
                        'weburl' => 'ORDER_CANCELED',
                    );
                $result = $this->completeTwoArr($result, $arr2);
            }
        }
        return $result;
    }
    //过滤重复数据
    public function completeTwoArr($arr1 = array(), $arr2){
        if(!empty($arr1)){
            $mark = false;
            foreach($arr1 as $key => $value)
                if (strcmp($value['driver_id'], $arr2['driver_id']) == 0) {
                    $mark = true;
                    break;
                }
            if(!$mark)
                $arr1[] = $arr2;
        }
        else
            $arr1[] = $arr2;
        return $arr1;
    }
}