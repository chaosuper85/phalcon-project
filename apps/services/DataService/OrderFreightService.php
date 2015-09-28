<?php
namespace Services\DataService;

use Library\Helper\ObjectHelper;
use Library\Helper\StringHelper;
use Phalcon\Mvc\User\Component;
use OrderFreight;
use Library\Log\Logger;
use Users;

class OrderFreightService extends Component {

    /**  货代创建订单
     * @param int $agentId 货代id
     * @param $yundanCode 运单号
     * @param int $carrierId 承运人id
     * @param $dockCityCode   港口城市
     * @param $importType 进出口类型
     * @param $tidanCode 提单号
     * @param $tixiangdanFileUrl  提箱单URL array
     * @param $adresscontactFileUrls 产状联系单文件URL  array
     * @return order or false
     */
    public function  createOrderFreight( $agentId, $yundanCode, $carrierId, $dockCityCode, $importType, $tidanCode, $tixiangdanFileUrl, $adresscontactFileUrls)
    {
        if( $agentId == $carrierId || empty( $agentId ) || empty( $yundanCode ) || empty( $carrierId ) || empty( $tidanCode ) || empty( $tixiangdanFileUrl ) || empty( $adresscontactFileUrls )  ){
            Logger::warn("createOrderFreight warn: agent:{%s} carTeam:{%s} yudan:{%s} tidan:{%s} tixiangUrl:{%s} addressUrl:{%s} error.");
            return false;
        }
        $orderFreight = new OrderFreight();
        $orderFreight->freightagent_user = $agentId;  // 货代
        $orderFreight->yundan_code       = $yundanCode; // 运单号
        $orderFreight->cock_city_code    = $dockCityCode; // 港口
        $orderFreight->import_type       = intval($importType);   // 进出口类型
        $orderFreight->carrier_userid    = $carrierId ; // 承运人Id
        $orderFreight->addresscontact_file_urls = is_array($adresscontactFileUrls) ? json_encode($adresscontactFileUrls): $adresscontactFileUrls;// 产状联系单文件URL
        $orderFreight->tixiangdan_file_url = $tixiangdanFileUrl; // 提箱单URL json
        $orderFreight->tidan_code = $tidanCode;// 提单号
        $orderFreight->ship_ticket_desc = "";
        $orderFreight->ship_ticket = "";
        $orderFreight->product_name = "";
        $orderFreight->product_desc = "";

        $this->db->begin();
        $ret = false;
        try{
            $ret = $orderFreight->create();
            if( $ret ){
                $ret = $this->createOrderUpdate( $orderFreight->id,array("order_status" =>$this->order_config->order_status_enum->TO_CONFIRM));//   订单状态： 等待承运人 确认接单
                if( $ret ){
                    $ret = $this->OrderSuperService->assignAuto( $orderFreight->id );
                }
            }else{
                Logger::warn(" create order msg:".var_export($orderFreight->getMessages(), true)."  order:".var_export($orderFreight->toArray(), true)."  return:".$ret);
            }
        }catch (\Exception $e){
            Logger::warn("freight:{%s} create order exception:{%s}",$agentId,$e->getMessage());
        }
        if( $ret ){ // true
            $this->db->commit();
            $activitySuccess =  true;
            // 货代创建订单成功，发送短信给 车队
            $this->ActivityLogComponent->noticeCarTeam( $orderFreight->carrier_userid,$orderFreight->freightagent_user );
        }else{
            $this->db->rollback();
            $activitySuccess =  false;
        }
        $this->ActivityLogService->insertActionLog
        (
            $this->constant->ACTION_TYPE->FREIGHT_CREATE_ORDER,
            $this->request->getClientAddress(),
            $agentId,  // reamid
            $this->constant->ACTION_REAM_TYPE->CARGOER,     //reamType
            $carrierId, // targetid
            $this->constant->ACTION_REAM_TYPE->CARTEAM, // target type
            json_encode(array('success' => $activitySuccess, 'dockCityCode' => $dockCityCode))  // json
        );
        return $ret ? $orderFreight : false;
    }

