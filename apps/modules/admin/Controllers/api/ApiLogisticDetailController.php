<?php

use  Library\Log\Logger;
use Modules\www\Validation\TestValidation;
use  Library\Helper\PageHelper;

/**
 * @RoutePrefix("/api/logisticDetail")
 */
class ApiOrderSuperController extends ApiControllerBase
{
    /**
     * 重新为司机定位
     * @Route("/locate", methods={"GET"})
     */
    public function locateAction()
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

