<?php

use Library\Helper\ArrayHelper;


use Modules\www\Validation\CarTeamConfirmOrderValidation;

use Phalcon\Http\Response;
use Library\Log\Logger;

/**
 * @RoutePrefix("/api/carteam/order")
 */
class ApiCarteamOrderController extends ApiControllerBase
{

    /** 车队 确认接单
     * @Route("/confirm", methods={"GET", "POST"})
     */
    public function confirmAction()
    {
        $reqJson =$this->request->getJsonRawBody(true);
        $result = array('error_code'=>'0', 'error_msg'=>'', 'data'=> array());
        Logger::info("confirmAction request:".var_export($reqJson,true));
        $data =  ArrayHelper::toArray($reqJson);

        $user    = $this->session->get("login_user");
        // 检查必填项
        $message = $this->CarTeamConfirmOrderValidation->validate($data);
        if( count($message) ){
            $result['error_code'] = 10000001;
            $result['error_msg']  = "参数格式不正确";
            $result['data']       = ArrayHelper::validateMessages($message);
        }else{
            try{
                 $this->ApiCarTeamOrderService->confirmOrder($reqJson,$user->id,$result);
            }catch (\Exception $e){
                Logger::info("confirmAction error msg:".$e->getMessage());
                $result['error_code'] = 10000003;
            }
        }
        Logger::info("confirmAction return:".var_export($result,true));
        $this->response->setJsonContent( $result )->send();
        return ;
    }


    /**
     * @Route("/address_confirm", methods={"GET", "POST"})
     */
    public function addressConfirmAction()
    {
/*
 *
 *
请求参数的例子：

{
    "orderid": "123456",
    "address_info": [
        {
            "product_address_id": 123,
            "box_address": {
                "provinceid": 1,
                "cityid": 2,
                "townid": 3,
                "address": "黄村镇清源西里9号"
            },
            "contactName": "李洪文",
            "contactNumber": "13677890987",
            "box_date": [
                {
                    "product_supply_time": "2015-09-06 02:00:00",
                    "product_time_id": "188"
                },
                {
                    "product_supply_time": "2015-09-06 02:00:00",
                    "product_time_id": "189"
                },
                {
                    "product_supply_time": "2015-09-06 02:00:00"
                }
            ]
        },
        {
            "box_address": {
                "provinceid": 1,
                "cityid": 2,
                "townid": 3,
                "address": "黄村镇清源西里9号"
            },
            "contactName": "李洪文",
            "contactNumber": "13677890987",
            "box_date": [
                {
                    "product_supply_time": "2015-09-06 02:00:00"
                },
                {
                    "product_supply_time": "2015-09-06 02:00:00"
                }
            ]
        }
    ]
}


 */
        $req = $this->request->getJsonRawBody(true);
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $msg = $this->AddressConfirmValidation->validate($req);

        if( count($msg) ){
            $result = array(
                'error_code'=>'10001',
                'error_msg'=>'参数校验错误',
                'data'=> ArrayHelper::validateMessages($msg),
            );
        }else{
            try{

                $ret = $this->OrderAddressConfirmService->orderAddressConfirm( $req );
                if( !$ret )
                {
                    $result = array(
                        'error_code'=>'100002',
                        'error_msg'=>'确认产装信息错误',
                        'data'=> array(),
                    );
                }

            }catch (\Exception $e){
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code'=>'100003',
                    'error_msg'=>'系统异常',
                    'data'=> array(),
                );
            }
        }

        $this->response->setJsonContent( $result )->send();

