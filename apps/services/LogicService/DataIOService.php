<?php   namespace Services\LogicService;

use Library\Helper\StatusHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Exception;

/**
 *  haibo
 *  数据导入导出
 *  eg:导出用户，导入用户，导出业务统计，导入业务统计
 */
class DataIOService extends Component
{


    const ERR_EXCEL_READ    = '文件格式不符,或模板有误,请联系管理员';
    const ERR_EXCEL_WIRTE   = '已经被拒绝写入';
    const ERR_EXCEL_BIG     = '文件过大，请拆分';
    const EXCEL_CARTEAM_TITLE   = '车队人员一览.xls';

    protected $ret = array();

    /**
     * 功能:导出车队
     */
    public function dumpCarTeam($begin_time='', $end_time='', $status=0)
    {
        //read
        $page_size = 2000;  //一次导出2000条最多
        $this->ret = $this->CarTeamService->queryCarTeams(
            $begin_time, $end_time, 0, 0, 0, 0, 0,$status, 0, 1, $page_size
        );

        if( $this->ret['data_sum']==0) {
            $this->ret['error_msg'] = '没有数据';
            $this->ret['error_code'] = 1;
            Logger::info('导出车队: 没有车队数据');
            return $this->ret;
        }

        //非中文部分转中文
        $hashPlatForm = $this->constant->PLATFORM_ENUM;
        foreach($this->ret['data'] as $k=>&$v) {
            if( isset($v['regist_platform']))
                $v['regist_platform'] = $hashPlatForm[$v['regist_platform']];
            $v['status'] = StatusHelper::numToWord('ACCOUNT', intval($v['status']));
            $v['audit_status'] = StatusHelper::numToWord('USER', intval($v['audit_status']));
        }

        //write
        $isOk = $this->ExcelService->write($this->ret['data'], 'carteam');
        if( !$isOk) {
            $this->ret['error_code'] = 2;
            $this->ret['error_msg']  = self::ERR_EXCEL_WIRTE;

            Logger::info('导出车队用户 生成文件错误');
            return $this->ret;
        }

    }

    /**
     * 功能:导出货代用户
     */
    public function dumpAgent($begin_time='', $end_time='' , $status=0)
    {
        //read
        $page_size = 2000;  //一次导出2000条
        $this->ret = $this->AgentService->queryCargoes(
            $begin_time, $end_time, 0, 0, 0, 0, 0,$status, 0, 1, $page_size
        );

        if( $this->ret['data_sum']==0) {
            $this->ret['error_msg'] = '没有数据';
            $this->ret['error_code'] = 1;
            Logger::info('导出货代用户: 没有货代数据');
            return $this->ret;
        }

        //非中文部分转中文
        $hashPlatForm = $this->constant->PLATFORM_ENUM;
        foreach($this->ret['data'] as $k=>&$v) {
            if( isset($v['regist_platform']))
                $v['regist_platform'] = $hashPlatForm[$v['regist_platform']];
            $v['status'] = StatusHelper::numToWord('ACCOUNT', intval($v['status']));
            $v['audit_status'] = StatusHelper::numToWord('USER', intval($v['audit_status']));
        }

        //write
        $ret = $this->ExcelService->write($this->ret['data'], 'agent');
        if( !$ret) {
            $this->ret['error_code'] = 2;
            $this->ret['error_msg']  = self::ERR_EXCEL_WIRTE;
            Logger::info('导出货代用户： 生成文件错误');
            return $this->ret;
        }

    }

    /**
     * 功能:导入司机
     */
    public function importDriver($dir, $team_id)
    {
        $arr = array();
        $this->ExcelService->read($dir,'driver',$arr,[0=>1,1=>3]);
        if( empty($arr)) {
            $this->ret['error_code'] = 3;
            $this->ret['error_msg']  = self::ERR_EXCEL_READ;
            return $this->ret;
        }

        return $this->DriverService->create($arr, $team_id);
    }


    /**
     * 功能:导入船公司
     */
    public function importShipCom($dir)
    {
        $arr = array();
        $this->ExcelService->read($dir,'shipCom',$arr,[0=>0,1=>1]);
        if( empty($arr)) {
            $this->ret['error_code'] = 3;
            $this->ret['error_msg']  = self::ERR_EXCEL_READ;
            return $this->ret;
        }

        $type = $this->order_config->CREATE_TYPE->CHECKED;
        $sum = 0;
        foreach($arr as $v) {
            $err_msg = $this->ShippingCompanyService->addShipCom($v['china_name'], $v['company_code'], $type, $v['company_code'], 0, '', '');
            !$err_msg && $sum++;
        }
        $err_sum = count($arr)-$sum;

        Logger::info('导入了'.$sum.'条记录'.' 失败了'.$err_sum.'条');
        return '导入了'.$sum.'条记录'.' 失败了'.$err_sum.'条';
    }








}
