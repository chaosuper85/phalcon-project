<?php   namespace Services\DataService;

use Phalcon\Mvc\User\Component;
class GetTbl_provinceService extends Component {
    //对 一行字符进行 分解
    public function changeStringToPart($str){
        if(!empty($str)){
            $str_arr = explode(' ', $str);
            return $str_arr;
        }
        return false;
    }
    //检查 一个字符串 是否在数组中
    public function isHave($str, $strArr = array()){
        $mark = 0;
        if(!empty($strArr))
            foreach($strArr as $key => $value)
                if(strcmp($str, $value) == 0)
                    $mark++;
        return $mark;
    }
    //先获取最高 行政数组 省一级的数组
    public function getProvinceArr(){
        $path = '/Users/xiaoliu/Downloads/经纬度word0.txt';
        $file = fopen($path, 'r');
        $provinceArr = array();
        $i = 1;
        while(!empty($buf = fgets($file))){
            $buf_part = $this->changeStringToPart($buf);
            $province = $buf_part[0];
            if($this->isHave($province, $provinceArr) == 0) {//加入 数组
                if ($i < 10)
                    $key = '0'.$i;
                else
                    $key = $i;
                $provinceArr[$key] = $province;
                $i++;
            }
        }
        fclose($path);
        return $provinceArr;
    }
    //获取 str 在 strArr 中的key值
    public function getKey($str, $strArr = array()){
        foreach($strArr as $key => $value)
            if(strcmp($str, $value) == 0)
                return $key;
        return -1;
    }

    //城市数组 中是否存在
    public function isHaveCity($city, $cityArr = array()){
        $mark = 0;
        if(!empty($cityArr))
            foreach($cityArr as $key => $value)
                if(strcmp($city, $value['city']) == 0)
                    $mark++;
        return $mark;
    }

    //读取市一级的 行政数组
    public function getCityArr() {
        $path = '/Users/xiaoliu/Downloads/经纬度word0.txt';
        $file = fopen($path, 'r');
        $provinceArr = $this->getProvinceArr();
        $cityArr = array();
        while (!empty($buf = fgets($file))) {
            $buf_part = $this->changeStringToPart($buf);
            $province = $buf_part[0];
            $provinceid = $this->getKey($province, $provinceArr);
            $city = $buf_part[1];
            if($this->isHaveCity($city, $cityArr) == 0){
                $cityArr[] = array(
                    'city' => $city,
                    'parentid' => $provinceid
                );
            }
        }
        fclose($path);
        return $cityArr;
    }