        return ;
    }

    /** 搜索船名
     * @Route("/search_ship_name", methods={"GET", "POST"})
     */
    public function searchShipNameAction()
    {
        $keyWord = $this->request->get("keyWord");
        $result = array('error_code'=>'0', 'error_msg'=>'', 'data'=> array() );

        $data = array();
        try{
            $data  = $this->ShipNameService->searchShipName( $keyWord );
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'系统异常',
                'data'=> array(),
            );
        }
        $result['data'] = $data;
        $this->response->setJsonContent( $result )->send();
        return ;
    }


    /** 搜索堆场
     * @Route("/search_yard", methods={"GET", "POST"})
     */
    public function searchYardAction()
    {
        $keyWord = $this->request->get("keyWord");
        $version = $this->request->get("version");
        $result = array('error_code'=>'0', 'error_msg'=>'', 'data'=> array() );
        $cityCode = "tianjin";
        $data = array();
        try{
            $data  = $this->YardInfoService->searchYard( $keyWord ,$cityCode);
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'系统异常',
                'data'=> array(),
            );
        }
        $result['data'] = $data;
        $this->response->setJsonContent( $result )->send();

        return ;


    }


    /**  搜索船公司
     * @Route("/search_ship_company", methods={"GET", "POST"})
     */
    public function searchShipCompanyAction()
    {
        $keyWord = $this->request->get("keyWord");
        $result  = array('error_code'=>'0', 'error_msg'=>'', 'data'=> array() );
        $data    = array();
        try{
              $data = $this->ApiCarTeamOrderService->searchShipCompany( $keyWord );
        }catch (\Exception $e){
            Logger::warn("searchShipCompanyAction error msg:".$e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'系统异常',
                'data'=> array(),
            );
        }
        $result['data'] = $data;
        $this->response->setJsonContent( $result )->send();
        return ;
    }



    /**
     * @Route("/down_load_files", methods={"GET", "POST"})
     */
    public function downLoadFilesAction()
    {

        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $data = array();
        try{

            $data = array(

            );

        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'系统异常',
                'data'=> array(),
            );
        }

        $result['data'] = $data;

        $this->response->setJsonContent( $result )->send();

        return ;


    }


    /**
     * 上传箱号铅封号，或者修改箱号铅封号
     *
     * @Route("/save_box_ensupe", methods={"GET", "POST"})
     */
    public function saveBoxEnsupeAction()
    {

        /*
        $req = array(
                    'order_box_id'      => '123',
                    'order_freight_id'  => '123',
                    'box_ensupe'        => '1234',
                    'box_code'          => '12345',
                );
         */

        $req = $this->request->getJsonRawBody(true);

        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $msg = $this->SaveBoxEnsupeValidation->validate( $req );
        if( count($msg) ){
            $result = array(
                'error_code'=>'10001',
                'error_msg'=>'参数校验错误',
                'data'=> ArrayHelper::validateMessages($msg),
            );
        }else{
            try{

                $ret = $this->CarteamOrderSaveBoxEnsupeService->save( $req );
                if( !$ret )
                {
                    $result = array(
                        'error_code'=>'100002',
                        'error_msg'=>'箱号铅封号信息错误',
                        'data'=> array(),
                    );
                }

            }catch (\Exception $e){
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code'=>'100003',
                    'error_msg'=>'系统异常',
                    'data'=> array(),
                );
            }

        }


        $this->response->setJsonContent( $result )->send();

        return ;
    }



    /**
     * 保存车队分派订单的信息ajax接口
     *
     * @Route("/assign_edit_save", methods={"GET", "POST"})
     */
    public function assignEditSaveAction()
    {

        /*
            {
                "order_assign_info": [
                    {
                        "assign_id": 1,
                        "order_freight_id": 12345,
                        "order_freight_boxid": 123,
                        "order_product_addressid": 1234567,
                        "driver_user_id": 123456789,
                        "order_product_timeid": 12345678
                    },
                    {
                        "order_freight_id": 12345,
                        "order_freight_boxid": 1234,
                        "order_product_addressid": 1234567,
                        "driver_user_id": 123456789,
                        "order_product_timeid": 12345678
                    },
                    {
                        "assign_id": 2
                    }
                ]
            }
         */
        $req = $this->request->getJsonRawBody(true);

        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $msg = $this->AssignEditSaveValidation->validate( $req );
        if( count($msg) )
        {
            $result = array(
                'error_code'=>'10001',
                'error_msg'=>'参数校验错误',
                'data'=> ArrayHelper::validateMessages($msg),
            );
        }else{

            $checkRes = $this->CarteamOrderAssignEditSaveService->checkInput( $req['order_assign_info'] );
            if( !$checkRes ){
                $result = array(
                    'error_code'=>'10001',
                    'error_msg'=>'数据重复',
                    'data'=> ArrayHelper::validateMessages($msg),
                );
            }else{
                try{

                    $ret = $this->CarteamOrderAssignEditSaveService->editSave( $req );
                    if( !$ret )
                    {
                        $result = array(
                            'error_code'=>'100002',
                            'error_msg'=>'保存车队分派订单的信息错误',
                            'data'=> array(),
                        );
                    }
                }catch (\Exception $e){
                    Logger::warn('assignEditSave throw exception '. $e->getTraceAsString());
                    $result = array(
                        'error_code'=>'100003',
                        'error_msg'=>'系统异常',
                        'data'=> array(),
                    );
                }
            }
        }

        $this->response->setJsonContent( $result )->send();

        return ;


    }


    /**
     * @Route("/choose_driver", methods={"GET", "POST"})
     */
    public function chooseDriverAction()
    {

        $req = $this->request->getJsonRawBody(true);

        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $msg = $this->ChooseDriverValidation->validate( $req );
        if( count($msg) ){
            $result = array(
                'error_code'=>'10001',
                'error_msg'=>'参数校验错误',
                'data'=> ArrayHelper::validateMessages($msg),
            );
        }else{
            try{
                $ret = $this->CarteamChooseDriverService->chooseDriver( $req );
                if( is_array($ret) && count($ret) >= 0 )
                {
                    $result['data'] = $ret;
                }else {
                    $result = array(
                        'error_code'=>'100004',
                        'error_msg'=>'系统错误',
                        'data'=> array(),
                    );
                }
            }catch (\Exception $e){
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code'=>'100003',
                    'error_msg'=>'系统异常',
                    'data'=> array(),
                );
            }
        }

        $this->response->setJsonContent( $result )->send();

        return ;
    }



    /**
     * @Route("/driver_equipment_complete", methods={"GET", "POST"})
     */
    public function driverEquipmentCompleteAction()
    {
        /*
        $req = array(
                    'assign_id'      => '1',
                );
         */

        $req = $this->request->getJsonRawBody(true);

        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $msg = $this->DriverEquipmentCompleteValidation->validate( $req );
        if( count($msg)  ){
            $result = array(
                'error_code'=>'10001',
                'error_msg'=>'参数校验错误',
                'data'=> ArrayHelper::validateMessages($msg),
            );

        }else{
            try{

                $error_info = array();
                $ret = $this->CarteamOrderEquipmentCompleteService->equipmentComplete( $req, $error_info );
                if( !$ret )
                {
                    if( !empty($error_info) ){
                        $result = $error_info;
                    }else{
                        $result = array(
                            'error_code'=>'100004',
                            'error_msg'=>'产装完成出错',
                            'data'=> array(),
                        );
                    }
                }

            }catch (\Exception $e){
                Logger::warn("exception:%s", var_export($e->getTraceAsString(),true));
                $result = array(
                    'error_code'=>'100003',
                    'error_msg'=>'系统异常',
                    'data'=> array(),
                );
            }
        }

        $this->response->setJsonContent( $result )->send();

        return ;
    }

    /**
     * 退载 密码校验
     * @Route("/reConstruct_verify", methods={"GET", "POST"})
     */
    public function reConstructVerifyAction(){
        $user = $this->session->get('login_user');
        $orderid = $this->request->get('orderid');
        $password = $this->request->get('password');
        $userId = $user->id;

        $msg = $this->CarteamreConstructVerifyValidation->validate(array('login_user' => $user, 'orderid' => $orderid, 'password' => $password));
        if(count($msg)){
            $result = array(
                'error_code' => 1001,
                'error_msg' => '参数校验错误',
                'data' => array(),
            );
        }else {
            try {
                $ret = $this->UserService->checkPasswordWhenLogin($userId, $password);
                if ($ret) {
                    $ret = $this->OrderFreightReconstructService->tuizaiOrder($orderid);
                    if ($ret)
                        $result = array(
                            'error_code' => '0',
                            'error_msg' => '退载成功',
                            'data' => array(),
                        );
                    else
                        $result = array(
                            'error_code' => '10004',
                            'error_msg' => '密码验证成功，退载失败',
                            'data' => array(),
                        );
                } else
                    $result = array(
                        'error_code' => '10003',
                        'error_msg' => '退载密码验证错误',
                        'data' => array(),
                    );
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100003',
                    'error_msg' => '退载--系统异常',
                    'data' => array(),
                );
            }
        }
        Logger::info('退载, data: '.var_export($result, true));
        $this->response->setJsonContent($result)->send();
        return;
    }


    /**
     *  修改历史记录
     * @Route("/modify_history", methods={"GET", "POST"})
     */
    public function modifyHistoryAction(){
        $result = array('error_code'=>'0', 'error_msg'=>'', 'data'=> array(),);
        $orderId = $this->request->get('orderid');
        $user  = $this->getUser();
        $order = OrderFreight::findFirst("id=$orderId");
        $data  = array();
        $rejected = $user->id != $order->carrier_userid && $user->id != $order->freightagent_user ;
        if( empty( $order ) || empty( $user ) || $rejected ){
            $data = array('error_code'=>'100001', 'error_msg'=>'您无权查看此订单的修改历史。', 'data'=> array(),);
            $res = false;
        }else{
            try{
                $res = $this->OrderLogService->getOrderLogRecords($orderId, $data);
            }catch(\Exception $e){
                Logger::warn($e->getMessage());
                $data = array('error_code'=>'100003', 'error_msg'=>'系统异常', 'data'=> array(),);
                $res = false;
            }
        }
        if( $res ){
            $result['data']       = $data;
            $result['error_code'] = 0 ;
            $result['error_msg']  = "";
            $this->response->setJsonContent( $result )->send();
        }else
          $this->response->setJsonContent( $data )->send();
    }


    /** 退载重建 重建
     *@Route("/reConstruct", methods={"GET", "POST"})
     */
    public function reConstructAction(){
        $user = $this->session->get('login_user');
        $userid = $user->id;
        $password = $this->request->get('password');
        $orderid = $this->request->getQuery('orderid');
        $shipping_company = $this->request->getQuery('shipping_company');
        $ship_name = $this->request->getQuery('ship_name');
        $ship_ticket = $this->request->getQuery('ship_ticket');
        $tidan_code = $this->request->getQuery('tidan_code');
        $yard = $this->request->getQuery('yard');

        $product_box_type = $this->request->getQuery('product_box_type');
        $box_20gp_count = $this->request->getQuery('box_20gp_count');
        $box_40gp_count = $this->request->getQuery('box_40gp_count');
        $box_40hq_count = $this->request->getQuery('box_40hq_count');
        $product_name = $this->request->getQuery('product_name');//非必须
        $product_desc = $this->request->getQuery('product_desc');//非必须
        $product_weight = $this->request->getQuery('product_weight');
        $msg = $this->CarteamReconstructValidation->validate(array('login_user' => $user, 'password' => $password, 'orderid' => $orderid, 'ship_ticket' => $ship_ticket, 'tidan_code' => $tidan_code,
            'product_box_type' => $product_box_type, 'box_20gp_count' => $box_20gp_count, 'box_40gp_count' => $box_40gp_count, 'box_40hq_count' => $box_40hq_count, 'product_weight' => $product_weight));
        if(count($msg)) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }
        else {
            $shipping_company_id = $this->OrderFreightReconstructService->getShipCompanyId($shipping_company);
            $ship_name_id = $this->OrderFreightReconstructService->getShipId($ship_name);
            $yard_id = $this->OrderFreightReconstructService->getYardId($yard);
            if($shipping_company_id == 0 || $ship_name_id == 0 || $yard_id == 0){
                Logger::warn('船公司名字，船名字， 堆场名字错误');
                $result = array(
                    'error_code' => 10001,
                    'error_msg' => '系统异常',
                    'data' => '',
                );
            }
            else {
                $ship_info = array(
                    'shipping_company_id' => $shipping_company_id,
                    'ship_name_id' => $ship_name_id,
                    'ship_ticket' => $ship_ticket,
                    'tidan_code' => $tidan_code,
                    'yard_id' => $yard_id,
                );
                $product_info = array(
                    'product_box_type' => $product_box_type,
                    'box_20gp_count' => $box_20gp_count,
                    'box_40gp_count' => $box_40gp_count,
                    'box_40hq_count' => $box_40hq_count,
                    'product_name' => $product_name,
                    'product_desc' => $product_desc,
                    'product_weight' => $product_weight,
                );
                $isSelf = $this->UserService->checkPasswordWhenLogin($userid, $password);
                if ($isSelf) {
                    try {
                        $data = $this->OrderFreightReconstructService->reConstruct($orderid, $ship_info, $product_info);
                        if ($data)
                            $result = array(
                                'error_code' => 0,
                                'error_msg' => '重建完成',
                                'data' => $data,
                            );
                        else
                            $result = array(
                                'error_code' => 1001,
                                'error_msg' => '系统错误',
                                'data' => $data,
                            );
                    } catch (\Exception $e) {
                        Logger::error('退载重建出错：' . $e->getMessage());
                        $result = array(
                            'error_code' => 100005,
                            'error_msg' => '系统错误',
                            'data' => '',
                        );
                    }
                } else
                    $result = array(
                        'error_code' => 10005,
                        'error_msg' => '密码校验错误',
                        'data' => '',
                    );
            }
        }
        Logger::info('退载重建, data: '.var_export($result, true));
        $this->response->setJsonContent($result)->send();
        return;
    }


    /**
     *@Route("/check_box_code", methods={"GET", "POST"})
     */
    public function  checkCodeAndSealAction(){

        /*
        $req = array(
                    'order_box_id'      => '123',
                    'order_freight_id'  => '123',
                    'box_ensupe'        => '1234',
                    'box_code'          => '12345',
                );
         */

        $req = $this->request->getJsonRawBody(true);
        Logger::info("checkCodeAndSealAction Request:{%s}",var_export( $req,true ));
        $result = array( );
        $msg = $this->SaveBoxEnsupeValidation->validate( $req );
        if( count($msg) ){
            $result = array('error_code'=>'10001', 'error_msg'=>'参数校验错误', 'data'=> ArrayHelper::validateMessages($msg),);
        }else{
            try{
                $this->OrderBoxService->checkBoxCodeAndSeal( $req['box_code'] , $req['box_ensupe'] , $req['order_freight_id'] , $result );
            }catch (\Exception $e){
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code'=>'100003',
                    'error_msg'=>'系统异常',
                    'data'=> array(),
                );
            }
        }
        $this->response->setJsonContent( $result )->send();

        return ;
    }
}