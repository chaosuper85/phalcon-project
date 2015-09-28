<?php

use Library\Log\Logger;
use Modules\admin\Validation\CarTeamUserValidation;

/**
 * haibo
 * 船管理
 * @RoutePrefix("/ship")
 */
class ShipController extends ControllerBase
{

    /**
     * 按信息显示所有船
     *
     * @Route("/ships", methods={"GET", "POST"})
     */
    public function shipsAction()
    {
        try{
//        if( $this->paramVerify( new CarTeamUserValidation('delCarTeam')))
//            return $this->sendBack();

            $name = $this->request->get('name');
            unset($_REQUEST['name']);
            $this->ret = $this->ShipNameService->ships($name);
$this->bo_dump();

            Logger::info( 'ships  sum:'. count($this->ret['data_sum']) );
        }catch(\Exception $e) {
            Logger::error("ships error msg:".$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/resource/ship');
    }

    /**
     * 按信息显示所有船公司
     *
     * @Route("/shipComs", methods={"GET", "POST"})
     */
    public function shipComsAction()
    {
        try{
//        if( $this->paramVerify( new CarTeamUserValidation('delCarTeam')))
//            return $this->sendBack();


            $name = $this->request->get('name');
            unset($_REQUEST['name']);
            $this->ret = $this->ShippingCompanyService->shipComs($name);
$this->bo_dump();
            Logger::info('shipComs  sum:'. $this->ret['data_sum']);
        }catch(\Exception $e) {
            Logger::error("shipComs error msg:".$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('page/resource/ship_company');
    }





}