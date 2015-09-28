<?php

use  Library\Log\Logger;
use Modules\www\Validation\TestValidation;
use  Library\Helper\PageHelper;

/**
 * @RoutePrefix("/api/basicstatic")
 */
class ApiBasicStaticController extends ApiControllerBase
{
    const DASHBOARD_PAGE_CACHE_TIME = 600;
    const DASHBOARD_PAGE_CACHE_KEY = 'dashboard_indexpage_stat';

    /**
     *
     * 统计的默认首页
     *  最近24小时每个小时  30天的每天的  activitlog  adminlog 各种事件类型的次数
     * @Route("/home", methods={"GET"})
     */
    public function homeAction()
    {
        try {
            if ($this->cache->exists(self::DASHBOARD_PAGE_CACHE_KEY)) {
                $json = $this->cache->get(self::DASHBOARD_PAGE_CACHE_KEY);
                $this->ret['data'] = json_decode($json);

            } else {
                $eventStatisticArr = array(
                    'www' => array(),
                    'admin' => array(),
                );
                $oneDayAgo = date('Y-m-d H:i:s', time() - 24 * 3600);
                $thirtyDayAgo = date('Y-m-d H:i:s', time() - 30 * 24 * 3600);
                $endTime = date('Y-m-d H:i:s');
                $actionTypeArr = $this->constant->ACTION_TYPE;
                $eventStatisticArr['www']['24hour'] = $this->EventStatisticService->getTimesByEventTypes(array_values($actionTypeArr->toArray()), 1, $oneDayAgo, $endTime);
                $eventStatisticArr['www']['30day'] = $this->EventStatisticService->getTimesByEventTypes(array_values($actionTypeArr->toArray()), 2, $thirtyDayAgo, $endTime);
                $eventStatisticArr['admin']['24hour'] = $this->EventStatisticService->getTimesByEventTypes(array_values($actionTypeArr->toArray()), 1, $oneDayAgo, $endTime, 2);
                $eventStatisticArr['admin']['30day'] = $this->EventStatisticService->getTimesByEventTypes(array_values($actionTypeArr->toArray()), 2, $thirtyDayAgo, $endTime, 2);
                $userStat = $this->EventStatisticService->geUserStat();
                $orderStat = $this->EventStatisticService->geTableStat('order_freight', 'order_status');
                $boxStat = $this->EventStatisticService->geTableStat('order_freight_box', 'box_status');
                $data = array(
                    'eventStat' => $eventStatisticArr,
                    'userStat' => $userStat,
                    'orderStat' => $orderStat,
                    'boxStat' => $boxStat,
                );
                $this->cache->set(self::DASHBOARD_PAGE_CACHE_KEY, json_encode($data), self::DASHBOARD_PAGE_CACHE_TIME); // 缓存
                $this->ret['data'] = $data;
            }


            return $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();

    }


    /**
     * 查询某个事件类型   特定时间段的 事件次数
     * @Route("/queryEvent", methods={"GET"})
     */
    public function queryEventAction()
    {
        try {
            //  $endTime = date('Y-m-d H:i:s');
            $start_time = $this->request->get('start_time');
            $end_time = $this->request->get('end_time');
            $event_typeid = $this->request->get('event_typeid');  //  activitylog 事件类型id  or   adminlog 事件类型id
            $time_type = $this->request->get('time_type');  // 1: hourly  ,   2 :  daily
            $platform_type = $this->request->get('platform_type');  // 1 activitylog(www &app)     2 :  adminlog

            if (!isset($event_typeid) || !isset($time_type)) {
                $this->ret['error_code'] = 3;
                $this->ret['error_msg'] = '参数缺失';
                return $this->sendBack();
            }
            $data = $this->EventStatisticService->getTimesByEventType($event_typeid, $time_type, $start_time, $end_time, $platform_type);

            $this->ret['data'] = $data;
            return $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
        return $this->sendBack();
    }

}

