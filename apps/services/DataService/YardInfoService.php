<?php

namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Library\Log\Logger;
use YardInfo;

use Overtrue\Pinyin\Pinyin;

class YardInfoService extends Component
{

    /**
     * Get yard list info
     * @return array
     */
    public function getYardInfo()
    {
        $sql = "SELECT yi.id, yi.yard_name, yi.create_time, COUNT(yl.id) AS total ";
        $sql .= "FROM yard_info yi ";
        $sql .= "LEFT JOIN yard_location yl ";
        $sql .= "ON yi.id=yl.yard_id ";
        $sql .= "GROUP BY yi.id ";
        $sql .= "ORDER BY yi.id DESC";

        $res = $this->db->fetchAll($sql);

        Logger::info('res: ' . var_export($res, true));

        return $res;
    }


    public function saveOrUpdateYardInfo($jsonData)
    {
        $ret = array(
            'error_code' => 0,
            'error_msg' => '成功',
        );

        $id = isset($jsonData['yard_id']) ? $jsonData['yard_id'] : null;
        $cock_city_code = $jsonData['cock_city_code'];
        $yard_name = $jsonData['yard_name'];

        $ret = $this::saveYard($id, $cock_city_code, $yard_name);

        if ($ret['error_code'] != 0) {
            return $ret;
        }

        $locations = $jsonData['locations'];

        foreach ($locations as $index => $location) {

            $this->YardLocationService->saveOrUpdateLocation($id,
                $location['location_detail_type'],
                $location['latitude'],
                $location['longitude']
            );
        }

        return $ret;


    }

    /**
     * @param $id
     * @param $cock_city_code
     * @param $yard_name
     * @param $type
     */
    public function saveYard(&$id, $cock_city_code, $yard_name)
    {
        $ret = array(
            'error_code' => 0,
            'error_msg' => '成功',
        );
        if (isset($id)) {
            /// update
            $yard = YardInfo::findFirst(["id = ?1", 'bind' => [1 => $id]]);
            if ($yard) {
                $yard->cock_city_code = $cock_city_code;
                $yard->yard_name = $yard_name;
                $yard->cock_city_code = $cock_city_code;
                $updateRet = $yard->update();
                if (!$updateRet) {
                    Logger::warn('update yard fail:' . var_export($yard->getMessages(), true));
                    $ret['error_code'] = 2;
                    $ret['error_msg'] = '更新堆场信息失败';
                    return $ret;
                }
            } else {
                $ret['error_code'] = 2;
                $ret['error_msg'] = '堆场id不存在';
                return $ret;
            }
        } else {
            //save
            if (!isset($cock_city_code)
                || !isset($yard_name)
            ) {
                $ret['error_code'] = 2;
                $ret['error_msg'] = '参数缺失';
                return $ret;
            }

            $yard = new YardInfo();
            $yard->cock_city_code = $cock_city_code;
            $yard->yard_name = $yard_name;
            $yard->cock_city_code = $cock_city_code;
            $saveRet = $yard->save();
            if (!$saveRet) {
                $ret['error_code'] = 2;
                $ret['error_msg'] = '保存堆场失败';
                return $ret;
            }
            $id = $yard->id;
            $ret['data'] = array('yard_id' => $yard->id);

        }
        return $ret;
    }

