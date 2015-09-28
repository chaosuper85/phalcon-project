<?php

use Library\Log\Logger;
use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/api/city")
 */
class ApiCityController extends ApiControllerBase
{


    /**
     * @Route("/getProvince", methods={"GET", "POST"})
     */
    public  function getProvinceAction()
    {
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $city = array();
        try{
            $city = $this->di->get('CityService')->getProvince();
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'网络异常',
                'data'=> array(),
            );
        }

        $result['data'] = $city;

        $this->response->setJsonContent( $result )->send();

        return ;
    }

    /**
     * @Route("/getProvinceCitys", methods={"GET", "POST"})
     */
    public  function getProvinceCitysAction() {
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );
        $city = array();
        try{
            $city = $this->CityService->getProvinceCitys();
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'网络异常',
                'data'=> array(),
            );
        }
        $result['data'] = $city;
        $this->response->setJsonContent($result)->send();
        return ;
    }


    /**
     * @Route("/getAll", methods={"GET", "POST"})
     */
    public  function getAllAction()
    {
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        $city = array();
        try{
            $city = $this->di->get('CityService')->getAllLocation();
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code'=>'100003',
                'error_msg'=>'网络异常',
                'data'=> array(),
            );

        }

        $result['data'] = $city;

        $this->response->setJsonContent( $result )->send();

        return ;
    }

    /**
     * @Route("/getSubLocation", methods={"GET", "POST"})
     */
    public  function getSubLocationAction()
    {
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );

        if( $this->request->isGet() ){
            $codeid = $this->request->getQuery('id');
        }else if( $this->request->isPost() ) {
            $codeid = $this->request->getPost('id');
        }
        $city = array();
        $msg = $this->CitySubLocationValidation->validate(array('codeid'=>$codeid));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $city = $this->CityService->getSubLocation($codeid);
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100003',
                    'error_msg' => '网络异常',
                    'data' => array(),
                );
            }
            $result['data'] = $city;
        }
        $this->response->setJsonContent( $result )->send();
        return ;
    }

}