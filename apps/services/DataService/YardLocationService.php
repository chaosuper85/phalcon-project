<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/12
 * Time: 上午11:48
 */

namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Library\Log\Logger;

class YardLocationService extends Component
{

    /***
     * 总体类型返回详细类型
     * @param $intType
     *1  重车 进口
     *2  重车 出口
     *3  空车 进口
     *4  空车 出口
     *5  中心
     */
    public function getDetailLocationType($intType)
    {
        $detailType = array(
            'yard_location_type' => 0,
            'yard_car_type' => 0,
            'yard_degree_type' => 0,
        );
        if ($intType == 5) {
            $detailType['yard_degree_type'] = $this->constant->yard_degree_type->center;
            return $detailType;
        }
        $detailType['yard_car_type'] = ($intType > 2) ? $this->constant->yard_car_type->empty_car : $this->constant->yard_car_type->full_car;
        $detailType['yard_location_type'] = ($intType % 2 == 1) ? $this->constant->yard_location_type->entrance : $this->constant->yard_location_type->exit;
        return $detailType;
    }

    /**
     * 根据详细类型返回总体类型
     * @param $detailType
     * @return int
     */
    public function getLocationTypeByDetail($detailType)
    {
        $intLocationType = 0;
        if ($detailType['yard_degree_type'] == intval($this->constant->yard_degree_type->center)) {
            return 5;
        }
        if ($detailType['yard_car_type'] == intval($this->constant->yard_car_type->empty_car)) {
            $intLocationType += 2;
        }
        if ($detailType['yard_location_type'] == intval($this->constant->yard_location_type->entrance)) {
            $intLocationType += 1;
        } else {
            $intLocationType += 2;
        }
        return $intLocationType;
    }

    /**
     * check location if is existed
     * @param int $yard_id
     * @param int $location_type
     * @param int $car_type
     * @param int $degree_type
     * @return array
     */
    public function checkLocationInfo($yard_id, $location_type = 0, $car_type = 0, $degree_type = 0)
    {
        $condition = array("yard_id = '" . $yard_id . "'");
        if ($location_type && $car_type) {
            $condition[] = "location_type = '" . $location_type . "'";
            $condition[] = "location_car_type = '" . $car_type . "'";
        }
        if ($degree_type) {
            $condition[] = "location_degree_type = '" . $degree_type . "'";
        }

        if ($condition) {
            $sql = "SELECT id ";
            $sql .= "FROM yard_location ";
            $sql .= " WHERE " . implode(" AND ", $condition);
            $sql .= " LIMIT 1";

        }

        $res = $this->db->fetchOne($sql);
        Logger::info('res: ' . var_export($res, true));
        return $res;
    }


    /**
     * haibo
     * 功能: 查询所有堆场接口位置信息
     * @param $yard_id
     * @return \YardLocation[]
     */
    public function locations($yard_id)
    {
        $yard_id = intval($yard_id);
        $data = \YardLocation::find(["yard_id = $yard_id", 'columns' => 'id,location_car_type,location_degree_type,location_type,latitude,longitude']);

        $count = $data->count();
        $data = $data->toArray();


        return $data;
    }

    public function saveOrUpdateLocation($yard_id, $location_detail_type, $latitude, $longitude, $forceOverWrite = 0)
    {
        $ret = array(
            'error_code' => 0,
            'error_msg' => '成功',
        );

        if (!isset($longitude)
            || !isset($location_detail_type)
            || !isset($latitude)
            || (intval($location_detail_type)>5 || intval($location_detail_type)<1)

        ) {
            $ret['error_code'] = 2;
            $ret['error_msg'] = '提交参数缺失或者错误';
            return $ret;
        }

        $detailType = $this::getDetailLocationType($location_detail_type);
        $location = \YardLocation::findFirst(
            [
                'columns' => '*',
                'conditions' => 'yard_id = ?1 AND location_car_type = ?2 AND location_degree_type=?3 AND location_type=?4',
                'bind' => [
                    1 => $yard_id,
                    2 => $detailType['yard_car_type'],
                    3 => $detailType['yard_degree_type'],
                    4 => $detailType['yard_location_type'],
                ]
            ]
        );

        if ($location) {
//            if ($forceOverWrite == 1) {
            // force update
            $location->latitude = $latitude;
            $location->longitude = $longitude;
            $location->update();
            $updateRet = $location->update();
            if (!$updateRet) {
                $ret['error_code'] = 2;
                $ret['error_msg'] = '更新堆场位置失败';
                return $ret;
            }

//            } else {
//                //remind exist this location type
//
//                $ret['error_code'] = 3;
//                $ret['error_msg'] = '堆场此类型位置已经存在，是否重写？';
//                return $ret;
//            }


        } else {
            // new location
            $newLocation = new \YardLocation();
            $newLocation->yard_id = $yard_id;
            $newLocation->latitude = $latitude;
            $newLocation->longitude = $longitude;
            $newLocation->location_car_type = $detailType['yard_car_type'];
            $newLocation->location_degree_type = $detailType['yard_degree_type'];
            $newLocation->location_type = $detailType['yard_location_type'];
            $saveRet = $newLocation->save();
            if (!$saveRet) {
                $ret['error_code'] = 2;
                $ret['error_msg'] = '新建堆场位置失败';
                return $ret;
            }
        }
        return $ret;

    }

}