<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/31
 * Time: 下午8:10
 */


namespace Services\LogicService;

use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;


class AppLocateService extends Component {

    /**
     * 围栏跟踪状态更新
     */
    public function updateStatus($driver_id, $order_freight_id, $assign_id, $content, $type) {
        $res['error_code'] = '0';
        $res['error_msg'] = '';
        $res['data'] = array();

        # 验证数据
        if (empty($driver_id) || (empty($order_freight_id) && empty($assign_id)) || empty($content) || empty($type)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        $assign_ids = array();
        if (!empty($order_freight_id)) {
            $assign_result = \OrderAssignDriver::find(
                array(
                    'columns' => 'id',
                    'conditions' => 'order_freight_id = ?1 AND driver_user_id = ?2',
                    'bind' => array(1 => $order_freight_id, 2 => $driver_id)
                )
            );
            foreach ($assign_result as $v) {
                $assign_ids[] = $v->id;
            }
        }
        else {
            $assign_ids[] = $assign_id;
        }

        # 开启事务
        $this->db->begin();

        # 修改状态
        foreach ($assign_ids as $assign_id) {
            # 检测箱子
            $assign_info = \OrderAssignDriver::findFirst($assign_id);
            if (empty($assign_info)) {
                continue;
            }

            # 更新address_status或drop_box_status，及箱子的timeline
            # 到达产装地附近
            if ($type == 'ARRIVE_CHANZHUANG') {
                $assign_info->address_status = 1;
            }
            else {
                # 到达堆场附近
                $assign_info->drop_box_status = 1;
            }
            $assign_info->update_time = date('Y-m-d H:i:s');
            $result = $assign_info->save();

            if ($result) {
                $params = array(
                    $assign_info->order_freight_id,
                    $assign_info->order_freight_boxid,
                    $type,
                    $driver_id,
                    0,
                    $content
                );
                $update_timeline_res = $this->di->get('AppOrderService')->updateBoxTimeLine(
                    $assign_info->order_freight_id,
                    $assign_info->order_freight_boxid,
                    $type,
                    $driver_id,
                    0,
                    $content
                );
                # 更新未成功则回滚
                if (!$update_timeline_res) {
                    $this->db->rollback();
                    Logger::warn(
                        $this->constant->LOG_SEPORATER . "APPLOCATE updateStatus: " .
                        $this->constant->LOG_SEPORATER . var_export($params, true)
                    );
                    return $res;
                }
            }
        }
        # 提交事务
        $this->db->commit();

        $res['data']['isok'] = '1';
        return $res;
    }

    /**
     * 箱子位置上报接口
     *
     * @param $driver_id int
     * @param $order_freight_id int
     * @param $box_id int
     * @param $longitude
     * @param $latitude
     *
     * @return bool
     */
    public function locateBoxInfo($driver_id, $order_freight_id, $box_id, $type, $longitude, $latitude) {
        $res['error_code'] = '0';
        $res['error_msg'] = '';
        $res['data'] = array();

        $order_freight_ids = explode(',', trim($order_freight_id, ','));
        $box_ids = explode(',', trim($box_id, ','));

        # 验证数据
        if (empty($driver_id) || empty($order_freight_ids) || empty($box_ids) || empty($type)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        # 验证箱子的属主
        $counts = 0;
        $conditions = array(
            'order_freight_id IN (' . implode(', ', $order_freight_ids) . ')',
            'order_freight_boxid IN (' . implode(', ', $box_ids) . ')',
            'driver_user_id = ?1'
        );
        $bind = array(1 => $driver_id);
        $counts = \OrderAssignDriver::count(array(
            'conditions' => implode(' AND ', $conditions),
            'bind' => $bind
        ));
        if (empty($counts)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        # 插入数据
        $box_location = new \OrderBoxLocation();

        $data['order_assign_driver'] = $driver_id;
        $data['box_latitude'] = !empty($latitude) ? $latitude : 0;
        $data['box_longitude'] = !empty($longitude) ? $longitude : 0;
        $data['location_source_type'] = $type;
        $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

        foreach ($order_freight_ids as $key => $order_id) {
            $data['order_freight_id'] = $order_id;
            $data['order_freight_boxid'] = $box_ids[$key];

            $box_location->save($data);
        }

        $res['data']['isok'] = '1';
        return $res;
    }

    /**
     * 获取产装地与落箱地的经纬度
     *
     * @param $driver_id int
     * @param $status_list array
     *
     * @return array
     */

    public function getLocationInfo($driver_id, $status_list) {
        $data = array();

        $assigns = \OrderAssignDriver::find(
            array(
                "conditions"    => "driver_user_id = ?1 AND assign_status IN(" . implode(',', $status_list) . ")",
                "bind"          => array(1 => $driver_id),
                #"group"         => "order_freight_boxid",
                "order"         => "order_product_timeid DESC"
            )
        );

        if (count($assigns)) {
            $data = $this->getBoxListByOrderId($assigns);
        }

        return $data;
    }

    /**
     * 格式化数据按订单获取箱子位置信息
     */
    public function getBoxListByOrderId($assign_list) {
        $data = array();
        $res = array();
        # 获取订单下的箱子信息
        foreach ($assign_list as $key => $val) {
            $res[$val->order_freight_id][] = $val;
        }

        if ($res) {
            foreach ($res as $order_id => $value) {
                $result = array();

                # get堆场名字
                $order_info = \OrderFreight::findFirst($order_id);
                $yard_info = \YardInfo::findFirst($order_info->yard_id);
                # 堆场名字
                $result['yard_name'] = !empty($yard_info->yard_name) ? $yard_info->yard_name : "";
                $result['order_freight_id'] = $order_id;
                # $result['yard_name'] = '天津东疆码头';
                # 堆场经纬度
                $location_info = $this->getLongitudeAndLatitude($result['yard_name']);
                $result['yard_longitude'] = $location_info['longitude'];
                $result['yard_latitude'] = $location_info['latitude'];

                foreach($value as $assign_info) {
                    # get产装地信息
                    $address_info = \OrderProductAddress::findFirst($assign_info->order_product_addressid);
                    # 产装地经纬度
                    $address_town_info = $this->di->get('CityService')->getFullNameById($address_info->address_townid);
                    $address_location_info = $this->getLongitudeAndLatitude($address_town_info . $address_info->address_detail);
                    $result['address_list'][] = array(
                        'assign_id'         => $assign_info->id,
                        'longitude'         => $address_location_info['longitude'],
                        'latitude'          => $address_location_info['latitude'],
                        'address_status'    => $assign_info->address_status,
                        'drop_box_status'   => $assign_info->drop_box_status
                    );
                }

                $data[] = $result;
            }
        }

        return $data;
    }


    /**
     * 获取经纬度
     */
    public function getLongitudeAndLatitude($address_name) {
        $res = array('longitude' => 0, 'latitude' => 0);

        if (!empty($address_name)) {
            $data = \Library\Helper\LocationHelper::getLocationByAdress($address_name);
            $arr = explode(',', $data);
            $res['longitude'] = !empty($arr[0]) ? $arr[0] : 0;
            $res['latitude'] = !empty($arr[1]) ? $arr[1] : 0;
        }

        return $res;
    }


    /**
     * 通过经纬度获取地址
     */
    public function getAddressByLocation($latitude, $longitude) {
        $address_info = '';

        if (!empty($latitude) && !empty($longitude)) {
            $location_info = $longitude . ',' . $latitude;
            $address_info = \Library\Helper\LocationHelper::getLocationByAdress($location_info);
        }

        return !empty($address_info) ? $address_info : '';
    }

}