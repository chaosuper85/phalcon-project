<?php


namespace Services\DataService;


use TblProvince;
use Library\Helper\ArrayHelper;
use \Phalcon\DiInterface;
use Library\Helper\LocationHelper;

use Library\Log\Logger;

use Phalcon\Mvc\User\Component;


/**
 * {getAllByCodeid 通过citycode来查位置}谁是作者，出来加点注释=。= todo  暴露的接口太多了不知道用哪个找了半天
 *
 * Class CityService
 * @package Services\DataService
 */
class CityService extends Component
{


    public function getProvince()
    {
        $sql = 'SELECT id, codeid, parentid, cityName FROM tbl_province WHERE codeid < 99 AND  codeid > 1';

        $res = $this->db->fetchAll($sql);

        Logger::info('res: ' . var_export($res, true));

        return $res;
    }


    public function getSubLocation($id)
    {
        $sql1 = 'SELECT id, codeid FROM `tbl_province` WHERE `id` = ?';
        $sql2 = 'SELECT id, cityName FROM `tbl_province` WHERE `parentid` = ?';
        $arr1 = $this->db->fetchOne($sql1, 2, [$id]);
        $arr2 = array();
        Logger::info('res: ' . var_export($arr1, true));
        if(!empty($arr1)) {
            $arr2 = $this->db->fetchAll($sql2, 2, [$arr1['codeid']]);
            Logger::info('res: ' . var_export($arr2, true));
        }
        return $arr2;
    }

    /**
     * 获取所有地理位置数据
     */
    public function getAllLocation()
    {

        $sql = 'SELECT id, codeid, parentid, cityName FROM tbl_province';

        $res = $this->db->fetchAll($sql);

        Logger::info('res: ' . var_export($res, true));

        return $res;
    }


    /**
     * 获取所有地理位置数据(省和市)
     */
    public function getProvinceCitys(){
        $sql = 'SELECT `id`, `codeid`, `parentid`, `cityName` FROM `tbl_province` WHERE `codeid` % 100 = 0';
        $res = $this->db->fetchAll($sql, 2);
        Logger::info('res: ' . var_export($res, true));
        return $res;
    }


    public function getCityByCityCode($cityCode)
    {

        $sql = "SELECT id, codeid, parentid, cityName FROM tbl_province WHERE  codeid = '" . $cityCode . "'";

        if (is_numeric($cityCode)) {
            $res = $this->db->fetchAll($sql);

            Logger::info('res: ' . var_export($res, true));

            return $res;
        } else {
            return null;
        }

    }

    /**
     * 根据 codeid 返回 cityName 及 parentid
     */
    public function getCityAndParentByCodeid($codeid)
    {
        if (is_numeric($codeid) && $codeid != 0) {
            $sql = "select cityName, parentid from tbl_province where codeid = '$codeid'";
            $res = $this->db->query($sql)->fetch();
            if (!empty($res))
                return array(
                    'cityName' => $res['cityName'],
                    'parentid' => $res['parentid'],
                );
            return false;
        }
        return false;
    }

    public function getAllName($result)
    {
        $j = count($result) - 1;
        $allName = '';
        if (!empty(strstr($result[$j]['cityName'], '市')))
            $j--;
        while ($j >= 0) {
            $allName .= $result[$j]['cityName'];
            $j--;
        }
        return $allName;
    }

    //根据codeid返回全部parent名字
    public function getAllByCodeid($codeid)
    {
        $result = array();
        $i = 0;
        while (($res = $this->getCityAndParentByCodeid($codeid)) != false) {
            $result[$i] = $res;
            $i++;
            $codeid = $res['parentid'];
        }
        return $this->getAllName($result);
    }

    public function getFullNameById($id)
    {
        $fullName = "";
        $city = TblProvince::findFirst(array(
            "conditions" => "id = ?1",
            "bind" => array(1 => $id),
        ));
        try {
            if (!empty($city)) {
                $fullName = $this->getAllByCodeid($city->codeid);
            }
        } catch (\Exception $e) {
            Logger::info("补全详细地址信息 error:" . $e->getMessage());
        }
        return $fullName;
    }
    public function  getById( $id ){
       $sql = "SELECT * FROM tbl_province WHERE id=? ";
       $res = $this->db->fetchOne( $sql, 2,[ $id ]);
       return $res;
    }

    //通过 获取各 市县 经纬度
    public function getAllCityFullName(){
        $sql = 'SELECT `id`, `codeid`, `parentid`, `cityName` FROM `tbl_province` WHERE `codeid` % 100 != 0 AND `id` < 4000';
        $arr = $this->db->fetchAll($sql, 2);
        $result = array();
        if(!empty($arr)){
            foreach($arr as $key => $value)
                $result[] = array(
                    'id' => $value['id'],
                    'cityName' => $this->getFullNameById($value['id']),
                );
        }
        return $result;
    }

    //获取各省 市的经纬度
    public function getLocation($cityNameArr = array()){
        $result = array();
        foreach($cityNameArr as $key => $value){
            $location = LocationHelper::getLocationByAdress($value['cityName']);
            $arr = explode(',', $location);
            if(!empty($location))
                $result = array(
                    'id' => $value['id'],
                    /*'longitude' => $arr[0],
                    'latitude' => $arr[1],*/
                    'location' => $location,
                );
        }
        return $result;
    }


    public function addLocation(){
        $result = false;
        $arr = $this->getAllCityFullName();
        if(!empty($arr)){
            $arr1 = $this->getLocation($arr);
            //$result = $this->updateTblProvince($arr1);
        }
        return $arr1;
    }
}