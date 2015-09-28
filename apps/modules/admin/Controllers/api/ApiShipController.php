<?php

use Library\Log\Logger;
use Modules\admin\Validation\ApiShipValidation;

/**
 * 海波
 * 船管理API
 * @RoutePrefix("/api/ship")
 */
class ApiShipController extends ApiControllerBase
{
    /**
     * 修改船资料
     * @Route("/alterShip", methods={"POST","GET"})
     * @return Response
     */
    public function alterShipAction()
    {
        try {
            if( !$this->paramVerify(new ApiShipValidation('alterShip'))) {
                $this->sendBack();
            }

            $msg = $this->ShipNameService->alterShip(
                $this->request->get('id',null,false),  //必传
                $this->request->get('name_zh',null,false),
                $this->request->get('name_en',null,false),
                $this->request->get('mobile',null,false),
                $this->request->get('address',null,false),
                $this->request->get('contact_name',null,false),
                $this->request->get('ship_code',null,false)
            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * 增加船
     * @Route("/addShip", methods={"POST","GET"})
     * @return Response
     */
    public function addShipAction()
    {
        try {
            if( !$this->paramVerify(new ApiShipValidation('addShip'))) {
                return  $this->sendBack();
            }

            $msg = $this->ShipNameService->addShip(
                $this->request->get('name_zh'), //必传
                $this->request->get('name_en'), //必传
                $this->request->get('mobile'),
                $this->request->get('address'),
                $this->request->get('contact_name'),
                $this->request->get('ship_code')
            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * 增加船公司资料
     * @Route("/addCompany", methods={"GET", "POST"})
     * @return Response
     */
    public function addCompanyAction()
    {
        try {
            if( !$this->paramVerify(new ApiShipValidation('addCompany'))) {
                return  $this->sendBack();
            }

            $msg = $this->ShippingCompanyService->addShipCom(
                $this->request->get('name_zh'), //必传
                $this->request->get('name_en'), //必传
                $this->request->get('com_type'),//必传
                $this->request->get('com_code'),
                $this->request->get('mobile'),
                $this->request->get('address'),
                $this->request->get('contact_name')

            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * 修改船公司资料
     * @Route("/alterCompany", methods={"GET", "POST"})
     * @return Response
     */
    public function alterCompanyAction()
    {
        try {
            if( !$this->paramVerify(new ApiShipValidation('alterCompany'))) {
                return  $this->sendBack();
            }

            $msg = $this->ShippingCompanyService->alterShipCom(
                $this->request->get('id',null,false),//必传
                $this->request->get('name_zh',null,false),
                $this->request->get('name_en',null,false),
                $this->request->get('com_code',null,false),
                $this->request->get('mobile',null,false),
                $this->request->get('address',null,false),
                $this->request->get('contact_name',null,false),
                $this->request->get('status',null,false)   //type
            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }


    /**
     * 导入船公司
     * @Route("/importCompany", methods={"GET", "POST"})
     * @return Response
     */
    public function importCompanyAction()
    {
        try {
//            $ret = $this->request->hasFiles();
//            if( !$ret) {
//                Logger::warn('importDriver :没有读取到文件');
//                return  $this->sendBack('没有接收到文件');
//            }
//
//            $files = $this->request->getUploadedFiles()[0];
//            $suffix = $files->getExtension();
//            if( $suffix!='xls' && $suffix!='xlsx') {
//                Logger::warn('importShipCom :后缀不正确或无法获取后缀');
//                return  $this->sendBack('文件格式不正确');
//            }
//
//            $tempDir = $this->config->application->tempDir;
//            $isOk = $files->moveTo( $tempDir.$files->getName());
//            if( !$isOk) {
//                Logger::error('importShipCom : file->moveTo 失败');
//                return $this->sendBack('文件缓存失败，请联系管理员');
//            }

            $this->ret['msg'] = $this->DataIOService->importShipCom('船公司列表.xls'/*$files->getName()*/);

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();



    }

}