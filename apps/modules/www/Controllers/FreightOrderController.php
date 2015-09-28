<?php
use Library\Helper\StringHelper;
use Library\Helper\YuDanNoHelper;
use Library\Log\Logger;


/**
 * @RoutePrefix("/freight/order")
 */
class FreightOrderController extends ControllerBase
{

    /**
     * 货代发起订单页--选择进出口页面
     *
     * @Get("/choose")
     */
    public function  chooseAction(){
        $user = $this->getUser();
        if( $user->usertype != $this->constant->usertype->freight_agent ){ //
            return $this->forwardError(array("error_code" => 100001,'error_msg' => "车队不允许创建订单。" ));
        }
        $docks                      = array(); // 港口城市列表
        $importTypes                = array(); // 进出口类型
        $this->data['dockList']     = $docks;
        $this->data['importTypes']  = $importTypes;
        return $this->view->pick("order/page/order_choose")->setVar("data", $this->data);
    }



    /**
     * 货代发起订单页--简易下单页面
     *
     * @Get("/new")
     */
    public function  newAction(){
        $user = $this->getUser();
        // 车队不允许建单
        if( $user->usertype != $this->constant->usertype->freight_agent ){ //
            return $this->forwardError(array("error_code" => 100001,'error_msg' => "车队不允许创建订单。" ));
        }
        // 货代必须认证通过
        $agent = FreightagentUser::findFirst("userid=$user->id");
        $authorized = $agent->audit_status ;
        if( $authorized !=4 ){
            return $this->forwardError(array("error_code" => 100002,'error_msg' => "认证未通过，不允许创建订单，请补全认证信息。" ));
        }

        if( $this->request->isGet() ){
            $dock_city_code = $this->request->getQuery('dock_city_code');
            $import_type = $this->request->getQuery('import_type');
        }else if( $this->request->isPost() ){
            $dock_city_code = $this->request->getPost('dock_city_code');
            $import_type = $this->request->getPost('import_type');
        }
        // 承运方列表（默认是 车队）
        $carteamList = $this->CarTeamService->listAll();
        $yudanHelp   =  new YuDanNoHelper( 12 );
        $data = array(
            "yudan_code"        => $yudanHelp->nextId(),
            'dock_city_code'    => $dock_city_code,
            'import_type'       => $import_type,
            'carteamList'       => $carteamList,
            "date"              => StringHelper::strToDate( time(),"Y-m-d")
        );
        $this->data['data'] = $data;
        return $this->view->pick("order/page/order_new")->setVar("data", $this->data);
    }


    /** 导出箱号铅封号
     * @Get("/export_box_info")
     */
    public function exportBoxAction()
    {
        $orderId = $this->request->get("orderid");
        $result = array();
        try {
            $path = $this->WordDocService->makeCodeAndSealWord($orderId, $result);
        } catch (\Exception $e) {
            Logger::warn("downloadProAddressAction error :%s", $e->getMessage());
        }
        if ( empty( $path ) ) {
            $this->forwardError($result);
        } else {
            $fileName = $result['fileName'];
            $this->response->setContentType("Application/msword", "UTF-8")->setContent(file_get_contents($path));
            unlink($path);
            return $this->response->setHeader("Content-Disposition", "inline; filename=" . $fileName );
        }
    }


}