    //完善信息+更新信息
    public function updateOrderFreight($id, $shipName = '', $shipTicket = '', $shipTicketDesc = '', $yardid = 0, $productName = '',
                                       $productDesc = '', $productWeight = 0, $productBoxType = 0, $box20gpCount = 0, $box40gpCount = 0,
                                       $box40hqCount = 0){
        $orderFreight = $this->getOrderFreightByid($id);
        if(empty($orderFreight)){
            Logger::warn("通过id返回的 orderFreight 为空，检查错误!");
            return false;
        }
        $orderFreight->ship_name = $shipName;
        $orderFreight->ship_ticket = $shipTicket;
        $orderFreight->ship_ticket_desc = $shipTicketDesc;
        $orderFreight->yard_id = $yardid;
        $orderFreight->product_name = $productName;
        $orderFreight->product_desc = $productDesc;
        $orderFreight->product_weight = $productWeight;
        $orderFreight->product_box_type = $productBoxType;
        $orderFreight->box_20gp_count = $box20gpCount;
        $orderFreight->box_40gp_count = $box40gpCount;
        $orderFreight->box_40hq_count = $box40hqCount;
        $orderFreight->submit_time = ($cur = (date("Y-m-d h:m:s", time())));
        $orderFreight->update_time = $cur;
        $res = $orderFreight->update();
        Logger::info("update orderFreight: ".$res);
        return $res;
    }

    //返回 信息总条数
    public function getTotalCount($userid, $orderStatus, $mark = null){
        if(empty($mark))
            $sql = 'select id from order_freight where freightagent_user = ?';
        else
            $sql = 'select id from order_freight where carrier_userid = ?';
        $sql .= ($isEmpty = empty($orderStatus)) ? '' : ' and order_status = ?';
        $arr = $isEmpty ? [$userid] : [$userid, $orderStatus];
        return $this->db->query($sql, $arr)->numRows();
    }
    //根据 订单 id 返回唯一一个订单
    public function getOrderFreightByid($id){
        if(empty($id)){
            Logger::warn("id 传递错误, 请检查参数传递！");
            return false;
        }
        $sql = 'SELECT * FROM `order_freight` WHERE `id` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$id]);
        Logger::info(var_export($arr, true));
        return $arr;
    }
    //改变订单状态  mark=null 表示状态随便修改 mark!=null 表示状态不能随便修改
    public function changeOrderStatus($id, $orderStatus, $mark = null){
        if(empty($id)){
            Logger::warn("id 传递错误，请检查参数传递");
            return false;
        }
        $orderFreight = $this->getOrderFreightByid($id);
        if(empty($orderFreight)){
            Logger::warn("根据订单id没找到对应订单，请检查代码！");
            return false;
        }
        if(empty($mark)){
            $orderFreight->order_status = $orderStatus;
            $orderFreight->update();
            Logger::info("mark = null, change order status: ".var_export($orderFreight->getMessages(), true));
            return true;
        }
        if(!$this->isCouldChange($orderFreight, $orderStatus)){
            Logger::warn("请确认订单状态能够改变!");
            return false;
        }
        $orderFreight->order_status = $orderStatus;
        $orderFreight->update();
        Logger::info("mark = null, change order status: ".var_export($orderFreight->getMessages(), true));
        return true;
    }

    //查看订单状态是否在 改变的范围
    public function isCouldChange($orderFreight, $orderStatus){
        $cur_status = $orderFreight->order_status;
        if($orderStatus == ($cur_status + 1))
            return true;
        return false;
    }

    //添加评分
    public function changeOrderPercent($id, $orderTotalPercent){
        $orderFreight = $this->getOrderFreightByid($id);
        if(empty($orderFreight)){
            Logger::warn("更改评分 order不存在，检查代码！");
            return false;
        }
        $orderStatus = $orderFreight[0]['order_status'];
        if($orderStatus != 6){
            Logger::warn('此状态订单不能评价！');
            return false;
        }
        return $this->updateOrderPercent($id, $orderTotalPercent);;
    }
    //更新 order_freight 通过id
    public function updateOrderPercent($id, $orderTotalPercent){
        $sql = 'UPDATE `order_freight` SET `order_status` = ?, `order_total_percent` = ? WHERE `id` = ?';
        $orderStatus = 7;
        return $this->db->execute($sql, [$orderStatus, $orderTotalPercent, $id]);
    }