    //计算 城市数组的codeid
    public function computeCityCodeid(){
        $cityArr = $this->getCityArr();
        if(empty($cityArr))
            return '计算城市数组错误';
        $pre_parentid = 0;
        $cityNum = 1;
        foreach($cityArr as $key => $value){
            $cur_parentid = $value['parentid'];
            if($cur_parentid != $pre_parentid) {
                $pre_parentid = $cur_parentid;
                $cityNum = 1;
            }
            if($cityNum < 10)
                $addKey = '0'.$cityNum;
            else
                $addKey = $cityNum;
            $codeid = $cur_parentid.$addKey;
            $cityArr[$key]['codeid'] = $codeid;
            $cityNum++;
        }
        return $cityArr;
    }
    //将 cityArr 写入 txt
    public function writeCityArr(){
        $str = '';
        $cityArr = $this->computeCityCodeid();
        foreach($cityArr as $key => $value){
            $cityName = $value['city'];
            $parentid = $value['parentid'];
            $codeid = $value['codeid'];
            $add_str = $cityName.' '.$codeid.' '.$parentid."\n";
            $str .= $add_str;
        }
        $open=fopen("/Users/xiaoliu/Downloads/cityArr.txt","a" );
        fwrite($open,$str);
        fclose($open);
        return '完成';
    }
    //获取 市 一级别的codeid
    public function getCityCodeid($city, $cityArr = array()){
        if(!empty($cityArr))
            foreach($cityArr as $key => $value)
                if(strcmp($city, $value['city']) == 0)
                    return $value['codeid'];
        return -1;
    }
    //完成 县区 一级别的数组生成
    public function getCountyArr(){
        $path = '/Users/xiaoliu/Downloads/经纬度word0.txt';
        $file = fopen($path, 'r');
        $cityArr = $this->computeCityCodeid();
        if(empty($cityArr))
            return "城市数组没有生成";
        $countArr = array();
        while (!empty($buf = fgets($file))) {
            $buf_part = $this->changeStringToPart($buf);
            $city = $buf_part[1];
            $count = $buf_part[2];
            $longitude = $buf_part[3];
            $latitude = $buf_part[4];
            if($this->isHaveCity($count, $cityArr) != 0)
                $count = '市辖区';
            $cityid = $this->getCityCodeid($city, $cityArr);
            $countArr[] = array(
                    'count' => $count,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'parentid' => $cityid
            );
        }
        fclose($path);
        return $countArr;
    }
    //计算 县区 一级别的 codeid
    public function computeCountCondeid(){
        $countArr = $this->getCountyArr();
        if(empty($countArr))
            return "县区 数组没有得到";
        $pre_parentid = 0;
        $count_num = 1;
        foreach($countArr as $key => $value){
            $cur_parentid = $value['parentid'];
            if($pre_parentid != $cur_parentid){
                $pre_parentid = $cur_parentid;
                $count_num = 1;
            }
            if($count_num < 10)
                $addKey = '0'.$count_num;
            else
                $addKey = $count_num;
            $codeid = $cur_parentid.$addKey;
            $countArr[$key]['codeid'] = $codeid;
            $count_num++;
        }
        return $countArr;
    }
    //将 countArr 写入 txt文档中
    public function writeCountArr(){
        $str = '';
        $countArr = $this->computeCountCondeid();
        foreach($countArr as $key => $value){
            $countName = $value['count'];
            $codeid = $value['codeid'];
            $parentid = $value['parentid'];
            $longitude = $value['longitude'];
            $latitude = $value['latitude'];
            $add_str = $countName.' '.$codeid.' '.$parentid.' '.$longitude.' '.$latitude."\n";
            $str .= $add_str;
        }
        $open=fopen("/Users/xiaoliu/Downloads/countArr.txt","a" );
        fwrite($open,$str);
        fclose($open);
        return '完成';
    }
    //将县级别不带 ‘市’ 的插入数据库
    public function insertProvinceArr(){
        $provinceArr = $this->getProvinceArr();
        foreach($provinceArr as $key => $value) {
            $cur = date('Y-m-d h:i:s', time());
            $cityName = $value;
            $codeid = $key;
            $parentid = 0;
            $sql = "insert into tbl_province(created_at, updated_at, codeid, parentid, cityName) values(?, ?, ?, ?, ?)";
            $this->db->query($sql, [$cur, $cur, $codeid, $parentid, $cityName]);
        }
        return '插入成功!';
    }
    //将 cityArr 插入数据库
    public function insertCityArr(){
        $cityArr = $this->computeCityCodeid();
        foreach($cityArr as $key => $value){
            $cur = date('Y-m-d h:i:s', time());
            $cityName = $value['city'];
            $codeid = $value['codeid'];
            $parentid = $value['parentid'];
            $sql = "insert into tbl_province(created_at, updated_at, codeid, parentid, cityName) values(?, ?, ?, ?, ?)";
            $this->db->query($sql, [$cur, $cur, $codeid, $parentid, $cityName]);
        }
        return "插入成功";
    }
    //将 countArr 插入数据库
    public function insertCountArr(){
        $countArr = $this->computeCountCondeid();
        foreach($countArr as $key => $value){
            $cur = date('Y-m-d h:i:s', time());
            $cityName = $value['count'];
            $codeid = $value['codeid'];
            $parentid = $value['parentid'];
            $longitude = $value['longitude'];
            $latitude = $value['latitude'];
            $sql = "insert into tbl_province(created_at, updated_at, codeid, parentid, cityName, longitude, latitude) values(?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [$cur, $cur, $codeid, $parentid, $cityName, $longitude, $latitude]);
        }
        return "插入成功";
    }

    //三合一
    public function insert(){
        $this->insertProvinceArr();
        $this->insertCityArr();
        $this->insertCountArr();
        return '数据成功导入数据库';
    }
}