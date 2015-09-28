<?php

use  Library\Log\Logger;
use Modules\www\Validation\TestValidation;
use  Library\Helper\PageHelper;

/**
 * @RoutePrefix("/api/ordersuper")
 */
class ApiOrderSuperController extends ApiControllerBase
{
    /**
     * 创建订单时调用自动分配订单的跟单员
     * @Route("/autoAssign", methods={"GET"})
     */
    public function autoAssignAction()
    {
        try {
            $id = $this->request->get('orderid');

            if (empty($id)) {
                Logger::warn('autoAssgin  param empty');
                $this->ret['error_msg'] = 'paras not correct';
                $this->ret['error_code'] = 1;
                return $this->sendBack();
            }

            $ret = $this->OrderSuperService->assignAuto($id);

            $jsonContent = array(
                'orderid' => $id,
                'admin_userid' => $this->admin_cfg->ACL_ROLE_TYPE->SYS_ID,
            );

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ASSIGN_ORDER_AUTO,
                json_encode($jsonContent)
            );

            return $this->sendBack($ret);

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
    }

    /**
     * 指派订单的跟单员
     * @Route("/assignOrderAdmin", methods={"GET"})
     */
    public function assignOrderAdminAction()
    {
        try {
            $orderid = $this->request->get('orderid');
            $admin_userid = $this->request->get('admin_userid');
            if (empty($orderid) || empty($admin_userid)) {
                Logger::warn('assignOrderAdminAction  para empty orderid:' . $orderid . ' admin_userid' . $admin_userid);
                $this->ret['error_msg'] = 'paras not correct';
                $this->ret['error_code'] = 1;
                $this->sendBack();
                return;
            }
            $this->ret = $this->OrderSuperService->assignOrderSupers($admin_userid, $orderid);
            $jsonContent = array(
                'orderid' => $orderid,
                'admin_userid' => $admin_userid,
            );
            $this->ActivityLogService->addAdminLog(
                $this->di->get('admin_cfg')->ADMIN_ACTION_TYPE['ASSIGN_ORDER_SUPER'],
                json_encode($jsonContent)
            );
            $this->sendBack();
        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '系统异常';
            $this->sendBack();
            Logger::warn($e->getMessage());
        }
    }

    /**
     * 更换订单的跟单员
     * @Route("/changeOrderAdmin", methods={"GET", "POST"})
     */
    public function changeOrderAdminAction()
    {
        try {

            $orderid = $this->request->get('orderid');
            $admin_userid = $this->request->get('admin_userid');

            if (empty($orderid) || empty($admin_userid)) {
                Logger::warn('changeOrderAdminAction  para empty orderid:' . $orderid . ' admin_userid' . $admin_userid);
                $this->ret['error_msg'] = 'paras not correct';
                $this->ret['error_code'] = 1;
                $this->sendBack();
                return;
            }
            $this->ret = $this->OrderSuperService->changeOrderSupers($admin_userid, $orderid);

            $loginusr= $this->AdminUserService->getSessionUser();

            $jsonContent = array(
                'old_admin_userid' => '',
                'orderid' => $orderid,
                'admin_userid' => $admin_userid,
                'op_admin_username' => $loginusr['username']
            );

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE['CHANGE_ORDER_SUPER'],
                json_encode($jsonContent),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE['ORDER'],
                $orderid
            );

            $this->sendBack();

        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            $this->sendBack();
            Logger::warn($e->getMessage());
        }
    }


    /**
     *  查询跟单员  跟单总览   转化率
     *  如果不传ordersupername参数，则是返回所有跟单员
     * @Route("/orderAdmins", methods={"GET"})
     */
    public function orderAdminsAction()
    {
        try {
            $ordersupername = $this->request->get('ordersupername'); // 跟单员名字
            $mobile = $this->request->get('mobile'); //  跟单员手机号
            $pageSize = $this->request->get('page_size');
            $page_no = $this->request->get('page_no'); //

            $start_time = $this->request->get('start_time'); //
            $end_time = $this->request->get('end_time'); //

            $pageHelper = new PageHelper($page_no, $pageSize);
            $pageHelper = $this->AclService->getAdminUserByGroupName('跟单组', $ordersupername, $mobile, $pageHelper);
            $users = $pageHelper->getData();

            if (count($users) > 0) {
                foreach ($users as $index => $userInfo) {
                    $userInfo['superData'] = $this->OrderSuperService->getUserSuperDetail($userInfo['id'], $start_time, $end_time);
                    $users[$index] = $userInfo;
                }

                $pageHelper->setData($users);
            }

            $this->ret['data'] = array("pageInfo" => $pageHelper->toArray());
            $this->sendBack();


        } catch (\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            $this->sendBack();
        }
    }

}