    //对 订单列表 查询 搜索参数数组进行检查
    public function checkParamsArr($paramsArr = array()){
        if(empty($paramsArr))
            return false;
        $arr = array();
        foreach($paramsArr as $key => $value)
            if(!empty($value))
                $arr[$key] = $value;
        return $arr;
    }
    //通过 ship_name_id 获取 船信息
    public function getShipSomeInfoById($id){
        $sql1 = 'select shipping_companyid, china_name from ship_name where id = ?';
        $sql2 = 'select company_name from shipping_company where id = ?';
        $shipArr = $this->db->query($sql1, [$id])->fetchAll();
        $shipCompanyArr = $this->db->query($sql2, [$shipArr[0]['shipping_companyid']])->fetchAll();
        return $shipCompanyArr[0]['company_name'].$shipArr[0]['china_name'];
    }

    /**
     *  查询订单详情 by id
     * @return OrderFreight
     */
    public function  getByOrderId( $orderId ){
        $order = OrderFreight::findFirst(array(
            "conditions" => " id = ?1",
            "bind"       =>  array( 1 => $orderId ),
        ));
        return $order;
    }
    //通过 carrier_userid 获取 部分订单信息
    public function getSomeInfoByCarrierid($carrierUserid, $orderStatus){
        $sql = 'select id, box_20gp_count, box_40gp_count, box_40hq_count from order_freight where carrier_userid = ? and order_status = ?';
        return $this->db->fetchAll($sql, 2, [$carrierUserid, $orderStatus]);
    }

    /**
     *   订单详细信息 包括 船信息、货物信息、货代、承运方
     */
    public function  orderInfo($orderId, &$result = array())
    {
        $order = $this->getByOrderId($orderId);
        if (empty($order)) {
            $result = array("error_code" => 404, "error_msg" => "您访问的订单详情找不到。");
            return false;
        } else {
            // 委托方 默认货代
            $agent = Users::findFirst("id=$order->freightagent_user");
            $agentUser = \FreightagentUser::findFirst("userid=$order->freightagent_user");
            $agentInfo = array(
                "agent_user_id" => $agent->id,
                "agent_id"      => empty( $agentUser )? "": $agentUser->id,
                "name" => $agent->unverify_enterprisename,
                "contactName" => empty($agent->real_name) ? $agent->contactName : $agent->real_name,
                "contactNumber" => empty($agent->mobile) ? $agent->contactNumber : $agent->mobile,
            );
            // 承运方 默认车队
            $carTeam = Users::findFirst("id=$order->carrier_userid");
            $carTeamUser = \CarTeamUser::findFirst("userid=$order->carrier_userid");
            $carTeamInfo = array(
                "carTeam_user_id" => $carTeam->id,
                "carTeam_id" => empty( $carTeamUser )? "":$carTeamUser->id,
                "name" => $carTeam->unverify_enterprisename,
                "contactName" => empty($carTeam->real_name) ? $carTeam->contactName : $carTeam->real_name,
                "contactNumber" => empty($carTeam->mobile) ? $carTeam->contactNumber : $carTeam->mobile,
            );
            // 订单文件
            $orderFiles = array(
                "tixiang"    => $order->tixiangdan_file_url,
                "chanzhuang" => $order->addresscontact_file_urls,
            );

            // 船信息
            $ship = $this->ShipNameService->getById($order->ship_name_id);
            $company = $this->ShippingCompanyService->getById($order->shipping_company_id);
            // 堆场信息
            $yard = $this->YardInfoService->getById($order->yard_id);
            $shipInfo = array(
                "dock_city_code" => $this->constant->citys[ $order->cock_city_code ]['name'],//
                "tidan_code" => $order->tidan_code,
                "ship_name" => empty( $ship->china_name ) ? $ship->eng_name : $ship->china_name,
                "ship_name_id" => $ship->id,
                "ship_company_name" => empty( $company->china_name ) ? $company->english_name : $company->china_name,
                "ship_company_id" => $company->id,
                "ship_ticket" => $order->ship_ticket, //todo
                "ship_ticket_desc" => $order->ship_ticket_desc,
                "yard_id" => $order->yard_id,
                "yard_name" => empty($yard) ? "" : $yard->yard_name
            );
            // 货物信息
            $productInfo = array(
                "product_name" => $order->product_name,
                "product_desc" => $order->product_desc,
                "product_weight" => $order->product_weight,
                "product_box_type" => $this->order_config->box_type_define[ $order->product_box_type  ],
                // 箱型箱量
                "box_type_number" => [
                    [
                        "type" => 1, // 20GP todo
                        "number" => $order->box_20gp_count,
                    ],
                    [
                        "type" => 2, // 40Gp
                        "number" => $order->box_40gp_count,
                    ],
                    [
                        "type" => 3,//40 HQG
                        "number" => $order->box_40hq_count,
                    ],
                ],
            );
            // 产装地址
            $addressInfo = $this->OrderProductAddressService->addressDetails($orderId);
            //调度信息
            $assignInfo  = $this->OrderAssignDriverService->orderAssignDetails($orderId);

            // 能否重建
            $canRestruct = $this->OrderFreightService->canReconstruct( $orderId );
            $result = array(
                "order_fregiht_id" => $order->id,
                "tidan" => $order->tidan_code,
                "yundan" => $order->yundan_code,
                "can_restruct" => $canRestruct,
                "orderStatus" => $order->order_status,
                "createDate" => StringHelper::strToDate($order->create_time, "Y-m-d"),
                "freight" => $agentInfo,
                "carteam" => $carTeamInfo,
                "order_files" => $orderFiles,
                "address_assign_info" => $assignInfo,
                "order_info" => [
                    "ship_info" => $shipInfo,
                    "product_info" => $productInfo,
                    "address_info" => $addressInfo,
                ]
            );
            return true;
        }
    }

