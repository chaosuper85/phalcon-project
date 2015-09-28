<?php
/**
 * 司机app订单相关接口
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/24
 * Time: 下午12:08
 */

use Library\Log\Logger;

/**
 * @RoutePrefix("/apporder")
 */

class AppOrderController extends ControllerBase {

    protected $user_id = null;

    public function initialize() {

        $this->user_id = $this->dispatcher->getParam('user_id');
    }

    /**
     * @Post("/index")
     */
    public function indexAction() {
        try {
            $driver_id = $this->request->getPost('driver_id', 'int');
            $status_list = array(
                $this->order_config->assign_status_enum->TO_TIXIANG,
                $this->order_config->assign_status_enum->TO_CHANZHUANG,
                $this->order_config->assign_status_enum->APP_CHANZHUANG_COMPLETE,
                $this->order_config->assign_status_enum->TO_YUNDI
            );

            $res = $this->di->get('AppOrderService')->getOrderIndexInfoByDriverId($driver_id, $status_list);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/detail")
     */
    public function detailAction() {
        try{
            $assign_id = $this->request->getPost('assign_id', 'int');

            $res = $this->di->get('AppOrderService')->getOrderDetailInfoByAssignId($this->user_id, $assign_id);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/productList")
     */
    public function productListAction() {
        try {
            $driver_id          = $this->user_id;
            $order_freight_id   = $this->request->getPost('order_freight_id', 'int');
            $box_id             = $this->request->getPost('box_id', 'int');

            $res = $this->di->get('AppOrderService')->getProductList($order_freight_id, $driver_id, $box_id);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/productConfirm")
     */
    public function productConfirmAction() {
        try {
            $driver_id          = $this->user_id;
            $order_freight_id   = $this->request->getPost('order_freight_id', 'int');
            $box_id             = $this->request->getPost('box_id', 'int');
            $address_id         = $this->request->getPost('address_id', 'int');
            $time_id            = $this->request->getPost('time_id', 'int');

            $res = $this->di->get('AppOrderService')->doProductConfirm(
                $driver_id,
                $order_freight_id,
                $box_id,
                $address_id,
                $time_id
            );
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/dropDetail")
     */
    public function dropDetailAction() {
        try {
            $driver_id          = $this->user_id;
            $order_freight_id   = $this->request->getPost('order_freight_id', 'int');
            $box_id             = $this->request->getPost('box_id', 'int');

            $res = $this->di->get('AppOrderService')->getDropDetailData($driver_id, $order_freight_id, $box_id);

            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/dropConfirm")
     */
    public function dropConfirmAction() {
        try {
            $driver_id          = $this->user_id;
            $order_freight_id   = $this->request->getPost('order_freight_id', 'int');
            $box_id             = $this->request->getPost('box_id', 'int');

            $res = $this->di->get('AppOrderService')->doDropConfirm($driver_id, $order_freight_id, $box_id);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/complete")
     */
    public function completeAction() {
        try {
            $driver_id = $this->request->getPost('driver_id', 'int');
            $status_list = array(
                $this->order_config->assign_status_enum->LUOXIANG,
                $this->order_config->assign_status_enum->YUNDI,
                $this->order_config->assign_status_enum->CANCEL
            );
            $current_page = $this->request->getPost('page', 'int', 1);

            $res = $this->di->get('AppOrderService')->getCompleteBoxList($driver_id, $status_list, $current_page);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

    /**
     * @Post("/completeDetail")
     */
    public function completeDetailAction() {
        try {
            $driver_id = $this->user_id;
            $order_freight_id = $this->request->getPost('order_freight_id', 'int');
            $box_id = $this->request->getPost('box_id', 'int');

            $res = $this->di->get('AppOrderService')->getCompleteBoxDetail($driver_id, $order_freight_id, $box_id);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

}