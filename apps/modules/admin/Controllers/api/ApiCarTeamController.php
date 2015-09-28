<?php

use Library\Helper\ArrayHelper;
use Library\Log\Logger;
use Modules\admin\Validation\ApiCarTeamUserValidation;

/**
 * Auth haibo
 * 车队信息管理
 * @RoutePrefix("/api/carTeam")
 */
class ApiCarTeamController extends ApiControllerBase
{

    /**
     * 司机管理
     * 功能: 导入司机
     * @Route("/importDriver", methods={"GET", "POST"})
     */
    public function importDriverAction()
    {
        try {
            $ret = $this->request->hasFiles();
            if( !$ret) {
                Logger::warn('importDriver :没有读取到文件');
                return  $this->sendBack('没有接收到文件');
            }

            $teamid = intval( $this->request->get('team_id'));
            if( $teamid < 1){
                Logger::error('teamid 没有传');
                return $this->sendBack('缺少team_id参数');
            }

            $files = $this->request->getUploadedFiles()[0];
            $suffix = $files->getExtension();
            if( $suffix!='xls' && $suffix!='xlsx') {
                Logger::warn('importDriver :后缀不正确或无法获取后缀');
                return  $this->sendBack('文件格式不正确');
            }

            $tempDir = $this->config->application->tempDir;
            $isOk = $files->moveTo( $tempDir.$files->getName());
            if( !$isOk) {
                Logger::error('importDriver : file->moveTo 失败');
                return $this->sendBack('缓存文件失败，请联系管理员');
            }

            $this->ret = $this->DataIOService->importDriver($files->getName(), $teamid);
            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->IMPORT_DRIVER,
                json_encode(['adminuser_id'=>$loginusr->id]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->USER,
                $teamid,
                $loginusr['username'].'导入了一些司机'
            );
            Logger::info('importDriver info:' . $this->ret['error_msg']);

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }
        return $this->sendBack();
    }


    /**
     * 功能: 导出车队用户到excel
     * @Route("/dumpCarTeam", methods={"GET", "POST"})
     */
    public function dumpCarTeamAction()
    {
        try {
            $arr = array();

            $this->ret = $this->DataIOService->dumpCarTeam(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('status')
            );

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DUMP_WWW_USER,
                '',
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                0,
                '导出了WWW用户'
            );
            Logger::info('dumpCarTeam info:' . $this->ret['error_msg']);

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }


