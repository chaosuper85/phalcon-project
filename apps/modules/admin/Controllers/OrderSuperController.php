<?php

use Library\Log\Logger;
use Modules\admin\Validation\CarTeamUserValidation;
use Library\Helper\PageHelper;

/**
 * @RoutePrefix("/ordersuper")
 */
class OrderSuperController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 跟单相关管理 Controller
    |--------------------------------------------------------------------------
    */

    /**
     * 跟单总览首页
     *  看到大部分订单  按照开始时间逆序   按照状态筛选
     * @Route("/orders", methods={"GET", "POST"})
     *
     */
    public function ordersAction()
    {

        $order_status = $this->request->get('order_status');   // 订单状态
        $order_id = $this->request->get('order_id');   // 订单状态
        $order_freightagent_mobile = $this->request->get('order_freightagent_mobile');  // 货代手机号
        $order_teammobile = $this->request->get('order_teammobile');  // 车队手机号
        $order_create_time_start = $this->request->get('order_create_start_time'); ## 订单生产时间
        $order_create_time_end = $this->request->get('order_create_end_time'); ## 订单生产时间
        $pageSize = $this->request->get('page_size');
        $page_no = $this->request->get('page_no');
        $enterprisename = $this->request->get('enterprisename');  // 车队或者货代名字   模糊查询
        $supervisor_name = $this->request->get('supervisor_name');  // 跟单员名字 模糊查询
        $tidan_code = $this->request->get('tidan_code');
        $yundan_code = $this->request->get('yundan_code');

        $pageHelper = new PageHelper($page_no, $pageSize);
        try {
            $data = $this->OrderService->ordersForAdmin($order_id, $enterprisename, $supervisor_name, $order_freightagent_mobile, $order_teammobile,
                $order_create_time_start, $order_create_time_end, $tidan_code , $yundan_code,
                $pageHelper, $order_status);
            $this->ret = $data->toArray();

            unset($_REQUEST['_url']);
            unset($_REQUEST['PHPSESSID']);

            $this->ret['paras'] = $_REQUEST;

        } catch (\Exception $e) {
            Logger::error('exception: ' . $e->getTraceAsString());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack("page/order/all");
    }


    /**
     *
     * 我跟进的订单
     *
     * @Route("/myOrder", methods={"GET", "POST"})
     */
    public function myOrderAction()
    {

        $order_status = $this->request->get('order_status');   // 订单状态
        $order_freightagent_mobile = $this->request->get('order_freightagent_mobile');  // 货代手机号
        $order_teammobile = $this->request->get('order_teammobile');  // 车队手机号
        $order_create_time_start = $this->request->get('order_create_time_start'); ## 订单生产时间
        $order_create_time_end = $this->request->get('order_create_time_end'); ## 订单生产时间
        $pageSize = $this->request->get('page_size');
        $page_no = $this->request->get('page_no');
        $tidan_code = $this->request->get('tidan_code');
        $yundan_code = $this->request->get('yundan_code');

        $pageHelper = new PageHelper($page_no, $pageSize);
        $usr = $this->AdminUserService->getSessionUser();
        $adminuserid = $usr['id'];

        try {
            $data = $this->OrderSuperService->getOrdersBySupervisor($adminuserid, $order_freightagent_mobile, $order_teammobile,
                $order_create_time_start, $order_create_time_end,  $tidan_code , $yundan_code,
                $pageHelper, $order_status);

            Logger::info('data: ' . var_export($data, true));
            $this->ret = $data->toArray();

            unset($_REQUEST['_url']);
            unset($_REQUEST['PHPSESSID']);
            $this->ret['paras'] = $_REQUEST;


        } catch (\Exception $e) {
            Logger::error('exception :' . $e->getTraceAsString());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack("page/order/my");
    }

}