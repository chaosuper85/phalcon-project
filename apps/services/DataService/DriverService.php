<?php  namespace Services\DataService;
/**
 * Created by PhpStorm.
 * auth : haibo
 * Date: 15/7/22
 * Time: 下午7:43
 */


use CarTeamUser;
use \Phalcon\DiInterface;
use Library\Log\Logger;
use Library\Helper\StatusHelper;
use Phalcon\Mvc\User\Component;

/** 车队服务
 * Class CarTeamService
 * @package Modules\services\DataService
 */
class DriverService extends Component
{

    private function create_usr($name, $mobile)
    {
        $type = $this->constant->usertype->driver;
        $user = \Users::findFirst( ["usertype = $type AND mobile= ?1",'bind'=>[1=>$mobile]]);
        if($user) {
            Logger::warn('导入司机-手机号重复.'.$mobile);
            return false;
        }

        $user = $this->UserService->create($mobile, 'no_pwd', 'driver', $mobile.'_sj'.$name, 'no_slat', 'no_token', 'no_ip', $name);
        if( !$user) {
            Logger::error('driverSerivce:'.var_export($user->getMessages(),true));
            return false;
        }else {
            return $user;
        }

    }

    /**
     * 功能:创建司机
     * 备注:用于司机excel导入。导入某个车队的司机,有则更新，没有则创建
     * @param $data
     * @param $team_id
     * @return array
     */
    public function create(&$data, $team_id)
    {
        if( !is_array($data))
            $data = array($data);

        $ret = array('error_code'=>0,'error_msg'=>'成功');
        $sum_update = 0;
        $sum_new = 0;

        try{
            $this->db->begin();   //调用的创建user接口也有事务
            foreach($data as $k=>$v)
            {
                //筛选的条件有两个，一个是team_id用来确定司机属于的车队。 一个是car_number用来分辨每个司机。
                //car_number 是独一无二的。

                $driver = \TbDriver::findFirst( ["car_number = ?1 AND team_id = $team_id",'bind'=>[1=>$v['car_number']]]);
                if( $driver) {//更新
                    Logger::warn(" car_number  already exist , car_number:".$v['car_number']." belongs to team:".$driver->team_id);

                    $driver->id_number = $v['id_number'];
                    $driver->drive_number = $v['drive_number'];
                    $driver->car_trans_auth = $v['car_trans_auth'];
                    $driver->driver_name = $v['driver_name'];
                    $isOk = $driver->update();
                    $isOk && $sum_update++;
                }
                else {//创建司机
                    $usr = $this->create_usr($v['driver_name'], $v['mobile']);
                    if( !$usr) {
                        $ret['error_code'] = 101;
                        $ret['error_msg'] = '创建用户失败.手机号重复。车牌号:'.$v['car_number'].' 手机号:'.$v['mobile'];
                        return  $ret;
                    }

                    $driver = new \TbDriver();
                    $driver->userid = $usr->id;
                    $driver->team_id = $team_id;
                    $driver->car_number = $v['car_number'];
                    $driver->id_number = $v['id_number'];
                    $driver->driver_name = $v['driver_name'];
                    $driver->drive_number = $v['drive_number'];
                    $driver->car_trans_auth = $v['car_trans_auth'];
                    $isOk = $driver->save();
                    $isOk && $sum_new++;
                }
                if( !$isOk) {
                    Logger::warn("create driver :",var_export($driver->getMessages(),true));
                    $ret['error_code'] = 102;
                    $ret['error_msg'] = '创建更新司机失败，请稍后再试';
                    return $ret;
                }
            }
            $this->db->commit();
        }
        catch (\Exception $e) {
            $this->db->rollback();
            Logger::error('driverService-create rollbac: '.$e->getMessage());
            $ret['error_code'] = 103;
            $ret['error_msg'] = '导入失败,请修改后再试. 车牌号:'.$v['car_number'].' 手机号:'.$v['mobile'];
            return  $ret;
        }

        $ret['error_msg'] = '更新了'.$sum_update.'条记录,'.'创建了'.$sum_new.'条记录。';
        return  $ret;
    }


