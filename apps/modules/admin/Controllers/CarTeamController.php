<?php

use Library\Log\Logger;
use Modules\admin\Validation\CarTeamUserValidation;

/**
 * @RoutePrefix("/carTeam")
 */
class CarTeamController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 车队管理 Controller
    | 迁移：haibo
    | 待测试
    |--------------------------------------------------------------------------
    */


    /**
     * 按信息显示所有车队
     *
     * @Route("/carTeams", methods={"GET", "POST"})
     */
    public function carTeamsAction()
    {
        try {
            $this->ret = $this->CarTeamService->queryCarTeams(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('name'),
                $this->request->get('team_name'),
                $this->request->get('mobile'),
                intval($this->request->get('audit_status')),
                intval($this->request->get('status')),
                intval($this->request->get('page_no')),
                $this->request->get('page_size')
            );

            Logger::info( 'queryCarTeams  sum:'. count($this->ret['data_sum']) );
        }
        catch (\Exception $e) {
            Logger::error('carTeams :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('page/user/carteam');
    }



    /**
     * 司机管理
     * @Route("/drivers", methods={"GET", "POST"})
     */
    public function driversAction()
    {
        try{
            if( !$this->paramVerify( new CarTeamUserValidation('drivers'))) {
                return  $this->sendBack();
            }

            $this->ret = $this->DriverService->drivers(
                $this->request->get('id'),
                $this->request->get('status'),
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('page_no'),
                $this->request->get('page_size')
            );

            Logger::info( 'CarTeam-drivers  sum:'. $this->ret['data_sum']);

        }catch(\Exception $e) {
            $this->ret['sum'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        $this->sendBack('page/user/driver');
    }


}