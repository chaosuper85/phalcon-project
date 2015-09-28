<?php

use Library\Helper\ArrayHelper;
use Modules\www\Validation\AgentCreateOrderValidation;
use Phalcon\Http\Response;
use Library\Log\Logger;

/**
 * @RoutePrefix("/api/freight/order")
 */
class ApiFreightOrderController extends ApiControllerBase
{

      static  $extensArr = array("doc","pdf","docx");
    /**
     * @Route("/carteamList", methods={"GET", "POST"})
     */
    public function carteamListAction()
    {


        $result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array(),
        );


        try {

            $carteamList = array(
                0 => array(
                    'carteamId' => '123456',
                    'carteamName' => '车队公司1',
                ),
                1 => array(
                    'carteamId' => '123457',
                    'carteamName' => '车队公司2',
                ),
                2 => array(
                    'carteamId' => '123458',
                    'carteamName' => '车队公司3',
                ),
            );

            $data = array(
                'carteamList' => $carteamList,
            );

            $result['data'] = $data;


        } catch (\Exception $e) {
            Logger::warn($e->getMessage());
            $result = array(
                'error_code' => '100003',
                'error_msg' => '网络异常',
                'data' => array(),
            );
        }

        $this->response->setJsonContent($result)->send();

        return;


    }

    /**   上传提箱单
     * @Route("/upload_tixiang", methods={"GET", "POST"})
     */
    public function uploadTixiangAction()
    {
        $result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array(),
        );
        $user = $this->session->get('login_user');
        try {
            $ret = $this->request->hasFiles();
            if ($ret == true) {
                // 只允许上传 doc pdf
                $files = $this->request->getUploadedFiles();
                $file  = $files[0];

                $extension = $file->getExtension();
                if( ArrayHelper::inArray( $extension, self::$extensArr )){
                    $ret = $this->QiNiuService->uploadFileToQiNiu($file, $user->id);
                    $result['data'] = array('pic_url' => $ret,'fileType' => $extension);
                    Logger::info('upload success');
                }else{
                    $result = array('error_code' => '100000003', 'error_msg' => '上传文件格式不正确', 'data' => array(),);
                }
            } else {
                $result = array(
                    'error_code' => '10001',
                    'error_msg' => '没有上传文件',
                    'data' => array(),
                );
                Logger::warn('no file upload ');
            }
        } catch (\Exception $e) {
            Logger::warn($e->getMessage());

            $result = array(
                'error_code' => '10002',
                'error_msg' => '网络异常',
                'data' => array(),
            );
        }
        Logger::info('uploadTixiangAction return ' . var_export($result, true));
        $this->response->setJsonContent($result)->send();
        return;
    }


    /** 上传产装联系单
     * @Route("/upload_chanzhuang", methods={"GET", "POST"})
     */
    public function uploadChanzhuangAction()
    {


        $result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array(),
        );
        $user = $this->session->get('login_user');
        try {
            $ret = $this->request->hasFiles();
            if ($ret == true) {
                $files = $this->request->getUploadedFiles();
                $file = $files[0];

                $extension = $file->getExtension();
                if( ArrayHelper::inArray( $extension, self::$extensArr )){
                    $ret = $this->QiNiuService->uploadFileToQiNiu($file, $user->id);
                    $result['data'] = array('pic_url' => $ret,'fileType'=> $extension );
                    Logger::info('upload success');
                }else{
                    $result = array('error_code' => '10003', 'error_msg' => '上传文件格式不正确', 'data' => array(),);
                }
            } else {
                $result = array(
                    'error_code' => '10001',
                    'error_msg' => '没有上传文件',
                    'data' => array(),
                );
                Logger::warn('no file upload ');
            }

        } catch (\Exception $e) {
            Logger::warn($e->getMessage());

            $result = array(
                'error_code' => '10002',
                'error_msg' => '网络异常',
                'data' => array(),
            );

        }
        Logger::info('uploadChanzhuangAction return ' . var_export($result, true));
        $this->response->setJsonContent($result)->send();
        return;
    }


    /** 货代确认创建订单
     *  默认 港口 天津 ， 出口类型 出口 0
     *
     * @Route("/new", methods={"GET", "POST"})
     */
    public function newAction()
    {
        /*
            array(
                    'tidan'=> '123456789',
                    'yundan'=> '20150819123456',
                    'carteamId'=> '12345',
                    'tixiang'=> 'http://7xjljc.com2.z0.glb.qiniucdn.com/',
                    'chanzhuang'=> array(
                        0=> 'http://7xjljc.com2.z0.glb.qiniucdn.com/',
                        1=> 'http://7xjljc.com2.z0.glb.qiniucdn.com/',
                )
            );
        */
        $req = $this->request->getJsonRawBody(true);
        Logger::info(" agent create order request:" . var_export($req, true));
        $result     = array('error_code' => '0', 'error_msg' => '创建订单成功。', 'data' => array());
        $validation = new AgentCreateOrderValidation($req);
        $message    = $validation->validate($req);
        $user       = $this->getUser();
        $dockCode   = "tianjin" ; // 天津
        $importType =  1 ; // 出口
        if (count($message)) { //
            $result['error_code'] = 100001;
            $result['error_msg'] = "参数格式不正确";
            $result['data'] = ArrayHelper::validateMessages($message);
        } else {
            try {
                $data = $this->OrderFreightService->createOrderFreight( $user->id, $req['yundan'], $req['carteamId'], $dockCode, $importType, $req["tidan"], $req['tixiang'], $req['chanzhuang']);
                if( empty($data) ){
                    $result['error_code'] = 100002;
                    $result['error_msg']  = "创建订单失败。";
                }
            }catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100003',
                    'error_msg' => '网络异常',
                    'data' => array(),
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return;
    }

    /**
     * @Route("/test", methods={"GET", "POST"})
     */
    public function testAction()
    {


        echo "test";
        die;

    }

    /**
     * @Route("/ladingCodeExist", methods={"GET", "POST"})
     */
    public function  ladingCodeExistAction(){
        $ladingCode =  trim( $this->request->get("tidanCode") );
        if( empty( $ladingCode ) ){
            $result = array('error_code'=> 1000003,'error_msg' => "提单号不能为空。");
        }else{
            $exist  = $this->OrderFreightService->checkLadingCodeExist( $ladingCode );
            if( $exist ){ // 存在
                $result['error_code'] = 1000001;
                $result['error_msg']  = "提单号已经存在";
            }else{
                $result = array('error_code'=> 0,'error_msg'=>"不存在此提单号。");
            }
        }
        return $this->response->setJsonContent( $result );
    }

}
