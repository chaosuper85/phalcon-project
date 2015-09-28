<?php

use Library\Log\Logger;
use Modules\admin\Validation\ApiAgentValidation;

/**
 * 货代管理api
 * auth haibo
 * @RoutePrefix("/api/agent")
 */
class ApiAgentController extends ApiControllerBase
{

    /**
     * 货代详情
     * @Route("/auditDetail", methods={"GET"})
     */
    public function auditDetailAction( )
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('auditDetail'))) {
                return $this->sendBack('carTeam/error');
            }

            $id = intval($this->request->get('id'));
            $type = $this->constant->usertype->freight_agent;
            $this->ret['data'] = $this->LGAuditService->auditInfo( $id, $type);

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->QUERY_AUDIT,
                json_encode(['agent_id'=>$id]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                $id,
                '查看了货代账户的审核信息.'
            );
            Logger::info("auditLook: ".var_export($this->ret['data'],true));
        }catch(\Exception $e) {
            $this->ret['error_code'] = -1;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }


    /**
     * 功能: 导出货代用户到excel
     * @Route("/dumpAgent", methods={"GET", "POST"})
     */
    public function dumpAgentAction()
    {
        try {
            $this->ret = $this->DataIOService->dumpAgent(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('status')
            );

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DUMP_WWW_USER,
                0,
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '导出了货代用户信息到excel.'
            );
            Logger::info('dumpAgent info:' . $this->ret['error_msg']);

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }

    /**
     * 货代管理 审核通过
     * @return Response
     *
     * @Get("/auditPass")
     */
    public function auditPassAction()
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('auditPass'))) {
                return $this->sendBack('carTeam/error');
            }

            $ids = $this->request->get('id');
            $sum = 0;
            foreach($ids as $id) {
                $ret = $this->LGAuditService->agentAudit($id, true);
                empty($ret) && $sum++;
            }

            $this->ret['error_msg'] = '共修改了'.$sum.'项';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT,
                json_encode($ids),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '审核通过了用户. '.$this->ret['error_msg']
            );
            Logger::info('auditPass sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }

    /**
     * 货代管理 审核驳回
     * @return Response
     *
     * @Get("/auditReject")
     */
    public function auditRejectAction( )
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('auditReject'))) {
                return $this->sendBack('carTeam/error');
            }

            $ids = $this->request->get('id');
            $msgs = $this->request->get('msg');

            $sum = 0;
            foreach($ids as $k=>$id) {
                $ret = $this->LGAuditService->agentAudit($id, false, $msgs[$k]);
                empty($ret) && $sum++;
            }

            $this->ret['error_msg'] = '共修改了'.$sum.'项';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT,
                json_encode($ids),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '审核驳回了用户. '.$this->ret['error_msg']
            );
            Logger::info('auditReject sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }

    /**
     * 货代管理 锁定
     * @return Response
     *
     * @Get("/lockAgent")
     */
    public function lockCargoAction(  )
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('lockAgent'))) {
                return $this->sendBack('carTeam/error');
            }

            $ids = $this->request->get('id');
            $sum = $this->AgentService->setStatus($ids, 'lock');

            $this->ret['error_msg'] = '共修改了'.$sum.'项';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ALTER_WWW_USER,
                json_encode($ids),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '锁定了用户. '.$this->ret['error_msg']
            );
            Logger::info('lockCargo  sum:' .$sum);
        }catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return $this->sendBack();
    }

    /**
     * 货代管理 解锁
     *
     * @return Response
     *
     * @Get("/unlockAgent")
     */
    public function unlockCargoAction(  )
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('unlockAgent'))) {
                return $this->sendBack('carTeam/error');
            }

            $ids = $this->request->get('id');
            $sum = $this->AgentService->setStatus($ids,'unlock');

            $this->ret['error_msg'] = '共修改了'.$sum.'项';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ALTER_WWW_USER,
                json_encode($ids),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '解锁了用户. '.$this->ret['error_msg']
            );
            Logger::info('unlockAgent sum:' .$sum);
        }catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }

    /**
     * 货代管理 删除
     * @return Response
     *
     * @Get("/delAgent")
     */
    public function delAgentAction( )
    {
        try {
            if( !$this->paramVerify(new ApiAgentValidation('delAgent'))) {
                return $this->sendBack();
            }

            $ids = $this->request->get('id');
            $sum = $this->AgentService->setStatus($ids, 'delete');

            $this->ret['error_msg'] = '共修改了'.$sum.'项';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DEL_WWW_USER,
                json_encode($ids),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '删除了用户. '.$this->ret['error_msg']
            );
            Logger::info('delAgent sum:' .$sum);
        }catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }

}