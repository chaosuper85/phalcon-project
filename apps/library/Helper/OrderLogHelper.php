<?php
namespace Library\Helper;

/**
 *      订单日志Helper 格式化 订单操作记录
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
 * 11 删除分派信息,
 *
 * 被操作的对象类型
 *  '2' => '货代',
    '1' => '车队',
    '3' => '司机',
    '4' => '订单',
    '5' => '系统自动',
    '6' => '其他',
    '7' => '集装箱',
    '8' => '产装地址',
 *
 *
 */
class OrderLogHelper
{

    private static $actionType = 0; // 订单的 操作类型
    private static $objectType = 0; // 被操作的 对象
    private static $data = array();
    private static $orderId = 0;


    /**
     *  1 增加产装地址
     */
    public static function addProAddress($orderId, $proAddressId = "", $provinceid = "", $cityid = "", $townid = "", $address = "", $contactName = "", $contactMobile = "")
    {
        self::$data = array(
            "provinceid" => $provinceid,
            "product_address_id" => $proAddressId,
            "cityid" => $cityid,
            "townid" => $townid,
            "address" => $address,
            "orderid" => $orderId,
            "contact_name" => $contactName,
            "contact_mobile" => $contactMobile,
        );
        self::$actionType = 1;
        self::$orderId = $orderId;
        self::$objectType = 8;
        return self::getLogData();
    }

    /**
     *  2增加分派司机
     */
    public static function addAssignDriver($assignId,$orderId, $driverId, $boxId, $addressId, $timeId)
    {
        self::$data = array(
            'assign_id' => $assignId,
            'box_id' => $boxId,
            'driver_id' => $driverId,
            'product_supply_time_id' => $timeId,
            'address_id' => $addressId,
            "orderid" => $orderId
        );
        self::$actionType = 2;
        self::$orderId = $orderId;
        self::$objectType = 3;
        return self::getLogData();
    }

    /**
     *  3 增加箱号，铅封号，
     */
    public static function  addBoxCodeAndSeal($orderId, $boxId, $boxCode, $seal)
    {
        self::$data = array(
            "box_id" => $boxId,
            'box_code' => $boxCode,
            'box_ensupe' => $seal,
            "orderid" => $orderId,
        );
        self::$actionType = 3;
        self::$orderId = $orderId;
        self::$objectType = 7;
        return self::getLogData();
    }

    /**
     *  4 修改产装地址
     */
    public static function updateProAddress($orderId, $proAddressId = "", $provinceid = "", $cityid = "", $townid = "", $address = "", $contactName = "", $contactMobile = "")
    {
        self::$data = array(
            "provinceid" => $provinceid,
            "product_address_id" => $proAddressId,
            "cityid" => $cityid,
            "townid" => $townid,
            "address" => $address,
            "orderid" => $orderId,
            "contact_name" => $contactName,
            "contact_mobile" => $contactMobile,
        );
        self::$actionType = 4;
        self::$orderId = $orderId;
        self::$objectType = 8;
        return self::getLogData();
    }

    /**
     *   5 修改分派司机，
     */
    public static function updateAssignDriver($assignId,$orderId, $driverId, $boxId, $addressId, $timeId)
    {
        self::$data = array(
            'assign_id' => $assignId,
            'box_id'    => $boxId,
            'driver_id' => $driverId,
            'product_supply_time_id' => $timeId,
            'address_id' => $addressId,
            "orderid" => $orderId
        );
        self::$actionType = 5;
        self::$orderId = $orderId;
        self::$objectType = 3;
        return self::getLogData();
    }


    /**
     *    6 修改箱号，铅封号，
     */
    public static function updateBoxCodeAndSeal($orderId, $boxId, $boxCode, $boxSeal)
    {
        self::$data = array(
            "box_id" => $boxId,
            'box_code' => $boxCode,
            'box_ensupe' => $boxSeal,
            "orderid" => $orderId,
        );
        self::$actionType = 6;
        self::$orderId = $orderId;
        self::$objectType = 7;
        return self::getLogData();
    }

    /**
     *   7 增加产装时间，
     */
    public static function addProDate($orderId, $product_supply_time, $product_supply_time_id = 0, $product_address_id = 0)
    {
        self::$data = array(
            "orderid" => $orderId,
            "product_supply_time" => $product_supply_time,
            "product_supply_time_id" => $product_supply_time_id,
            "product_address_id" => $product_address_id,
        );
        self::$actionType = 7;
        self::$orderId = $orderId;
        self::$objectType = 9;
        return self::getLogData();
    }

    /**
     *  8 修改产装时间，
     */
    public static function updateProDate($orderId, $product_supply_time, $product_supply_time_id = 0, $product_address_id = 0)
    {
        self::$data = array(
            "orderid" => $orderId,
            "product_supply_time" => $product_supply_time,
            "product_supply_time_id" => $product_supply_time_id,
            "product_address_id" => $product_address_id,
        );
        self::$actionType = 8;
        self::$orderId = $orderId;
        self::$objectType = 9;
        return self::getLogData();
    }

    /**
     *   9 删除产装地址，
     */
    public static function delProAddress($orderId, $proAddressId = "", $provinceid = "", $cityid = "", $townid = "", $address = "", $contactName = "", $contactMobile = "")
    {
        self::$data = array(
            "provinceid" => $provinceid,
            "product_address_id" => $proAddressId,
            "cityid" => $cityid,
            "townid" => $townid,
            "address" => $address,
            "orderid" => $orderId,
            "contact_name" => $contactName,
            "contact_mobile" => $contactMobile,
        );
        self::$actionType = 9;
        self::$orderId = $orderId;
        self::$objectType = 8;
        return self::getLogData();
    }


    /**
     *  10 删除产装时间，
     */
    public static function delProDate($orderId, $product_supply_time, $product_supply_time_id = 0, $product_address_id = 0)
    {
        self::$data = array(
            "orderid" => $orderId,
            "product_supply_time" => $product_supply_time,
            "product_supply_time_id" => $product_supply_time_id,
            "product_address_id" => $product_address_id,
        );
        self::$actionType = 10;
        self::$orderId = $orderId;
        self::$objectType = 9;
        return self::getLogData();
    }

    /**
     *   11  删除分派信息 司机，
     */
    public static function delAssignDriver($assignId,$orderId, $driverId, $boxId, $addressId, $timeId)
    {
        self::$data = array(
            'assign_id' => $assignId,
            'box_id'    => $boxId,
            'driver_id' => $driverId,
            'product_supply_time_id' => $timeId,
            'address_id' => $addressId,
            "orderid" => $orderId
        );
        self::$actionType = 5;
        self::$orderId = $orderId;
        self::$objectType = 3;
        return self::getLogData();
    }




    public static function  getLogData()
    {
        $data = array(
            'targetReamId'      => self::$orderId, //  order
            'targetReamType'    => self::$objectType, // box  or  address or driver
            'json_content'      => self::$data,
            'child_action_type' => self::$actionType, //
        );
        return $data;
    }
}