<?php

use Library\Log\Logger;
use Modules\admin\Validation\ApiCarTeamUserValidation;

/**
 * haibo
 * 堆场管理
 * @RoutePrefix("/api/yard")
 */
class ApiYardController extends ApiControllerBase
{

    /**
     * save or update
     * {
     * "yard_id":16,
     * "cock_city_code":"tianjin",
     * "yard_name":"56xddcom堆场",
     * "locations":[
     * {
     * "location_detail_type":5,
     * "latitude":"39.0086361801",
     * "longitude":"117.7419987002"
     * },
     * {
     * "location_detail_type":4,
     * "latitude":"39.0086361801",
     * "longitude":"117.7619987002"
     * },
     * {
     * "location_detail_type":3,
     * "latitude":"39.0086361801",
     * "longitude":"117.7619987333"
     * },
     * {
     * "location_detail_type":2,
     * "latitude":"39.0066361801",
     * "longitude":"117.7619987999"
     * },
     * {
     * "location_detail_type":1,
     * "latitude":"39.0086361801",
     * "longitude":"117.7619987002"
     * }
     * ]
     * }
     *
     *
     * @Route("/save", methods={"POST"})
     */
    public function saveAction()
    {
        try {

            $yardWithLocations = $this->request->getJsonRawBody(true);
            $this->ret = $this->YardInfoService->saveOrUpdateYardInfo($yardWithLocations);
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE['YARD_SAVE'],
                $yardWithLocations
            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();

        }
        return $this->sendBack();
    }

    /**
     * save or update location of  yard_id
     * @Route("/saveOrUpdateLocation", methods={"POST"})
     */
    public function saveOrUpdateLocationAction()
    {
        try {
            $yard_id = $this->request->get('yard_id');
            $location_detail_type = $this->request->get('location_detail_type');  // 枚举  1-5
            $latitude = $this->request->get('latitude');
            $longitude = $this->request->get('longitude');

            //  1 or others  , 1 为强制重写此类型type     (如果没有强制重写， errorcode 返回3提示已经存在此类型位置)
            $forceOverWrite = $this->request->get('forceOverWrite');
            if (empty($yard_id)) {
                $this->ret['error_msg'] = 'yard_id 不能为空';
                $this->ret['error_code'] = -1;
                Logger::warn('yard_id 不能为空');
                return $this->sendBack();
            }
            $yard = \YardInfo::findFirst(array("id = ?1", 'bind' => [1 => $yard_id], 'columns' => 'id,cock_city_code,yard_name'));
            if (!$yard) {
                $this->ret['error_msg'] = '堆场id不存在';
                $this->ret['error_code'] = -1;
                Logger::warn('yard_id 不能为空');
                return $this->sendBack();
            }
            $this->ret = $this->YardLocationService->saveOrUpdateLocation($yard_id, $location_detail_type, $latitude, $longitude, $forceOverWrite);

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE['YARD_LOCATION_SAVE'],
                json_encode(array(
                    'latitude' => $latitude,
                    'yard_id' => $yard_id,
                    'longitude' => $longitude,
                    'location_detail_type' => $location_detail_type ,
                ))
            );


        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
        return $this->sendBack();
    }


}