    /**
     * 车队用户审核详情
     * @Route("/auditDetail", methods={"GET"})
     */
    public function auditDetailAction( )
    {
        try {
            if( !$this->paramVerify(new ApiCarTeamUserValidation('auditDetail'))) {
                return $this->sendBack('carTeam/error');
            }

            $team_id = intval( $this->request->get('id'));
            $type = $this->constant->usertype->carteam;
            $this->ret['data'] = $this->LGAuditService->auditInfo( $team_id, $type);

            Logger::info("auditDetailLook: ".var_export($this->ret['data'],true));

        }catch(\Exception $e) {
            $this->ret['data_sum'] = 0;
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return $this->sendBack();
    }


    /**
     * 车队管理 审核通过
     * @Route("/auditPass", methods={"GET", "POST"})
     * @return Response
     */
    public function auditPassAction()
    {
        try {
            if( !$this->paramVerify(new ApiCarTeamUserValidation('auditPass'))) {
                $this->sendBack();
            }

            $ids = $this->request->get('id');
            $sum = 0;
            foreach($ids as $id) {
                $ret = $this->LGAuditService->carteamAudit($id, true);
                empty($ret) && $sum++;
            }

            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT_CARTEAM,
                json_encode(['teamids'=>$ids]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                $id,
                $loginusr['username'].'审核通过了车队'
            );


            $this->ret['error_msg'] = '共修改了'.$sum.'项';
            Logger::info('auditPass sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }

    /**
     * 车队管理 审核驳回
     * @Route("/auditReject", methods={"GET", "POST"})
     * @return Response
     */
    public function auditRejectAction( )
    {
        try {
            if( !$this->paramVerify(new ApiCarTeamUserValidation('auditReject'))) {
                return  $this->sendBack();
            }

            $ids = $this->request->get('id');
            $msgs = $this->request->get('msg');

            $sum = 0;
            foreach($ids as $k=>$id) {
                $ret = $this->LGAuditService->carteamAudit($id, false, $msgs[$k]);
                empty($ret) && $sum++;
            }

            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT_CARTEAM,
                json_encode(['teamids'=>$ids]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                $id,
                $loginusr['username'].'审核驳回了车队'
            );


            $this->ret['error_msg'] = '共修改了'.$sum.'项';
            Logger::info('auditReject sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }


    /**
     * 车队管理 锁定
     * @Route("/lockCarTeam", methods={"GET", "POST"})
     * @return Response
     */
    public function lockCarTeamAction()
    {
        try{

            if( !$this->paramVerify( new ApiCarTeamUserValidation('lockCarTeam')))
                return  $this->sendBack();

            $ids = $this->request->get('id');
            $sum = $this->CarTeamService->setStatus($ids, 'lock');

            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT_CARTEAM,
                json_encode(['teamids'=>$ids]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                '',
                $loginusr['username'].'锁定了车队'
            );

            $this->ret['error_msg'] = '共修改了'.$sum.'项';
            Logger::info('loockCarTeam  sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }


    /**
     * 车队管理 解锁
     * @Route("/unlockCarTeam", methods={"GET", "POST"})
     * @return Response
     */
    public function unlockCarTeamAction( )
    {
        try{
            if( !$this->paramVerify( new ApiCarTeamUserValidation('unlockCarTeam')))
                return  $this->sendBack();

            $ids = $this->request->get('id');
            $sum = $this->CarTeamService->setStatus($ids,'unlock');

            $this->ret['error_msg'] = '共修改了'.$sum.'项';
            Logger::info('unlockCarTeam sum:' .$sum);

            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT_CARTEAM,
                json_encode(['teamids'=>$ids]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                '',
                $loginusr['username'].'解锁了车队'
            );

        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }


    /**
     * 车队管理 删除
     * @Route("/delCarTeam", methods={"GET", "POST"})
     * @return Response
     */
    public function delCarTeamAction()
    {
        try{
            if( !$this->paramVerify( new ApiCarTeamUserValidation('delCarTeam')))
                return $this->sendBack();

            $ids = $this->request->get('id');
            $sum = $this->CarTeamService->setStatus($ids, 'delete');
            $loginusr = $this->AdminUserService->getSessionUser();
            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->AUDIT_CARTEAM,
                json_encode(['teamids'=>$ids]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->WWW_USER,
                '',
                $loginusr['username'].'删除了车队'
            );

            $this->ret['error_msg'] = '共修改了'.$sum.'项';
            Logger::info('delCarTeam sum:' .$sum);
        } catch (\Exception $e) {
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return $this->sendBack();
        }

        return $this->sendBack();
    }








//-------------------------被砍------------------------------//
    //    /**
//     * 功能:公司管理员审核通过
//     * @Route("/comAdminPass", methods={"GET", "POST"})
//     */
//    public function comAdminPassAction()
//    {
//        try {
//
//            if( $this->paramVerify(new ApiCarTeamUserValidation('comAuditPass'))) {
//                return  $this->sendBack();
//            }
//
//            $team_id = intval($this->request->get('id'));
//            $ret = $this->ApplyAdminService->passAdminApply($team_id);
//
//            Logger::info('comAdminPass info:' . $ret['error_msg']);
//
//        } catch (\Exception $e) {
//            $this->ret['error_msg'] = '网络异常';
//            $this->ret['error_code'] = -1;
//            Logger::warn($e->getMessage());
//            return $this->sendBack();
//        }
//
//        return $this->sendBack($ret['error_code']==0);
//    }
//
//    /**
//     * 功能:公司管理员审核驳回
//     * @Route("/comAdminReject", methods={"GET", "POST"})
//     */
//    public function comAdminRejectAction()
//    {
//        try {
//
//            if( $this->paramVerify(new ApiCarTeamUserValidation('comAuditReject'))) {
//                return  $this->sendBack();
//            }
//
//            $team_id = intval($this->request->get('id'));
//            $ret = $this->ApplyAdminService->rejectAdminApply($team_id);
//
//            Logger::info('comAdminReject info:' . $ret['error_msg']);
//
//        } catch (\Exception $e) {
//            $this->ret['error_msg'] = '网络异常';
//            $this->ret['error_code'] = -1;
//            Logger::warn($e->getMessage());
//            return $this->sendBack();
//        }
//
//        return $this->sendBack($ret['error_code']==0);
//    }
//-------------------------被砍------------------------------//


}