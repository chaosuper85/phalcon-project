<?php   namespace Services\DataService;

use Monolog\Logger;
use Phalcon\Mvc\User\Component;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class PositionService extends Component {

    /**
     * 从数据库获得 全部cityName 和 id
     */
    public function getCityName() {
        $sql = 'select id, cityName from tbl_province';
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * 将一个 字符串 与 数组中 数据进行比较
     *  有且仅有一个 返回id 并且将 Array中数据删除
     *  没有或者有多个 返回特定字符 Array中信息不变
     */
    public function comWithArr($str, $arr = array()) {
        $markCycle = 0;
        $cur_id = 0;
        foreach($arr as $value)
            if(strcmp($str, $value['cityName']) == 0){
                $cur_id = $value['id'];
                $markCycle++;
            }
        if($markCycle==0)
            return 0;
        elseif($markCycle==1)
            return $cur_id;
        return 2;
    }

    /**
     * 将一条信息插入数据库
     */
    public function updateOnePosition($id, $cur_city){
        $longitude = $cur_city['longitude'];
        $latitude = $cur_city['latitude'];
        $sql1 = "update tbl_province set longitude = ? where id = ?";
        $this->db->query($sql1,[$longitude,$id]);
        $sql2 = "update tbl_province set latitude = ? where id = ?";
        $this->db->query($sql2,[$latitude,$id]);
        if(!empty($cur_city['mark'])){
            $newCityName = $cur_city['newCityName'];
            $sql3 = "update tbl_province set cityName = ? where id = ?";
            $this->db->query($sql3, [$newCityName, $id]);
        }
        return true;
    }
    /**
     * 读取文档txt
     */
    public function readTxt(){
        $arr = $this->getCityName();
        $result = array();
        $i = 0;
        $path = '/Users/xiaoliu/Downloads/经纬度word0.txt';
        $file = fopen($path, 'r');
        while(!empty($buf = fgets($file))){
            $buf_part = $this->changeStringToPart($buf);
            $cur_city = array(
                'cityName' => $buf_part[2],
                'longitude' => $buf_part[3],
                'latitude' => $buf_part[4],
            );
            $mark = $this->comWithArr($cur_city['cityName'], $arr);
            if($mark == 0 || $mark == 2) {
                $cur_city['mark'] = $mark;
                $result[$i] = $cur_city;
                $i++;
            }
        }
        fclose($file);
        return $result;
    }
    /**
     * 将Result 写入 txt文档
     */
    public function writeIntoTxt($result = array()){
        if(!empty($result)){
            $str = '';
            foreach($result as $value){
                $cityName = $value['cityName'];
                $longitude = $value['longitude'];
                $latitude = $value['latitude'];
                $mark = $value['mark'];
                $add_str = $cityName.' '.$longitude.' '.$latitude.' '.$mark."\n";
                $str .= $add_str;
            }
            $open=fopen("/Users/xiaoliu/Downloads/经纬度_匹配不到.txt","a" );
            fwrite($open,$str);
            fclose($open);
        }
    }
    /**
     * 对一个字符串进行分解
     */
    public function changeStringToPart($str){
        if(!empty($str)){
            $str_arr = explode(' ', $str);
            return $str_arr;
        }
        return false;
    }
    /**
     * 将修改的 插入数据库
     */
    public function readToMySqlLast(){
        $path = '/Users/xiaoliu/Downloads/经纬度_匹配不到.txt';
        $file = fopen($path, 'r');
        while(!empty($buf = fgets($file))){
            $buf_part = $this->changeStringToPart($buf);
            $cur_city = array(
                'id' => $buf_part[3],
                'longitude' => $buf_part[1],
                'latitude' => $buf_part[2]
            );
            if(!empty($buf_part[5])) {
                $cur_city['mark'] = 1;
                $cur_city['newCityName'] = $buf_part[0];
            }
            $this->updateOnePosition($cur_city['id'], $cur_city);
        }
        return false;
    }


    //  获取 市辖 信息及 codeid
    public function getShiXia(){
        $sql = "select codeid from tbl_province where cityName = '市辖区'";
        return $this->db->query($sql)->fetchAll();
    }
    //删除字符串后两位
    public function delLastTwo($str){
        return substr($str, 0, strlen($str) - 2);
    }
    //通过 codeid 获取城市 longitude 及 latitude
    public function getCityLongitude($codeid){
        $sql = "select longitude, latitude from tbl_province where codeid = ?";
        $arr = $this->db->query($sql, [$codeid])->fetchAll();
        return $arr;
    }
    //写入 市辖 sql语句
    public function writeIntoShiXia($codeid, $arr = array()){
        $latitude = $arr['latitude'];
        $longitude = $arr['longitude'];
        $sql1 = "update tbl_province set longitude = ?,latitude = ? where codeid = ?";
        $this->db->query($sql1, [$longitude, $latitude, $codeid]);
        return true;
    }
    //给 市辖 填充信息 总函数
    public function writeToShiXiaMain(){
        $arr = $this->getShiXia();
        foreach($arr as $key => $value){
            foreach($value as $key1 => $value1)
                $value_del = $this->delLastTwo($value1);
            $arr2 = $this->getCityLongitude($value_del);
            foreach($arr2 as $key2 => $value2)
                $this->writeIntoShiXia($value['codeid'], $value2);
        }
        return true;
    }
    public function test(){
        //$arr = $this->readTxt();
        //return $this->writeIntoTxt($arr);
        //$this->readToMySqlLast();
        //$longitude = 'aaaa';
        //$id = 1;
        $a = $this->writeToShiXiaMain();
        //$sql = "update tbl_province SET longitude = ? where id = ? ";
        //$this->db->query($sql);
        //public function update($table, $fields, $values, $whereCondition = null, $dataTypes = null);
        //$this->db->query($sql,[$longitude,$id]);
        return true;
    }

    public function readNewTxt(){
        $sql = 'INSERT INTO `tbl_province` (`created_at`, `updated_at`, `codeid`, `parentid`, `cityName`) VALUES (NOW(), NOW(), ?, ?, ?)';
        $path = '/Users/gongyao/Downloads/2015年最新全国行政区域划分.txt';
        $file = fopen($path, 'r');
        $db = $this->db;
        $result = false;
        $db->begin();
        try{
            $isContinue = false;
            while (!empty($buf = fgets($file))){
                $buf_part = $this->strToArray($buf);
                if(!empty($buf_part)) {
                    $cityName = $buf_part['name'];
                    $codeid = $buf_part['number'];
                    if($codeid % 10000 == 0)
                        $parentid = 0;
                    elseif($codeid % 100 == 0)
                        $parentid = ((int)($codeid / 10000)) * 10000;
                    else
                        $parentid = ((int)($codeid / 100)) * 100;
                    $isContinue = $db->execute($sql, [$codeid, $parentid, $cityName]);
                }
                if(!$isContinue)
                    break;
            }
            if(!$isContinue){
                //Logger::warn('插入数据库不成功!');
                $db->rollback();
            }
            $db->commit();
            $result = true;
        }catch(\Exception $e){
            $db->rollback();
        }
        if(!$result)
            $db->rollback();
        fclose($file);
        return $result;
    }
    //对 一行 文字进行 操作
    public function strToArray($str){
        $result = array();
        if(!empty($str))
            $result = array(
                'number' => substr($str, 0, 6),
                'name' => trim(substr($str, 6)),
            );
        return $result;
    }
    //对没有 县 的市增加 ‘市辖区’
    public function addShiXia(){
        $sql1 = 'SELECT `codeid` FROM `tbl_province`';
        $sql2 = 'SELECT `id` FROM `tbl_province` WHERE `parentid` = ?';
        $sql3 = 'INSERT INTO `tbl_province` (`created_at`, `updated_at`, `codeid`, `parentid`, `cityName`) VALUES (NOW(), NOW(), ?, ?, ?)';
        $result = true;
        $db = $this->db;
        try{
            $db->begin();
            $arr = $db->fetchAll($sql1, 2);
            if(!empty($arr))
                foreach($arr as $key => $value){
                    $codeid = $value['codeid'];
                    if($codeid % 100 == 0 && $codeid % 10000 != 0){
                        $arr1 = $db->fetchAll($sql2, 2, [$codeid]);
                        if(empty($arr1)){
                            $isContinue = $db->execute($sql3, [$codeid + 1, $codeid, '市辖区']);
                            if(!$isContinue) {
                                $result = false;
                                break;
                            }
                        }
                    }
                }
            if(!$result)
                $db->rollback();
            $db->commit();
        }catch (\Exception $e){
            $db->rollback();
        }
        return $result;
    }
}