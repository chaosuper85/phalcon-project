<?php

use Library\Log\Logger;
use Modules\admin\Validation\AgentValidation;

/**
 * @RoutePrefix("/agent")
 */
class AgentController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 货代管理 Controller
    | 迁移：haibo
    | 待测试
    |--------------------------------------------------------------------------
    */

    /**
     * @Route("/agents", methods={"GET", "POST"})
     */
    public function agentsAction()
    {
        try{
            $this->ret = $this->AgentService->queryCargoes(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('agent_name'),
                $this->request->get('name'),
                $this->request->get('mobile'),
                intval($this->request->get('audit_status')),
                intval($this->request->get('status')),
                intval($this->request->get('page_no')),
                $this->request->get('page_size')
            );

            Logger::info( 'queryCargoes sum:'. var_export($this->ret['data_sum'],true) );
        }
        catch (\Exception $e) {
            Logger::error('agents :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/user/agent');
    }


    /**
     * @Route("/test", methods={"GET", "POST"})
     */
    public function testAction()
    {
        $this->dump('sdf');
        die;
        $lsit = \AdminLog::findFirst( ['conditions'=>'','columns'=>'id'])->toArray();
var_dump($lsit);
        //$this->LGAuditService->carteamAudit(1, false,'未通过');

        die;
        // var_dump($this->ExcelService->write($arr,1));



        $arr  = \CarTeamUser::find(array('columns'=>'id,teamName,status,teamPic'))->toArray();

        $this->ret['data'] = $arr;
        return $this->sendBack('page/test');

       // $this->ActivityLogService->addAdminLog(3,'sdfdsf');
       // $this->JpushService->toOne(2);



//var_dump($this->SmsHistoryService->querySmsHistory());die;

//        $carteam = \CarTeamUser::findFirst(1);
//
//        $status = new   \Library\Helper\StatusHelper($carteam);
//
//die;
//        echo $status->auditing;
//        //$status->set($status,$status->auditing+-)
//
//        die;
//        $carteam = \CarTeamUser::find("status & 16");
//
//        var_dump($carteam->toArray());die;

    }


}