<?php
namespace Services\DataService;

use Library\Helper\ObjectHelper;
use Phalcon\Mvc\User\Component;
use Library\Log\Logger;


class Tbl_province extends Component{
    public function getOrderFreightIdByCityName($cityName){ //通过 名字获取 orderFreightId
        $cityId = $this->getIdByCityName($cityName);
        return $this->getOrderFreightIdListById($cityId[0]['id']);
    }

    //通过城市名字  获取城市id
    public function getIdByCityName($cityName){
        $sql = 'select id from tbl_province where cityName = ?';
        return $this->db->query($sql, [$cityName])->fetchAll();
    }
    //根据城市id 获取 orderFreightId集合
    public function getOrderFreightIdListById($id){
        $sql1 = 'select order_freight_id from order_product_address where address_provinceid = ?';
        $sql2 = 'select order_freight_id from order_product_address where address_cityid = ?';
        $sql3 = 'select order_freight_id from order_product_address where address_townid = ?';
        $list1 = $this->db->query($sql1, [$id])->fetchAll();
        $list2 = $this->db->query($sql2, [$id])->fetchAll();
        $list3 = $this->db->query($sql3, [$id])->fetchAll();
        $arr = array();
        $return_list = empty($list1) ? (empty($list2) ? (empty($list3) ? false : $list3) : $list2) : $list1;
        if(!empty($return_list))
            foreach($return_list as $key => $value)
                $arr[] = $value['order_freight_id'];
        return $arr;
    }
    //检查 元素 是否在 数组中
    public function isHaveInArr($value_, $arr){
        if(empty($arr)){
            Logger::warn('OrderFreightId数组为空,检查代码');
            return false;
        }
        foreach($arr as $key => $value)
            if(strcmp($value_, $value) == 0)
                return true;
        return false;
    }
    //将数组 转换成 字符串
    public function toStringByArr($arr){
        if(empty($arr)){
            Logger::warn('OrderFreightId数组为空,检查代码');
            return false;
        }
        $str = '';
        foreach($arr as $key => $value)
            $str .= ($value.',');
        return substr($str, 0, strlen($str) - 1);
    }

    //利用 经度 来获取 地址集合
    public function getLocationArr($longitude){
        $sql = 'SELECT codeid, latitude FROM `tbl_province` WHERE `longitude` = ?';
        return $this->db->query($sql, [$longitude])->fetchAll();
    }
    //根据 经度 来获取 相似地址集合
    public function getSimilarLocationArr($longitude){
        $locationArr = $this->getLocationArr($longitude);
        $i = 0.01;
        while(empty($locationArr)){
            $longitudeParam1 = $longitude - $i;
            $longitudeParam2 = $longitude + $i;
            $i = $i + 0.01;
            $locationArr = $this->mergeArr($this->getLocationArr($longitudeParam1), $this->getLocationArr($longitudeParam2));
        }
        return $locationArr;
    }
    //两个数组合并
    public function mergeArr($arr1, $arr2){
        $arr = array();
        if(!empty($arr1))
            $arr = $arr1;
        if(empty($arr) && !empty($arr2))
            $arr = $arr2;
        if(!empty($arr) && !empty($arr2))
            foreach($arr2 as $key => $value)
                $arr[] = $value;
        return $arr;
    }
    //以 locationArr、latitude为参数 返回准确地址
    public function getExactLocationByArr($longitudeArr, $latitude){
        $mark = 0;
        $i = 0.00;
        while($mark == 0 && $i < 0.8){
            foreach($longitudeArr as $key => $value)
                if(strcmp($value['latitude'],($latitude + $i)) == 0){
                    $mark = $value['codeid'];
                    return $mark;
                }
            if($i == 0)
                $i = 0.01;
            elseif($i > 0)
                $i = $i * -1;
            else
                $i = -1 * $i + 0.01;
        }
        return $mark;
    }
    //以 经度、维度  为参数返回准确的地址 codeid
    public function getExactLocation_($longitude, $latitude){
        $locationArr = $this->getSimilarLocationArr($longitude);
        return $this->getExactLocationByArr($locationArr, $latitude);
    }
    public function getExactLocation($longitude, $latitude){
        $i = 0;
        while(($mark = $this->getExactLocation_($longitude + $i, $latitude)) == 0){
            if($i == 0)
                $i = $i + 0.01;
            elseif($i > 0)
                $i = -1 * $i;
            else
                $i = -1 * $i + 0.01;
        }
        return $mark;
    }
}