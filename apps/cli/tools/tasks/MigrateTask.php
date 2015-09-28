<?php

use Library\Log\Logger;
use Library\Helper\ArrayHelper;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager as EventsManager;
use Library\Helper\StringHelper;

/***
 *
 * 一期账户导入到二期
 */
class MigrateTask extends \Phalcon\CLI\Task
{

/**
 * 功能: 一期数据库用户、审核信息迁移。
 * 备注: 需要加一个只读权限
 */
public function UserMoveAction()
{
    try{
        $db_old = new DbAdapter([
            'adapter'  => 'Mysql',
            'username' => 'readerxdd',
            'password' => 'Passw0rd',
            'host'     => '123.59.59.233',
            "dbname" => 'xddbiz',
            "port" => '4404',
        ]);

        $carteam_sum = 0;
        $agent_sum = 0;

        $this->di['db']->beging();
        //导入车队
        $sql_usr = "SELECT users.created_at,users.updated_at,usertype,mobile,contactName,contactNumber,pwd,salt,car_team.teamName,contactName FROM users";
        $sql_team = " INNER JOIN car_team ON users.id = car_team.userid WHERE users.usertype=1";

        $data = $db_old->fetchAll($sql_usr.$sql_team,2);
        foreach($data as $v) {
            $v['usertype'] = 2-$v['usertype'];  //用户类型(1期2期的映射关系y=-x+2)
            $v['username'] = $v['mobile'];
            $isOk = $this->di['UserService']->create($v['mobile'], $v['pwd'], $v['usertype'], $v['username'], $v['salt'], '', '', '');
            $isOk && $carteam_sum++;
        }

        //导入货代
        $sql_usr = "SELECT users.created_at,users.updated_at,usertype,mobile,contactName,contactNumber,pwd,salt,cargoer.enterpriseName,contactName FROM users";
        $sql_agent = " INNER JOIN cargoer ON users.id = cargoer.userid WHERE users.usertype=1";

        $data = $db_old->fetchAll($sql_usr.$sql_agent,2);
        foreach($data as $v) {
            $v['usertype'] = 2-$v['usertype'];  //用户类型(1期2期的映射关系y=-x+2)
            $v['username'] = $v['mobile'];
            $isOk = $this->di['UserService']->create($v['mobile'], $v['pwd'], $v['usertype'], $v['username'], $v['salt'], '', '', '');
            $isOk && $agent_sum++;
        }
        $this->di['db']->commit();

        Logger::info('导入车队%s条,货代%s条',$carteam_sum,$agent_sum);
    }catch (\Exception $e) {
        $this->di['db']->rollBack();
        Logger::error($e->getMessage());
        return false;
    }
    
}



private function UserMove2Action()
{


    //1 车队 0 货代 usertype

    //车队类型（公司0 个人1） teamType
    //读取状态为3(审核通过)的车队 car_team.status=3

    // ,car_team.teamName,car_team.teamPic,car_team.teamType,car_team.status

    $tmp_usr = time().'usr';
    $t_carteam = time().'usr';
    $t_agent = time().'usr';

    //迁移数据 usr carteam agent三张表到本数据库
    //........
    var_dump(\AdminUsers::find()->toArray());die;





    $db_tmp = new DbAdapter([
        'adapter'  => 'Mysql',
        'username' => 'root',
        'password' => 'Passw0rd',
        'host'     => '127.0.0.1',
        "dbname" => 'phalcon',
        "port" => '3306',
    ]);

//
//        INSERT INTO users(mobile,contactNumber,usertype,pwd,salt,regist_version)
//SELECT uc.mobile,uc.contactNumber,uc.usertype,uc.pwd,uc.salt,uc.regist_version
//FROM users_copy as uc
//WHERE NOT EXISTS (select users.id FROM users WHERE users.mobile = uc.mobile)

        $sql_write_team = "INSERT INTO car_team_user(created_at,userid,teamName,teamPic,ownerName,ownerNameIdentityCardId,idcard_pic)";

//        $sql_write = substr($sql_write,0,-1);


    //筛选
    $sql_insert_usr = "INSERT INTO users(created_at,updated_at,users.usertype,mobile,contactName,contactNumber,pwd,salt,username,unverify_enterprisename,real_name)";
    $sql_usr_team = " SELECT uc.created_at,uc.updated_at,uc.usertype,mobile,contactName,contactNumber,pwd,salt,mobile,car_team.teamName,contactName FROM users_copy AS uc";
    $sql_usr_agent = " SELECT uc.created_at,uc.updated_at,uc.usertype,mobile,contactName,contactNumber,pwd,salt,mobile,cargoer.enterpriseName,contactName FROM users_copy AS uc";
    $sql_join_agent = " INNER JOIN cargoer ON uc.id = cargoer.userid WHERE uc.usertype=0";
    $sql_join_team = " INNER JOIN car_team ON uc.id = car_team.userid WHERE uc.usertype=1";
    $sql_not = " AND NOT EXISTS(SELECT users.id FROM users WHERE users.mobile=uc.mobile)";

    //插入user表的车队用户
    $b1 = $db_tmp->execute($sql_insert_usr.$sql_usr_team.$sql_join_team.$sql_not);
    $row1 = $db_tmp->affectedRows();

    //插入user表的货代用户
    $b2 = $db_tmp->execute($sql_insert_usr.$sql_usr_agent.$sql_join_agent.$sql_not);
    $row2 = $db_tmp->affectedRows();


    //插入货代
    $sql_insert_agent = " INSERT INTO freightagent_user(created_at,userid) ";
    $sql_data = "SELECT cargoer.created_at,cargoer.userid FROM users_copy ";
    $sql_join_agent = $sql_join_agent;
    $b3 = $db_tmp->execute($sql_insert_agent.$sql_data.$sql_join_agent);
    $row3 = $db_tmp->affectedRows();

    //插入车队
    $sql_insert_team = " INSERT INTO freightagent_user(created_at,userid) ";
    $sql_data = "SELECT cargoer.created_at,cargoer.userid FROM users_copy ";
    $sql_join_agent = $sql_join_agent;
    $b4 = $db_tmp->execute($sql_insert_team.$sql_data.$sql_join_agent);
    $row4 = $db_tmp->affectedRows();

    //插入企业表


    var_dump($b1.' '.$row1);
//var_dump('    '.$b2.' '.$row2);die;


}


}