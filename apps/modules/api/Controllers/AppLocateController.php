<?php
/**
 * 司机app定位接口
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/24
 * Time: 下午12:20
 */

use Library\Log\Logger;

/**
 * @RoutePrefix("/applocate")
 */
class AppLocateController extends ControllerBase {

    public $user_id = null;

    public function initialize() {

        $this->user_id = $this->dispatcher->getParam('user_id');
    }


    /**
     * @Post("/push")
     */
    public function pushAction() {
        $params = array('driver_id' => 31, 'assign_id' => 1, 'weburl' => 'http://ifeng.com');
        var_dump($this->di->get("JpushService")->toAlias('TO_WEB', json_encode($params)));
    }

    /**
     * @Post("/index")
     */
    public function indexAction() {
        try {
            $res = $this->di->get('AppLocateService')->locateBoxInfo(
                $this->user_id,
                $this->request->getPost('order_freight_id'),
                $this->request->getPost('box_id'),
                $this->request->getPost('type', 'int'),
                $this->request->getPost('longitude', 'string'),
                $this->request->getPost('latitude', 'string')
            );

            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP LOCATE:" . $e->getMessage());
        }
    }

    /**
     * @Post("/get")
     */
    public function getAction() {
        try {
            # 只获取箱子状态为：待产装、待运抵
            $status_list = array(
                $this->order_config->assign_status_enum->TO_TIXIANG,
                $this->order_config->assign_status_enum->APP_CHANZHUANG_COMPLETE,
                $this->order_config->assign_status_enum->TO_CHANZHUANG,
                $this->order_config->assign_status_enum->TO_YUNDI
            );

            $res['error_code'] = '0';
            $res['error_msg'] = '';
            $res['data'] = $this->di->get('AppLocateService')->getLocationInfo(
                $this->user_id,
                $status_list
            );

            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP LOCATE:" . $e->getMessage());
        }
    }

    /**
     * @Post("/updateStatus")
     */
    public function updateStatusAction() {
        try {
            $res = $this->di->get('AppLocateService')->updateStatus(
                $this->user_id,
                $this->request->getPost('order_freight_id', 'int'),
                $this->request->getPost('assign_id', 'int'),
                $this->request->getPost('content', 'string'),
                $this->request->getPost('type', 'string')
            );

            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP LOCATE:" . $e->getMessage());
        }
    }
}