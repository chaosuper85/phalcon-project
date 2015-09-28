<?php
namespace Services\DataService;


use Library\Helper\OrderLogHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use OrderAssignDriver;
use OrderFreightBox;
use OrderBoxTimeline;
use OrderFreightTimeline;
use OrderFreight;

/**
 *  派车详情
 */
class OrderAssignDriverService extends Component
{

    /**
     *   车队 派车
     */
    public function  create($orderId, $productAddrId, $productTimeId, $caseId, $driverUserId)
    {

        $assign_status = 1;

        $conditions = "id = :id:";
        $parameters = array(
            "id" => $caseId,
        );

        $orderFreightBox = OrderFreightBox::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));
        if( !empty($orderFreightBox) && !empty($orderFreightBox->box_code) && !empty($orderFreightBox->box_ensupe) )
        {
            $assign_status = 2;
        }

        $assignDriver = new OrderAssignDriver();
        $assignDriver->order_freight_id = $orderId;                 // 订单号
        $assignDriver->order_product_addressid = $productAddrId;    // 产装地址
        $assignDriver->order_product_timeid = $productTimeId;       //产状时间
        $assignDriver->order_freight_boxid = $caseId;           // 箱子
        $assignDriver->driver_user_id = $driverUserId;          // 司机
        $assignDriver->assign_status = $assign_status;          //根据箱号铅封号，是否填写，决定初始的分派状态
        $ret = $assignDriver->save();
        if( !$ret ){
            Logger::warn("create assignDriver msg:%s  details:%s ", var_export($assignDriver->getMessages(), true), var_export($assignDriver->toArray(), true));
        } else { // 添加 分派司机log
            $data = OrderLogHelper::addAssignDriver( $assignDriver->id,$orderId,$driverUserId,$caseId,$productAddrId,$productTimeId);
            $this->ActivityLogComponent->orderHistroyLog( $data );
        }
        return $ret ? $assignDriver : $ret;
    }


    /**
     * 判断司机能否修改，（条件，如果箱子有一条分派已产装，则不能修改）
     */
    public function  driverChangeable( $order_freight_id, $order_freight_boxid )
    {

        $assigns = OrderAssignDriver::find(array(
            "conditions" => "order_freight_id = ?1 and order_freight_boxid=?2 ",
            "bind" => array(1 => $order_freight_id, 2 => $order_freight_boxid),
        ));

        if( empty($assigns) ){
            Logger::warn("  order_freight_id: %s, order_freight_boxid: %s, assigns not found  ",  $order_freight_id, $order_freight_boxid);
            return false;
        }

        foreach( $assigns as $key => $assign  ){
                if(  $assign->assign_status >= '3' ){
                    return false;
                }
        }

        return true;
    }


    /**
     *   车队 修改 派车 的产装地址或者产装时间
     */
    public function  changeAddressOrTime($assignid, $driver_user_id, $productAddrId, $productTimeId, $driver_changeable)
    {

        $assigninfo = OrderAssignDriver::findFirst(array(
            "conditions" => "id = ?1",
            "bind" => array(1 => $assignid),
        ));

        if( !empty($assigninfo) ){
            //如果 $driver_changeable 为false, 则 $driver_user_id 不能修改
            if( !$driver_changeable && $driver_user_id != $assigninfo->driver_user_id ){
                Logger::warn(" driver can not change, driver_user_id:%s, assigninfo->driver_user_id:%s, driver_changeable:%s ", $driver_user_id, $assigninfo->driver_user_id, $driver_changeable);
                return false;
            }

            //如果提交的$driver_user_id,$productAddrId,$productTimeId,都一样，则不做修改操作
            if(  $assigninfo->driver_user_id == $driver_user_id
                && $assigninfo->order_product_addressid == $productAddrId
                && $assigninfo->order_product_timeid == $productTimeId ){
                Logger::info("assignid:%s,driver_user_id:%s,productAddrId:%s,productTimeId:%s not changed, return true",$assignid,$driver_user_id, $productAddrId, $productTimeId);
                return true;
            }

            $assigninfo->driver_user_id = $driver_user_id;
            $assigninfo->order_product_addressid = $productAddrId;
            $assigninfo->order_product_timeid = $productTimeId;
            $ret = $assigninfo->update();
            if( !$ret ){
                Logger::warn("assigninfo->update failed");
                return false;
            }else{
                // 修改分派信息
                $data = OrderLogHelper::updateAssignDriver($assigninfo->id, $assigninfo->order_freight_id,$assigninfo->driver_user_id,$assigninfo->order_freight_boxid,$productAddrId,$productTimeId);
                $this->ActivityLogComponent->orderHistroyLog( $data );
            }
        }else{
            Logger::warn("assignid %s, not found", $assignid);
            return false;
        }

        return true;
    }

    /**
     *  查询 订单的派单信息数组
     */
    public function  listByOrderId($orderId)
    {
        $assigns = OrderAssignDriver::find(array(
            "conditions" => "order_freight_id = ?1 and enable =1 ",
            "bind" => array(1 => $orderId),
            "order" => "create_time "
        ));
        return count($assigns) ? $assigns : array() ;
    }

    /**
     *  派车详情 by orderId
     * @return array
     */
    public function orderAssignDetails($orderId)
    {
        $assignDriversInfo = array(); // 派单信息
        $this->assignDriverDetails( $orderId,$assignDriversInfo);
        Logger::info("派单信息：".var_export( $assignDriversInfo,true));
        $allBox = $this->OrderBoxService->listAllBoxByOrderId($orderId);
        $data   = array();
        if (!empty($allBox)) {
            foreach ($allBox as $box) {
                // 默认可以修改，与箱子 相关的所有 地址、时间已经产装，该 所有的记录不能修改（ 箱子、地址、时间、分派的司机 ）
                $assignTemp = array(
                    "box_id" => $box->id,
                    "box_ensupe" => $box->box_ensupe,
                    "box_can_change" => true, // 默认可以修改,
                    "all_can_change" => true, //
                    "box_type" => $box->box_size_type,
                    "box_status" => $box->box_status,
                    "box_code" => $box->box_code,
                    "box_weight" => $box->box_weight,
                    "driver_info" => array("id" => "", "name" => "", "mobile" => "", "car_number" => ""),
                    "address_info" => array()
                );
                if (!empty($assignDriversInfo) && array_key_exists($box->id, $assignDriversInfo)) {
                   // 存在箱子的 派单信息
                    $assignDetails = $assignDriversInfo[$box->id];
                    $assignTemp['driver_info']  = $assignDetails['driver_info'];
                    $assignTemp['address_info'] = $assignDetails['address_info'];
                    $staAll = $this->getStatusMinByOrderAndBox( $orderId, $box->id );
                    if( $staAll > 2 ){ // 箱子的所有的分派任务状态 >=3 说明都已经产装，箱子\地址\时间 信息都不能修改
                        $assignTemp['all_can_change'] = false;
                    }
//                    $staBox = $this->getStatusMaxByOrderAndBox( $orderId,$box->id );
//                    if( $staBox > 2 ){ // 箱子的所有的分派任务状态 中有一个 >=3 ,箱子的 箱号 铅封号不能修改
//                        $assignTemp['box_can_change'] = false ;
//                    }
                }
                $data[] = $assignTemp;
            }
        }
        return  $data;
    }


    /**
     * 一笔订单  同一个箱子、同一个司机 => 可能多地产装
     */
    public function listAssignMoreAddress($orderId, $boxId, $driverId)
    {
        $assigns = OrderAssignDriver::find(array(
            "conditions" => "order_freight_id = ?1 and order_freight_boxid=?2 and driver_user_id=?3 and enable=1",
            "bind" => array(1 => $orderId, 2 => $boxId, 3 => $driverId),
        ));

        return count( $assigns ) > 0 ? $assigns : array() ;
    }

    // 通过 order_freight_id 获取司机部分信息
    public function getDriverIdByOrderFreightId($orderFreightId){
        $sql = 'select driver_user_id from order_assign_driver where order_freight_id = ?';
        return $this->db->query($sql, [$orderFreightId])->fetchAll();
    }

    //通过 user_id 获取 车牌号
    public function getCarNumberByUserid($userid){
        $sql = 'select car_number from tb_driver where userid = ?';
        return $this->db->query($sql, [$userid])->fetchAll();
    }

    //通过 user_id 获取司机名字及手机号
    public function getNameNumberById($id){
        $sql = 'select contactName, contactNumber from users where id = ?';
        return $this->db->query($sql, [$id])->fetchAll();
    }

    //通过 order_freight_id 获取司机信息
    public function getDriverInfoByOrderFreightId($orderFreightId){
        $user = array();
        $driverIdList = $this->getDriverIdByOrderFreightId($orderFreightId);
        if(count($driverIdList) != 1){
            Logger::warn('一个订单对应多个司机!,检查逻辑');
            return $user;
        }
        $carNumberList = $this->getCarNumberByUserid($driverIdList[0]['driver_user_id']);
        $nameNumberList = $this->getNameNumberById($driverIdList[0]['driver_user_id']);
        if(count($carNumberList) != 1 && count($nameNumberList) != 1){
            Logger::warn('一个userid 对应多个车牌号及名字,检查逻辑');
            return $user;
        }
        $user['carNumber'] = $carNumberList[0]['car_number'];
        $user['name'] = $nameNumberList[0]['contactName'];
        $user['number'] = $nameNumberList[0]['contactNumber'];
        return $user;
    }

    /**
     *  查询订单的 某一个箱子 的分派信息
     */
    public function boxAssignInfo($orderId, $boxId)
    {
        $assigns = OrderAssignDriver::find(array(
            "conditions" => "order_freight_id = ?1 and order_freight_boxid=?2 and enable=?3 ",
            "bind" => array(1 => $orderId, 2 => $boxId, 3=>1),
        ));
        return  count($assigns) ? $assigns : array() ;
    }

    /*
     * 删除一个分派信息
     */
    public function delBoxAssignInfo( $assignid )
    {
        $assigninfo = OrderAssignDriver::findFirst(array(
            "conditions" => "id = ?1",
            "bind" => array(1 => $assignid),
        ));

        if( !empty($assigninfo) ){
            $assigninfo->enable = 0;
            $ret = $assigninfo->save();
            if( !$ret ){
                Logger::warn("assigninfo->delete failed");
                return false;
            }else{
                $data = OrderLogHelper::delAssignDriver( $assigninfo->id,$assigninfo->order_freight_id,$assigninfo->driver_user_id,$assigninfo->order_freight_boxid,$assigninfo->order_product_addressid,$assigninfo->order_product_timeid);
                $this->ActivityLogComponent->orderHistroyLog( $data );
            }
        }else{
            Logger::warn("assignid %s, not found", $assignid);
            return false;
        }

        return true;
    }


    // 增加修改，前的校验
    public function saveBoxAssignPreCheck( $boxId )
    {

        $conditions = "id = :id:";
        $parameters = array(
            "id" => $boxId,
        );

        $orderFreightBox = OrderFreightBox::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        if( !empty($orderFreightBox) && $orderFreightBox->box_status >= '3' ){
            Logger::warn("orderFreightBox,boxid:%s can not change assign", $boxId);
            return false;
        }

        return true;
    }

    //添加箱号，铅封号， 是否箱子的分派，已提箱完成，检查，满足条件则修改
    public function saveBoxAssignCompleteCheck($userId, $orderId, $boxId )
    {

        $conditions = "id = :id: ";
        $parameters = array(
            "id" => $boxId,
        );

        $orderFreightBox = OrderFreightBox::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        if( empty($orderFreightBox) || empty($orderFreightBox->box_code) || empty($orderFreightBox->box_ensupe) ) {
            return true;
        }

        $conditions = "order_freight_id = :order_freight_id: AND order_freight_boxid = :order_freight_boxid:  AND enable = 1 ";
        $parameters = array(
            "order_freight_id" => $orderId,
            "order_freight_boxid" => $boxId
        );

        $orderAssignDriverRes = OrderAssignDriver::find(array(
            $conditions,
            "bind" => $parameters
        ));

        foreach( $orderAssignDriverRes as $key => $orderAssignDriver )
        {
            if( $orderAssignDriver->assign_status == '1'
                && !empty($orderAssignDriver->driver_user_id)
                && !empty($orderAssignDriver->order_product_addressid)
                && !empty($orderAssignDriver->order_product_timeid)
            )
            {
                    $orderAssignDriver->assign_status = '2';
                    $ret = $orderAssignDriver->save();
                    if( !$ret ) {
                        Logger::warn("orderAssignDriver->save return false");
                        return false;
                    }
            }
        }

        return true;
    }

    /*
     * 订单的箱子分派完成，需要做的逻辑处理
     */
    public function orderBoxAssignComplete($userId, $orderId, $boxId )
    {
        // 如果$orderId, $boxId的状态为待提箱，
        // 并且  箱号，铅封号 填写完成，
        // 并且  所有的都  分派了司机和装货地址，时间，则修改为  提箱完成。
        $conditions = "order_freight_id = :order_freight_id: AND order_freight_boxid = :order_freight_boxid:  AND enable = 1 ";
        $parameters = array(
            "order_freight_id" => $orderId,
            "order_freight_boxid" => $boxId
        );

        $orderAssignDriverRes = OrderAssignDriver::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        if( !empty($orderAssignDriverRes) ){

            if( !empty($orderAssignDriverRes->driver_user_id)
                && !empty($orderAssignDriverRes->order_product_addressid)
                && !empty($orderAssignDriverRes->order_product_timeid)
            )
            {

                $conditions = "id = :id:";
                $parameters = array(
                    "id" => $boxId,
                );

                //更新box的状态，已提箱，待产装。
                $orderFreightBox = OrderFreightBox::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if(  !empty( $orderFreightBox )
                    && $orderFreightBox->box_status == '1'
                    && !empty($orderFreightBox->box_code)
                    && !empty($orderFreightBox->box_ensupe) )
                {
                    $orderFreightBox->box_status = '2';
                    $ret = $orderFreightBox->save();

                    if( $ret ){

                        //记录箱子的timeline，
                        $orderBoxTimeline = new OrderBoxTimeline();
                        $orderBoxTimeline->order_freight_id = $orderId;
                        $orderBoxTimeline->order_freight_boxid = $boxId;
                        $orderBoxTimeline->boxline_type = $this->order_config->box_status_enum->TO_CHANZHUANG;
                        $orderBoxTimeline->verify_ream_type = $this->order_config->verify_ream_type->TIXIANG;
                        $orderBoxTimeline->verify_ream_id = $userId;
                        $orderBoxTimeline->location_msg = '';
                        $orderBoxTimeline->jsonContent = '';
                        $ret = $orderBoxTimeline->save();
                        if( !$ret ){
                            Logger::warn("orderBoxTimeline->save return false");
                            return false;
                        }

                        //如果 订单的箱子都提箱完成，则修改订单的状态
                        $ret = $this->OrderAssignDriverService->orderAssignComplete($userId, $orderId );
                        if( !$ret ){
                            $this->db->rollback();
                            Logger::warn("orderAssignComplete return false");
                            return false;
                        }


                    }else{
                        Logger::warn( 'orderAssignDriverRes->save error: '.var_export($orderAssignDriverRes->getMessages(),true) );
                        return false;
                    }

                }
            }
        }

        return true;
    }

    /*
    * 订单的分派完成，检查和需要做的逻辑处理
     */
    public function orderAssignComplete($userId, $orderId )
    {

        //如果$orderId, 的所有箱子id,$boxId的状态为提箱完成。则修改订单的状态为
        $conditions = "order_freight_id = :order_freight_id:";
        $parameters = array(
            "order_freight_id" => $orderId,
        );

        //先找订单的所有箱子，再看箱子的分派情况

        $orderFreightBoxRes = OrderFreightBox::find(
            array(
                $conditions,
                "bind" => $parameters
            )
        );

        if( count($orderFreightBoxRes) == 0 ){
            Logger::info("orderId:%s,orderFreightBoxRes has no record", $orderId);
            return true;
        }

        $complete = true;
        foreach( $orderFreightBoxRes as $orderFreightBox ){
            //几个箱子都分派完成，则订单扭转
            if( $orderFreightBox->box_status == '1' ){
                $complete = false;
                break;
            }

            //有一个箱子未分派完成，则不更改
        }

        /*
         * 查询订单，满足条件下修改状态
         */
        if( $complete == true )
        {
            $conditions = "id = :order_freight_id:";
            $parameters = array(
                "order_freight_id" => $orderId,
            );

            $orderFreightRes = OrderFreight::findFirst(array(
                $conditions,
                "bind" => $parameters
            ));

            if( empty($orderFreightRes) ){
                Logger::warn('OrderFreight::findFirst: return empty. id: '.var_export($orderId,true));
                return false;
            }

            $orderFreightRes->order_status = '4';
            $ret = $orderFreightRes->save();
            if( !$ret ){
                //修改状态出错
                Logger::warn('orderFreightRes->save error: '.var_export($orderFreightRes->getMessages(),true));
                return false;
            }else{
                //记录订单的timeline
                $orderFreightTimeline = new OrderFreightTimeline();
                $orderFreightTimeline->order_freight_id = $orderId;
                $orderFreightTimeline->ordertimeline_type = '1';
                $orderFreightTimeline->verify_ream_type = $this->order_config->verify_ream_type->TIXIANG;
                $orderFreightTimeline->verify_ream_id = $userId;
                $orderFreightTimeline->jsonContent = '';
                $ret = $orderFreightTimeline->save();
                if( !$ret )
                {
                    Logger::warn('orderFreightTimeline->save: return: '.var_export($orderFreightTimeline->getMessages(),true));
                    return false;
                }
            }
        }
        return true;
    }


    /**
     *   检查 已分配的 时间 和 地址 是否可以修改 (已经 产装的 地址 和 时间 不可修改 即 status > 2 )
     * @return  false 不可修改
     *          true  可以修改
     */
    public  function  canBeUpdated( $orderId, $productAddressId, $timeId = 0 ){
        $status = $this->order_config->assign_status_enum->TO_CHANZHUANG;// 待产装
        $res  = $this->countByAddressIdAndTimeIdAndStatus( $orderId, $productAddressId , $status, $timeId);
        Logger::info(" 产装时间：%s 地址：%s 已经产装记录:%s", $timeId,$productAddressId,$res);
        if( $res > 0 ){ // 存在 不可以修改
            Logger::warn(" 产装时间：%s 地址：%s 已经产装记录:%s 不可修改。", $timeId,$productAddressId,$res);
            return false ;
        }else{
            return true ;
        }
    }

    /**
     * @param $productAddressId 产装地址
     * @param $timeId   时间
     * @param $status 分派状态
     */
    public  function  countByAddressIdAndTimeIdAndStatus( $orderId, $productAddressId,$status ,$timeId = 0 ){
        $sql    = " select count(*) as times from order_assign_driver where order_freight_id =? AND order_product_addressid = ?  and assign_status > ? and enable= 1 ";
        $params = [ $orderId, $productAddressId , $status ];
        if( !empty( $timeId ) ){
            $sql.= " and order_product_timeid = ? ";
            $params [] = $timeId;
        }
        $res = $this->db->fetchAll( $sql,2, $params );
        return $res[0]['times'];
    }


    /**
 *   派单司机详情
 */
    public function  assignDriverDetails($orderId, &$assignDriversInfo = array())
    {
        $assignList = $this->listByOrderId($orderId);//
        if (!empty($assignList)) { //存在派单信息
            foreach ($assignList as $assign) {
                $assignTemp = array("box_id" => "", "driver_info" => array(), "address_info" => array());
                // 产装地址
                $productAddress = $this->OrderProductAddressService->getProductAddressById($assign->order_product_addressid);
                // 产状时间
                $productDate = $this->OrderProductTimeService->getProductTimeById($assign->order_product_timeid);
                // 详细地址
                $fullName = $this->CityService->getFullNameById($productAddress->address_townid);
                $addressDetail = $fullName . $productAddress->address_detail;
                Logger::info($addressDetail . "=" . $fullName . "+" . $productAddress->address_detail);
                $addressInfo = array(
                    "assign_id" => $assign->id,
                    "product_address_id" => $assign->order_product_addressid,
                    "product_time_id" => $assign->order_product_timeid,
                    'box_address' => $productAddress->address_detail,
                    "box_address_detail" => $addressDetail,
                    'box_time' => $productDate->product_supply_time,
                    'assign_status' => $assign->assign_status //todo
                );
                //同一个箱子  只能有一个司机，可能有多个产装信息，只要其中 有一个 已经产装完成（ >2 ），则 该司机信息 不能修改；
                if (array_key_exists($assign->order_freight_boxid, $assignDriversInfo)) { //存在,说明 一个箱子 有多个地址
                    $boxId = $assign->order_freight_boxid;
                    $assignDriversInfo[$boxId]['address_info'][] = $addressInfo;
                    if ($assign->assign_status > 2) { // 存在 司机的 一次派单 已经产装
                        $assignDriversInfo[$boxId]['driver_info']['driver_can_change'] = false;
                        Logger::info("订单：%s,司机：%s,箱子：%s,产装地：%s,时间：%s 分配状态：%s，司机信息不能修改。", $orderId, $assign->driver_user_id, $assign->order_freight_boxid, $assign->order_product_addressid, $assign->order_product_timeid, $assign->assign_status);
                    }
                } else { // 补全 箱子信息
                    $assignTemp["address_info"][] = $addressInfo;
                    $assignTemp['box_id'] = $assign->order_freight_boxid;
                    $driver = $this->DriverService->getByUserId($assign->driver_user_id);
                    $user = \Users::findFirst("id='$assign->driver_user_id'");
                    $assignTemp['driver_info'] = array(
                        "id" => $user->id,
                        "name" => empty($driver->driver_name) ? $user->real_name : $driver->driver_name,
                        "mobile" => $user->mobile,
                        "car_number" => $driver->car_number,
                        "driver_can_change" => $assign->assign_status > 2 ? false : true,
                    );
                    $assignDriversInfo[$assign->order_freight_boxid] = $assignTemp;
                }
            }
        }
    }



    /**  一  orderId 不空  boxId 为 null 查询 一个订单 所有箱子状态的 最小值
     *   二  都不空 查询  箱子的所有分配记录 状态的最小直
     * @param $orderId
     * @param $boxId
     */
    public function getStatusMinByOrderAndBox( $orderId, $boxId = null ){
        $params = [];
        if( empty( $boxId ) ){
             $sql =" select IFNULL(MIN(box_status ),0) as status from order_freight_box WHERE  order_freight_id = ?  ";
            $params[] = $orderId;
        }else{
            $sql = " select IFNULL(MIN(assign_status ),0) as status  from order_assign_driver WHERE  order_freight_id =? AND order_freight_boxid =? AND enable=1 ";
            $params =[ $orderId, $boxId ];
        }
        $res = $this->db->fetchOne( $sql,2,$params );
        $status = intval( $res['status'] );
        Logger::info("订单：%s,箱子：%s ,最小状态值：%s",$orderId,$boxId,$status);
        return $status;
    }

    /**   1 只有 orderId =>查询订单 所有的箱子状态的 最大值；
     *    2 都有， 查询订单的 某一个箱子 所有分派任务状态的 最大值；
     * @param  $orderId
     * @param  $boxId
     * @return int
     */
    public function getStatusMaxByOrderAndBox( $orderId, $boxId = null ){
        $params = [];
        if( empty( $boxId ) ){
            $sql =" select IFNULL( MAX (box_status ),0) as status from order_freight_box WHERE  order_freight_id = ?  ";
            $params[] = $orderId;
        }else{
            $sql = " select IFNULL( MAX(assign_status ),0) as status  from order_assign_driver WHERE  order_freight_id =? AND order_freight_boxid =? ";
            $params =[ $orderId, $boxId ];
        }
        $res = $this->db->fetchOne( $sql,2,$params );
        $status = intval( $res['status'] );
        Logger::info("订单：%s,箱子：%s ,最小状态值：%s",$orderId,$boxId,$status);
        return $status;
    }







}