    /**
     *  订单的基本信息 / 委托方 默认货代 /  承运方 默认车队 /  订单文件 / 船信息 / 堆场信息 / 货物信息 / 产装地址 / 调度信息
     */
    public  function  orderBaseInfo( $orderId,$userId,&$result){
        $order = $this->getByOrderId( $orderId );
        if( empty( $order ) ) {
            $result['error_code'] = 404;
            $result['error_msg']  = "您查看的订单找不到。";
            return false;
        }else{
            $isOrderUser = $userId == $order->freightagent_user || $userId == $order->carrier_userid;
            if( !$isOrderUser ) {
                Logger::info("用户：$userId 无权查看订单： $order->id result:" . $isOrderUser);
                $result = array("error_code" => 404, "error_msg" => "您无权查看该订单详情。");
                return false;
            }
            // 委托方 默认货代
            $agent = Users::findFirst("id=$order->freightagent_user");
            $agentInfo = array(
                "id" => $agent->id,
                "name"=> $agent->unverify_enterprisename,
                "contactName"=> empty( $agent->real_name)? $agent->contactName:$agent->real_name,
                "contactNumber" => empty($agent->mobile) ? $agent->contactNumber: $agent->mobile,
            );
            // 承运方 默认车队
            $carTeam = Users::findFirst("id=$order->carrier_userid");
            $carTeamInfo = array(
                "id" => $carTeam->id,
                "name" => $carTeam->unverify_enterprisename,
                "contactName"=> empty( $carTeam->real_name)? $carTeam->contactName  :$carTeam->real_name,
                "contactNumber" => empty($carTeam->mobile) ? $carTeam->contactNumber: $carTeam->mobile,
            );
            // 订单文件
            $orderFiles = array(
                "tixiang"    => is_string($order->tixiangdan_file_url)? $order->tixiangdan_file_url:json_decode($order->tixiangdan_file_url),
                "chanzhuang" => is_string($order->addresscontact_file_urls)? $order->addresscontact_file_urls:json_decode($order->addresscontact_file_urls),
            );

            // 堆场列表
            $yardList = $this->YardInfoService->getAll( $order->cock_city_code );
            $result = array(
                "order_fregiht_id"    => $order->id,
                "tidan"               => $order->tidan_code,
                "yundan"              => $order->yundan_code,
                "can_restruct"        => true,
                "orderStatus"         => $order->order_status,
                "create_date"         => $order->create_time,
                "freight"             => $agentInfo,
                "carteam"             => $carTeamInfo,
                "order_files"         => $orderFiles,
                "product_box_type"    =>  $this->order_config->box_type_define, // 货物箱型
                "box_type_list"       =>  $this->constant->boxType,   // 箱型列表 todo
                "address_assign_info" => array(),
                "order_info"          => array(),
                "yard_info"           => $yardList,
            );
            Logger::info("用户$userId :完成订单信息orderInfo:".var_export($result,true));
            return true;
        }
    }

