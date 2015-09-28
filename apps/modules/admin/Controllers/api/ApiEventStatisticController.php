<?php

use  Library\Log\Logger;


/**
 * * @RoutePrefix("/api/es")
 */
class ApiEventStatisticController extends ApiControllerBase
{
    /**
     *
     * 根据小时统计事件   返回按照时间排序过的数据  (每小时 每天的事件次数)
     * @Route("/queryEvent", methods={"GET"})
     *
     */
    public function queryEventAction()
    {
        try {
            if (!$this->paramVerify(new \Modules\admin\Validation\BasicStaticValidation('queryEventStatistic'))) {
                $this->ret['error_code'] = 2;
                $this->ret['error_msg'] = '参数校验出错';
                Logger::warn('paramVerify fail');
                return $this->sendBack();
            }
            $event_type = $this->request->get('event_type');
            $time_type = $this->request->get('time_type');   // 1 hourly  2 按照day
            $start_time = $this->request->get('start_time'); // 精确到小时   yy-mm-dd hh
            $end_time = $this->request->get('end_time');

            $this->ret = array(
                'error_code' => 0,
                'error_msg' => '成功',
                'data' => array(),
            );

            if( $this->cache->exists('times_event_'.$time_type)) {
                $json = $this->cache->get('times_event_'.$time_type);
                $this->ret['data'] = json_decode($json);
            } else {
                $data =  $this->EventStatisticService->getTimesByEventType($event_type, $time_type, $start_time, $end_time) ;
                $this->ret['data'] = $data ;
            }

            return  $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络出错';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

    }


    /**
     *
     *  www 和 客户端用户事件历史
     * @Route("/userActivityList", methods={"GET"})
     *
     */
    public function userActivityListAction()
    {
        try {
            $userid = $this->request->get('id');
            $user_mobile = $this->request->get('mobile');
            $start_time = $this->request->get('begin_time');
            $end_time = $this->request->get('end_time');
            $activity_type = $this->request->get('type');   // 如果不传 ，查询所有事件类型
            $page_size = $this->request->get('page_size');
            $page_no = $this->request->get('page_no');

            $this->ret = array(
                'error_code' => 0,
                'error_msg' => '成功',
                'data' => array(),
            );

            $data = $this->ActivityLogService->getActivityByUser($userid, $user_mobile, $activity_type, $start_time, $end_time, $page_size, $page_no);
            $this->ret['data'] = $data;

            return $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络出错';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
    }

    /**
     *
     *  admin用户事件历史
     * @Route("/adminUserLogList", methods={"GET"})
     *
     */
    public function adminUserLogListAction()
    {
        try {
            $admin_userid = $this->request->get('id');
            $user_mobile = $this->request->get('mobile');
            $start_time = $this->request->get('begin_time');
            $end_time = $this->request->get('end_time');
            $admin_action_type = $this->request->get('type');   // 如果不传 ，查询所有事件类型
            $pageSize = $this->request->get('page_size');
            $page_no = $this->request->get('page_no');

            $this->ret = array(
                'error_code' => 0,
                'error_msg' => '成功',
                'data' => array(),
            );

            $data = $this->AdminUserService->getAdminUserLogList($admin_userid, $user_mobile, $start_time, $end_time, $admin_action_type);
            $this->ret['data'] = $data;

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->QUERY_USER_LOG,
                '',
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->USER,
                $admin_userid
            );

            return $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络出错';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
    }


    /**
     * 查询一个订单的操作 ， activitylog 和 adminlog  中的一些操作记录
     * @Route("/orderOpList", methods={"GET"})
     *
     */
    public function orderOpListAction()
    {
        try {

            $orderid = $this->request->get('id');
            $platformType = $this->request->get('platformType');  //  1: www的 activitylog   2： admin的adminlog    其他或者不传: 两个log都查询
            $this->ret = array(
                'error_code' => 0,
                'error_msg' => '成功',
                'data' => array(),
            );

            $data = $this->OrderService->getOrderOpList($orderid, $platformType);
            $this->ret['data'] = $data;

            return $this->sendBack();
        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络出错';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
    }


    /**
     *
     * @Route("/eventTypeList", methods={"GET"})
     *
     */
    public function eventTypeListAction()
    {
        try {
            $this->ret = array(
                'error_code' => 0,
                'error_msg' => '成功',
                'data' => array(),
            );

            $data =  $this->admin_cfg->EVENT_STATISTIC_TYPE_NAME;
            $this->ret['data'] = $data ;
            return  $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络出错';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

    }


}

