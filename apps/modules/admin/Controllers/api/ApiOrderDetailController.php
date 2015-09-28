<?php

use  Library\Log\Logger;
use Modules\www\Validation\TestValidation;
use  Library\Helper\PageHelper;

/**
 * @RoutePrefix("/api/orderDetail")
 */
class ApiOrderSuperController extends ApiControllerBase
{
    /**
     * 修改箱子状态等信息
     * @Route("/setBox", methods={"GET"})
     */
    public function setBoxAction()
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



}

