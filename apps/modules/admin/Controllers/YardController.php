<?php

use Library\Log\Logger;
use Library\Helper\PageHelper;

/**
 * haibo
 * 堆场管理
 * @RoutePrefix("/yard")
 */
class YardController extends ControllerBase
{

    /**
     * 查询所有堆场
     * @Route("/yardInfos", methods={"GET", "POST"})
     */
    public function yardInfosAction()
    {
        try {

            $pageSize = $this->request->get('page_size');
            $page_no = $this->request->get('page_no');
            $yard_name = $this->request->get('yard_name');  // 模糊搜索
            $create_time_start = $this->request->get('create_time_start');
            $create_time_end = $this->request->get('create_time_end');
            $create_type = $this->request->get('create_type');   //  0 ->admin创建   1 -》www 创建
            $cock_city_code = $this->request->get('cock_city_code'); // 堆场 城市  cityid
            $pageHelper = new PageHelper($page_no, $pageSize);
            $data = $this->YardInfoService->yardInfos($cock_city_code, $yard_name, $create_time_start, $create_time_end, $create_type, $pageHelper);
            $this->ret = $data->toArray();

            unset($_REQUEST['_url']);
            unset($_REQUEST['PHPSESSID']);
            $this->ret['paras'] = $_REQUEST;
//            $this->ret[] = $_REQUEST;

        } catch (\Exception $e) {
            Logger::error('yardInfos' . $e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }
        return $this->sendBack('page/resource/yard');
    }


    /**
     * 按堆场id获取所有堆场位置信息
     *
     * @Route("/yardLocations", methods={"GET", "POST"})
     */
    public function yardLocationsAction()
    {
        try {
            $id = $this->request->get('yard_id');
            $locations = $this->YardLocationService->locations($id);
            $this->ret = $locations;
            Logger::info('queryYardLocations  sum:' . count($locations));
        } catch (\Exception $e) {
            Logger::error('yardLocations :' . $e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }
        return $this->sendBack('page/resource/yard');
    }


    /**
     * 按堆场id获取所有堆场位置信息
     * @Route("/yarddetail", methods={"GET", "POST"})
     */
    public function yardDetailAction()
    {
        try {
            $id = $this->request->get('yard_id');
            if (empty($id)) {
                Logger::warn('yard_id 不能为空');
                return $this->sendBack('page/resource/yard_detail');
            }
            $yard = \YardInfo::findFirst(array("id = ?1", 'bind' => [1 => $id], 'columns' => '*'));
            if (!$yard) {
                Logger::warn('yard_id 不存在');
                return $this->sendBack('page/resource/yard_detail');
            }
            $locations = $this->YardLocationService->locations(intval($id));


            $retArr = array(
                'yardinfo' => $yard->toArray()
            );

            foreach ($locations as $index => $location) {
                $location_detail_type = $location['location_detail_type'] =
                    $this->YardLocationService->getLocationTypeByDetail(array(
                        'yard_location_type' => $location['location_type'],
                        'yard_car_type' => $location['location_car_type'],
                        'yard_degree_type' => $location['location_degree_type'],
                    ));
                $locationTypeName = "location_type_" . $location_detail_type;

                $retArr['yardinfo'][$locationTypeName] = $location;
            }

            for ($i = 1; $i <= 5; $i++) {
                if(!isset($retArr['yardinfo']['location_type_'.$i])){
                    $retArr['yardinfo']['location_type_'.$i] = array() ;
                }
            }

            $this->ret = $retArr;

        } catch (\Exception $e) {
            Logger::error('yardLocations :' . $e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }
        return $this->sendBack('page/resource/yard_detail');
    }


}