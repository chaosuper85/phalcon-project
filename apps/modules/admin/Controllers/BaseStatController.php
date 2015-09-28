<?php

use Library\Log\Logger;
use Modules\admin\Validation\BasicStaticValidation;

/**
 * 迁移：haibo
 * @RoutePrefix("/baseStat")
 */
class BaseStatController extends ControllerBase
{
    /**
     * @Route("/", methods={"GET", "POST"})
     *
     * 最近24小时 ：短信验证码  图片验证   注册  登陆    创建订单  完成订单
     *
     */

    public function indexAction()
    {
        try{
            //参数合法校验
            $validator = new BasicStaticValidation('page');
            if( !$this->paramVerify($validator)) {
                $this->sendBack('basicStatics/page');
            }

            $this->ret = $this->PVService->queryPVUVInfo(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('search_type')
            );

        }
        catch (\Exception $e) {
            Logger::error('page_static :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('basicStatics/page');
    }


    /**
     * 基础统计  页面数据统计
     *
     * @Route("/page", methods={"GET", "POST"})
     * @return Response
     */
    public function pageAction()
    {
        try{
            //参数合法校验
            $validator = new BasicStaticValidation('page');
            if( !$this->paramVerify($validator)) {
                $this->sendBack('basicStatics/page');
            }

            $this->ret = $this->PVService->queryPVUVInfo(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('search_type')
            );

        }
        catch (\Exception $e) {
            Logger::error('page_static :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('basicStatics/page');
    }

    /**
     * 基础统计  用户数据统计
     * @Route("/user", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function userAction()
    {
        try{
            $this->ret = $this->UserStaticService->queryUserStat(
                $this->request->get('begin_time'),
                $this->request->get('end_time'),
                $this->request->get('platform'),
                $this->request->get('version'),
                $this->request->get('user_type')
            );

        }
        catch (\Exception $e) {
            Logger::error('user_static :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('basicStatics/user');
    }

    /**
     * 短信发送量
     *
     * @Route("/msg", methods={"GET", "POST"})
     * @return Response
     */
    public function sendMsgStatAction()
    {
        try{
//            //参数合法校验
//            $validator = new BasicStaticValidation('msg');
//            if( !$this->paramVerify($validator)) {
//                $this->sendBack('basicStatics/sendMsg');
//                //$this->sendBack('error/');
//            }

            $ret = $this->SmsHistoryService->querySmsHistory();
        }
        catch (\Exception $e) {
            Logger::error('msg_static :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('basicStatics/sendMsg');
    }

    /**
     * 图片验证码触发量
     *
     * @Route("/pic", methods={"GET", "POST"})
     * @return Response
     */
    public function picVerifyStatAction( )
    {
        try{
            //参数合法校验
//            $validator = new BasicStaticValidation('pic');
//            if( !$this->paramVerify($validator)) {
//                $this->sendBack('basicStatics/picVerifyStat');
//                //$this->sendBack('error/');
//            }

            $ret = $this->ActivityLogService->picVerifyStat();
$this->bo_dump();
            return $this->sendBack('basicStatics/picVerifyStat', $ret);
        }
        catch (\Exception $e) {
            Logger::error('pic_static :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('basicStatics/picVerifyStat', $ret);
    }

    /**
     * 日志统计
     * @Route("/log", methods={"GET", "POST"})
     * @return Response
     */
    public function logsAction( )
    {
        try{
        if( !$this->paramVerify( new BasicStaticValidation('log')))
            return $this->forwardError();

            $this->ret = $this->ActivityLogService->listAdminLog();
$this->bo_dump();

            Logger::info( 'ships  sum:'. count($this->ret['data_sum']) );
        }catch(\Exception $e) {
            Logger::error("logs error msg:".$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/stat/log');
    }





}