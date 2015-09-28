<?php
namespace Services\LogicService;

use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Users;
use OrderProductTime;
use OrderFreightBox;
use OrderProductAddress;


/**
 * 解析 订单的 日志 action_log action_type =17
 *     订单日志数据格式定义 在 @resource OrderLogHelper
 *  1 增加产装地址，
 * 2 增加分派司机，
 * 3 增加箱号，铅封号，
 * 4 修改产装地址，
 * 5 修改分派司机，
 * 6 修改箱号，铅封号，
 * 7 增加产装时间，
 * 8 修改产装时间，
 * 9 删除产装地址，
 * 10 删除产装时间，
 */
class OrderLogService extends Component
{
    public function  getOrderLogRecords($orderId, &$data = array())
    {
        $type = $this->constant->ACTION_TYPE->ORDER_MODIFY_HISTORY;
        $records = $this->ActivityLogService->listByRealmIdAndTargetIdAndActionType($type, $orderId);
        try {
            if (!empty($records)) {
                foreach ($records as $record) {
                    $userId = $record['reamId'];
                    $user = Users::findFirst("id=$userId");
                    $tempData = array(
                        "date" => StringHelper::strToDate($record['created_at'], "Y-m-d H:i"),
                        "user" => empty(trim($user->real_name)) ? $user->unverify_enterprisename : $user->real_name,
                        'operateType' => "",
                        "content"     => "",
                    );
                    $contentArr = json_decode($record['jsonContent'], true);
                    $content = "";
                    switch ( $record['child_action_type'] ) {
                        case 1: //1 增加产装地址，
                            $tempData['operateType'] = "增加产装地址";
                            $content = $this->getAddressContent($contentArr,  $tempData['operateType'] );
                            break;

                        case 2 : //  添加分派司机
                            $tempData['operateType'] = "新增分派信息";
                            $content = $this->getAssignContent($contentArr,  $tempData['operateType'] );
                            break;

                        case 3:  // 增加箱号、铅封号
                            $tempData['operateType'] = "增加箱号、铅封号";
                            $content = $this->getBoxCodeAndSeal( $contentArr, $tempData['operateType']);
                            break;

                        case 4 : // 修改产装地址
                            $tempData['operateType'] = "修改产装地址";
                            $content = $this->getAddressContent($contentArr, $tempData['operateType']);
                            break;

                        case 5 :      // 修改司机

                            break;

                        case 6 : // 修改箱号铅封号
                            $tempData['operateType'] = "修改箱号铅封号";
                            $content = $this->getBoxCodeAndSeal( $contentArr, $tempData['operateType']);
                            break;

                        case 7 : //  7 增加产装时间，
                            $tempData['operateType'] = "增加产装时间";
                            $content = $this->getProDateContent( $contentArr, $tempData['operateType']);
                            break;

                        case 8: //  8 修改产装时间，
                            $tempData['operateType'] = "修改产装时间";
                            $content = $this->getProDateContent( $contentArr, $tempData['operateType']);
                            break;

                        case 9 : // 9 删除产装地址

                            break;

                        case 10: //  10 删除产装时间，
                            $tempData['operateType'] = "删除产装时间";
                            $content = $this->getProDateContent( $contentArr, $tempData['operateType']);
                            break;

                        default:
                            break;
                    }
                    if( !empty( $content ) ){
                        $tempData['content'] = $content;
                        $data[] = $tempData;
                    }
                }
                return true;
            }
        } catch (\Exception $e) {
            Logger::warn("$orderId lookistory error msg:" . $e->getMessage());
            $data['error_code'] = 200001;
            $data['error_msg'] = "系统异常";
            return false;
        }
    }

    public function  getOrderLogsWithPage(){

    }


    /**
     *          "provinceid"=> $provinceid,
                "product_address_id" => $proAddressId,
                "cityid"    => $cityid,
                "townid"    => $townid,
                "address"   => $address,
                "orderid"   => $orderId,
                "contact_name" => $contactName,
                "contact_mobile" => $contactMobile,
     *
     */
    public function  getAddressContent( $contentArr,$operate )
    {
        $townid   = $contentArr['townid'];
        $address  = $contentArr['address'];
        $fullName = $this->CityService->getFullNameById($townid);
        $addressDetail = $fullName . $address;
        Logger::info($addressDetail . "=" . $fullName . "+" . $addressDetail);
        $contactName= $contentArr['contact_name'];
        $contactNum = $contentArr['contact_mobile'];
        $content = " %s：%s；联系人：%s；联系方式：%s";
        $params  = [ $operate ,$addressDetail,$contactName,$contactNum ];
        $content = vsprintf( $content, $params);
        return $content;
    }


    private  function  getBoxCodeAndSeal( $contentArr , $operate ){
        $box_code   = $contentArr[ 'box_code' ];
        $box_ensupe = $contentArr['box_ensupe'];
        $params = [ $operate, $box_code, $box_ensupe];
        $content = "%s：箱号：%s，铅封号：%s";
        $content = vsprintf( $content,$params);
        return $content;
    }


    /**
     *      "orderid"                 => $orderId,
            "product_supply_time"    => $product_supply_time,
            "product_supply_time_id" => $product_supply_time_id,
            "product_address_id"=> $product_address_id,
     */
    public function  getProDateContent( $contentArr, $type ){
        $time   = $contentArr[ 'product_supply_time' ];
        $address = $this->OrderProductAddressService->getDetailAddressById( $contentArr['product_address_id']);
        $params = [ $address, $type , $time ];
        $content = "给地址：%s；%s：%s";
        $content = vsprintf( $content,$params);
        return $content;
    }


    /**
     *  分派信息
     *       'box_id' => $boxId,
             'driver_id' => $driverId,
            'product_supply_time_id' => $timeId,
            'address_id' => $addressId,
     */
    public function getAssignContent( $contentArr ,$type ){
        $boxId     = $contentArr['box_id'];
        $driverId  = $contentArr['driver_id'];
        $timeId    = $contentArr['product_supply_time_id'];
        $addressId = $contentArr['address_id'];
        $user = \Users::findFirst("id='$driverId'");
        $driver   = $this->DriverService->getByUserId( $driverId );
        $driverName = "";
        $carNo = "";
        if( !empty( $driver )  &&  !empty($user) ){
            $driverName = empty( $driver->driver_name ) ? $user->real_name : $driver->driver_name;
            $carNo = $driver->car_number;
        }
        $time = OrderProductTime::findFirst("id=$timeId");
        $proTime = empty( $time )? "已被删除" : StringHelper::strToDate($time->product_supply_time);
        $address = $this->OrderProductAddressService->getDetailAddressById( $addressId );
        $proAdd  = empty( $address )? "地址已删除": $address;
        // 更新分派信息

        $content = "%s：司机：%s,车牌号：%s,集装箱：%s,产装时间：%s,地址：%s";
        $content = vsprintf( $content , [ $type, $driverName, $carNo,$boxId,$proTime , $proAdd ]);
        return $content;
    }





}