    //仅仅 通过 id 获取 评分 一个订单 只有一个人评价，他评价输入整数，输出也应该是整数
    public function getOrderTotalPercent($id){
        $sql = 'SELECT order_total_percent FROM `order_freight` WHERE `id` = ?';
        $percent = $this->db->fetchAll($sql, 2, [$id]);
        if(empty($percent)){
            Logger::warn('获取评分出错！');
            return false;
        }
        return $percent[0]['order_total_percent'];
    }

    /**
     *  确认接单 更新
     */
    public function agentConfirmOrder( $orderId , $params = array() ){
        if( empty( $params ) ){
            return ;
        }else{
            $sql  = " update order_freight set  ";
            $data = [];
            foreach( $params as $field => $value ){
                $sql .= " $field =? , ";
                $data[] = $value;
            }
            $sql.=" update_time =NOW(),submit_time=NOW() where id=$orderId ";
            $res = $this->db->execute( $sql, $data);
            Logger::info( " update order result:".$res );
            return $res;
        }

    }

    /**
     *  创建接单 更新
     */
    public function createOrderUpdate( $orderId , $params = array() ){
        if( empty( $params ) ){
            return false;
        }else{
            $sql  = " update order_freight set  ";
            $data = [];
            foreach( $params as $field => $value ){
                $sql .= " $field =? , ";
                $data[] = $value;
            }
            $sql.=" update_time =NOW(),create_time=NOW() where id=$orderId ";
            $res = $this->db->execute( $sql, $data);
            Logger::info( " update order result:".$res );
            return $res;
        }
    }

    public function getById( $orderId ){
        $order = OrderFreight::findFirst(array(
            "conditions"  => " id = ?1",
            "bind"        => array( 1 => $orderId ),
        ));
        return $order;
    }


    /**
     *  检查 提单号 是否存在
     */
    public function checkLadingCodeExist( $ladingCOde ){
        $exist = true ;
        $sql  = " SELECT count(*) as times from order_freight WHERE tidan_code =? ";
        try{
            $res   = $this->db->fetchOne( $sql, 2 ,[ $ladingCOde ]);
            $exist = $res['times'] > 0 ;
        }catch (\Exception $e){
            Logger::warn("checkLadingCodeExist error:{%s}",$e->getMessage());
        }
        return $exist;
    }

    /**
     * 通过订单id 查看是否能 退载重建 返回true能退载重建
     * 如果 订单所有的箱子中 存在 一个箱子 已落箱，订单不能重载 > 4
     *  若果全都是 100，司机app产装完成，待后台车队确认 -->3 说明 状态值都小于3 ,可以重载
     */
    public function  canReconstruct( $orderId ){
        $luoxiang_status    = $this->order_config->box_status_enum->LUOXIANG; // 已落箱
        $allStatus = $this->getAllBoxStatusByOrder( $orderId );
        $flag = true ; // 默认可以
        if( !empty( $allStatus ) ){
            foreach( $allStatus as  $status ){
                if( $status !=100 && $status['box_status'] > $luoxiang_status ){
                    $flag = false;
                    break;
                }
            }
        }
        return $flag;
    }

    public function getAllBoxStatusByOrder( $orderId, $boxId= null ){
        $params = [ $orderId ];
        $result = array();
        $sql = " SELECT  box_status  FROM order_freight_box WHERE order_freight_id =? ";
        if( !empty( $boxId ) ){
            $sql.=" AND id=? ";
            $params[] = $boxId;
        }
        try{
            $result = $this->db->fetchAll( $sql,2,$params);
        }catch (\Exception $e){
            Logger::warn("getAllBoxStatusByOrder:{%s} error msg:{%s}",$orderId,$e->getMessage());
        }
        return $result;
    }
}