    public function drivers($id, $status, $begin_time, $end_time, $platform, $version, $page=1, $page_size=10 )
    {
        empty($page) && $page=1 ;
        $page_size = 10;
        $page_start = ($page-1)*$page_size;

        $id = intval($id);
        $sql = "SELECT users.id, users.created_at as regist_time, users.mobile, users.regist_platform, users.regist_version, "
            ." tb_driver.driver_name, tb_driver.car_number, tb_driver.drive_number, tb_driver.status"
            ." FROM users INNER JOIN tb_driver ON users.id = tb_driver.userid WHERE tb_driver.team_id = $id ";
        $bind = array();
        $count = $this->cond($sql, $bind, $begin_time, $end_time, $platform, $version, $status , $id);

        $sql .= " ORDER BY tb_driver.created_at DESC LIMIT $page_start,$page_size ";
        $data = $this->db->fetchAll($sql,2,$bind);

        //把code转化成中文 todo
        $hashPlatForm = $this->constant['platform'];
        foreach($data as $k=>&$v) {
            if( isset($v['regist_platform']))
                $v['regist_platform'] = $hashPlatForm[$v['regist_platform']];
            //StatusHelper::numToWord('CarTeamUser',$v['status']);
        }

        $car_team = \CarTeamUser::findFirst($id);
        $car_team = $car_team? $car_team->toArray():false;
        $page_sum = intval($count/$page_size) + 1;
        $result = array(
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'page_no' => $page,
            'page_sum' => $page_sum,
            'data_sum' => $count,
            'data_head' => $car_team,
            'data' => $data,
        );

        return $result;
    }

    private  function cond(&$sql, &$bind, $begin_time, $end_time, $platform, $version, $status , $id)
    {

        $where = '';
        if( !empty($begin_time) )
        {
            $where .= " AND users.created_at > ? ";
            $bind[] = $begin_time;
        }

        if( !empty($end_time) )
        {
            $where .= " AND users.created_at < ? ";
            $bind[] = $end_time;
        }

        if( !empty($platform) )
        {
            $where .= " AND users.regist_platform = ? ";
            $bind[] = $platform;
        }

        if( !empty($version) )
        {
            $where .= " AND users.regist_version = ? ";
            $bind[] = $version;
        }

        if( isset($status) && $status!='' )
        {
            $status = intval($status);
            $where .= " AND tb_driver.status = $status ";
        }

        if( true) {
            //$sql .= " AND users.status ^ $this->admin_cfg['usr_deleted'] ";
        }

        $sql .= $where;

        $sql_count = "SELECT COUNT(tb_driver.id)as sum FROM users INNER JOIN tb_driver ON users.id = tb_driver.userid WHERE tb_driver.team_id = $id ";
        $count = $this->db->fetchAll($sql_count.$where, 2, $bind);

        return  isset($count[0]['sum']) ? intval($count[0]['sum']):0;
    }

    //修改司机的状态
    public function setStatus($id, $new_status)
    {
        $driver = \TbDriver::findFirst( ["userid = ?1",'bind'=>[1=>$id]]);
        if( !$driver)
            return false;

        $isOk1 = false; //更新状态是否成功

        $status = new StatusHelper($driver);

        //修改司机状态
        switch($new_status)
        {
            case 'free':
                if($status->free)
                    break;

                $isOk1 = $status->add('free')->saveModel();
            break;

            case 'work':
                if( $status->work)
                    break;

                $isOk1 = $status->add('work')->saveModel();
            break;

            case 'unknow':
                if($status->unknow)
                    break;

                $isOk1 = $status->add('unknow')->saveModel();
            break;

            case 'locked':
                if( $status->locked)
                    break;

                $isOk1 = $status->add('locked')->saveModel();
            break;

            case 'unlocked':
                if( !$status->locked)
                    break;

                $isOk1 = $status->add('guest')->saveModel();
            break;
        }

        $log_db = var_export($driver->getMessages(),true);

        Logger::info('carteamService-setStatus:'.$log_db);
        return $isOk1;
    }

//    public function pwdToDriver($driver_id, $pwd)
//    {
//        //获取电话 pwd
//        $driver = \TbDriver::findFirst( intval($driver_id));
//        if( !$driver)
//            return false;
//
//        // sms.text init
//        $contact = \Users::findFirst( ["id = ?1",'bind'=>[1=>$driver->userid],'column'=>'mobile,contactName']);//某些字段
//        if( !$contact)
//            return false;
//
//        $mobile = $contact->mobile;
//        $msg = '密码:'.$pwd;
//        $content = $contact->contactName.$msg;
//        $content = iconv('UTF-8', 'GBK', $content);
//
//        // sms send
//        $ret_code = $this->SmsService->sendSMS(array($mobile), $content); //not
//        if ($ret_code == '0') {
//            Logger::info('send sms to ' . $mobile. ' success ');
//        } else {
//            Logger::error('send sms to ' . $mobile . ' fail ');
//        }
//
//        //sms-log
//        $this->ActivityLogService->insertActionLog($this->constant->ACTION_TYPE->SMS_SEND,
//            $this->request->getClientAddress(), 1, 1, "", 2, "PWD_TO_DRIVER");
//
//        return $ret_code=='0';
//    }

    /**
     * @param $userId
     */
    public function getByUserId( $userId ){
        $userId = intval($userId);
        $driver = \TbDriver::findFirst("userid='$userId'");

        return $driver;
    }
    //



}