    /**
     *
     */
    public function yardInfos($cock_city_code, $yard_name, $create_time_start, $create_time_end, $create_type, $pageHelper)
    {

        $params = array();
        $sql = "select  * from  yard_info    where  1=1   ";
        $sqlCount = "select count(id)  from  yard_info   where  1=1  ";

        if (isset($yard_name)) {
            $sql .= " and yard_name   like ? ";
            $sqlCount .= "  and yard_name like ? ";
            $params[] = '%' . $yard_name . '%';
        }


        if (isset($cock_city_code)) {
            $sql .= " and cock_city_code =? ";
            $sqlCount .= "  and cock_city_code =? ";
            $params[] = $cock_city_code;
        }

        if (isset($create_type)) {
            $sql .= " and type =? ";
            $sqlCount .= "  and type =? ";
            $params[] = $create_type;
        }

        if (isset($create_time_start)) {
            $sql .= "  and create_time >=? ";
            $sqlCount .= " and create_time >=? ";
            $params[] = $create_time_start;
        }

        if (isset($create_time_end)) {
            $sql .= "  and create_time <=? ";
            $sqlCount .= " and create_time <=? ";
            $params[] = $create_time_end;
        }
        $sql .= " order by update_time desc ";
        $startRow = $pageHelper->getStartRow();
        $pageSize = $pageHelper->getPageSize();
        $sql .= "  limit $startRow ,$pageSize";
        $yardinfo = $this->db->fetchAll($sql, 2, $params);

        $yardsWithLocationArr = array();

        if (count($yardinfo) > 0) {
            foreach ($yardinfo as $index => $yard) {
                $locations = $this->YardInfoService->locations($yard['id']);
                $yard['locations'] = $locations;
                $yardsWithLocationArr[] = $yard;
            }

        }
        $countRet = $this->db->query($sqlCount, $params)->fetch();
        $counts = $countRet[0];
        $pageHelper->setData($yardsWithLocationArr)->setTotalRows($counts);
        return $pageHelper;

    }

    public function locations($yard_id)
    {

        if (empty($yard_id)) {
            Logger::warn("yard_id 传递错误，请检查代码!");
            return false;
        }

        $sql = 'select * from yard_location where yard_id = ?  limit 100 ';
        $yardlocations = $this->db->query($sql, [$yard_id])->fetchAll();
        return $yardlocations;


    }


    public function modifyYardInfo($id, $name)
    {
        $id = intval($id);
        $yard = \YardInfo::findFirst($id);
        if (!$yard) {
            Logger::info('modifyShip 无此船id');
            return false;
        }

        $yard->yard_name = $name;
        $ret = $yard->update();

        Logger::info('modifyship info:' . var_export($yard->getMessages(), true));
        return $ret;
    }

    public function getIdByCityName($cityName)
    {
        if (empty($cityName)) {
            Logger::warn("cityName 传递错误，请检查代码!");
            return false;
        }
        $sql = 'select id from yard_info where cock_city_code = ?';
        $yard = $this->db->query($sql, [$cityName])->fetchAll();
        if (count($yard) > 1) {
            Logger::warn("一个cityName 对应多个 id，请检查逻辑!");
            return false;
        }
        return $yard[0]['id'];
    }

    //通过 id 获取堆场部分信息
    public function getSomeInfoById($id)
    {
        $sql = 'select cock_city_code from yard_info where id = ?';
        return $this->db->query($sql, [$id])->fetchAll();
    }

    public function getById($id)
    {
        $yard = YardInfo::findFirst("id=$id");
        return $yard;
    }

    /**
     *  搜索堆场
     */
    public function searchYard($name, $cityCode ="tianjin", $limit = 10)
    {
        $likeStr = '%'.$name.'%';
        $sql = "select id as yard_id,yard_name,cock_city_code as dock_city_code from yard_info where ( yard_name like ? OR pinyin LIKE ? ) AND cock_city_code =? limit $limit";
        $result = $this->db->fetchAll($sql,2,[ $likeStr,$likeStr,$cityCode ]);
        return $result;
    }

    public function  createIfNUllByName($name, $type = 1)
    {
        $yard = YardInfo::findFirst(array(
            "conditions" => "yard_name =?1",
            "bind" => [ 1 => $name ]
        ));
        if (empty($yard)) {
            $yard = $this->create($name, $type);
        }
        return $yard;
    }

    public function create($name, $type = 0)
    {
        $yard = new YardInfo();
        $yard->yard_name = $name;
        $setting = [
            'delimiter' => '',
            'accent'    => false,
        ];
        $yard->pinyin = Pinyin::trans( $name ,$setting);
        $yard->cock_city_code = "tianjin";
        $yard->type = $type;
        $res = $yard->create();
        Logger::info(" create Yard Error msg:" . var_export($yard->getMessages(), true));
        return $yard;
    }

    public function getAll( $dockCityCode ){
        $sql    = " SELECT id as yard_id ,yard_name,cock_city_code FROM yard_info WHERE cock_city_code='$dockCityCode' ";
        $result = array();
        try{
            $result  = $this->db->fetchAll( $sql );
        }catch (\Exception $e){
            Logger::warn('getAlldock error msg:{%s}',$e->getMessage());
        }
        return  $result;
    }


}
