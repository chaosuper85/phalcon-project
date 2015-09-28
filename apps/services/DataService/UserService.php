<?php


namespace Services\DataService;

use Library\Helper\StringHelper;
use Library\Log\Logger;
use Library\Helper\IdGenerator;
use Users;

use Phalcon\Mvc\User\Component;


class UserService extends Component
{


    public function getUserByMobile($mobile, $enable = true)
    {
        $conditions = " mobile = ?1  and enable=?2 ";
        $bind = array(1 => $mobile, 2 => $enable);
        $user = Users::findFirst(array(
            $conditions,
            "bind" => $bind
        ));
        return $user;
    }

    /**
     * @param $mobile
     * @param $pwd
     * @param $userType
     * @param $username
     * @param $salt
     * @param $invite_token
     */
    public function  create($mobile, $pwd, $userType, $username, $salt, $token, $ip, $real_name="")
    {
        $user = new Users();
        $config = $this->constant;
        $user->mobile = $mobile;
        $user->usertype = $this->constant->usertype[$userType];
        Logger::info("type:" . $this->constant->usertype->$userType . "   " . $userType);
        $user->pwd = $pwd;
        $user->username = $username;
        $user->enable = true;
        $user->telephone_number = '';
        $user->contactName = '';
        $user->contactNumber = '';
        $user->email = '';
        $user->invite_userid = 0;
        $user->avatarpicurl = '';
        $user->enterprise_licence = '';
        $user->enterprise_groupid = 0;
        $user->unverify_enterprisename = '';
        $user->real_name = $real_name;
        $user->salt = $salt;
        $user->enterpriseid = 0;
        $user->invite_token = IdGenerator::guid();// 自动生成
        $user->regist_platform = $config->PLATFORM_TYPE->PC;
        $user->regist_version = "1.0";
        $user->remember_token = $token;
        $this->db->begin();
        $ret = $user->save();
        $activityReamType = 0;
        if ($ret) {
            // 创建 车队 或者 货代 账户
            $user = $this->getUserByMobile($mobile);
            Logger::info("create User:", var_export($user->toArray(), true));
            try{
                switch ($user->usertype) {
                    case  3 :
                        $result = true;
                        break;
                    case  2 : //freight_agent 货代
                        $activityReamType = $this->constant->ACTION_REAM_TYPE->CARGOER;

                        $result = $this->AgentService->create($user->id);
                        break;
                    case  1:// carteam 车队
                        $result = $this->CarTeamService->create($user->id);
                        $activityReamType = $this->constant->ACTION_REAM_TYPE->CARTEAM;
                        break;
                    default:
                        break;
                }
                if (!$result) { // 保存 货代 或 车队 失败
                    //$this->db->rollback();
                    // 保存事件记录
                    $this->ActivityLogService->insertActionLog
                    (
                        $this->constant->ACTION_TYPE->REG,
                        $ip,
                        $user->id,     // reamid
                        $activityReamType,  //reamType
                        0, // target id
                        0, // target type
                        json_encode(array('success' => false, 'userid' => $user->id, 'mobile' => $mobile))  // json
                    );
                    return false;
                }

                $this->db->commit();

                $this->ActivityLogService->insertActionLog
                (
                    $this->constant->ACTION_TYPE->REG,
                    $ip,
                    $user->id,          // reamid
                    $activityReamType,  //reamType
                    0, // target id
                    0, // target type
                    json_encode(array('success' => true, 'userid' => $user->id, 'mobile' => $mobile))  // json
                );
            }catch (\Exception $e){
                Logger::warn(" create user error msg:".$e->getMessage());
                $this->db->rollback();
                return false;
            }
            return $user;
        } else {
            Logger::warn("create User error:" . var_export($user->getMessages(), true));
            return false;
        }
    }

    /**
     * @param $mobile
     * @return bool
     */
    public function checkMobileExist($mobile)
    {
        return Users::count(array("mobile = ?1", "bind" => [1 => $mobile])) > 0;
    }

    public function checkNameExist($userName)
    {
        return Users::count(array("username =?1", "bind" => [1 => $userName])) > 0;
    }

    /**
     *  用户 必须存在
     */
    public function getByNameOrMobile($nameOrMobile, $enable = true)
    {
        if (StringHelper::isMobileNumber($nameOrMobile)) {
            $conditions = " mobile = ?1 and enable = ?2"; // 手机号
        } else {
            $conditions = " username = ?1 and enable = ?2";
        }
        $bind = array(1 => $nameOrMobile, 2 => $enable);
        $user = Users::findFirst(array(
            $conditions,
            "bind" => $bind
        ));
        return $user;
    }
    //通过id 获取users表 对象
    public function getById($id){
        $conditions = 'id = ?1';
        $bind = array(1 => $id);
        $user = Users::findFirst(array(
            $conditions,
            'bind' => $bind,
        ));
        return $user;
    }

    /**
     * @param $mobileOrName
     * @param $pwd
     */
    public function validatePwd($mobileOrName, $rawPwd)
    {
        $user = $this->getByNameOrMobile($mobileOrName);
        if (empty($user)) {
            return false;
        }
        // 登录名 或 手机号输入正确
        if ($user->pwd != md5($rawPwd . $user->salt)) { // 密码错误
            return false;
        }
        return true;
    }

    /**
     * @param $mobile
     * @param $smsType 0/1
     * @param $clientIp
     * @param $result 返回的结果
     * @return bool 发送失败 false  发送成功 true
     */
    public function sendSmsWithCacheAndIpLimit($mobile, $smsType, $clientIp, &$result = array())
    {
        $exist = $this->checkMobileExist($mobile);
        switch ($smsType) {
            case 'REGISTER': // 注册
                if ( $exist ) { //
                    $result['error_code'] = '201';
                    $result['error_msg'] = " 手机号码已被注册.";
                    Logger::warn("来自iP" . $clientIp . " 注册 手机号" . $mobile . "手机号码已被注册");
                    return false;
                }
                break;
            case 'CHANGE_PWD': // 更换密码
                if (!$exist) { // 不存在
                    $result['error_code'] = '202';
                    $result['error_msg'] = " 手机号码未注册.";
                    Logger::warn("来自iP" . $clientIp . " 更换密码 手机号" . $mobile . "手机号码未注册");
                    return false;
                }
                // 存在用户 修改密码
                Logger::warn("来自iP" . $clientIp . " 更换密码.手机号" . $mobile);
                break;
            case 'APPLY_ADMIN': // 申请管理员
                if (!$exist) { //不存在 mobile
                    $result['error_code'] = '204';
                    $result['error_msg'] = " 手机号码未注册.";
                    Logger::warn("来自iP" . $clientIp . "  申请管理员 短信 ： " . $mobile . "手机号码未注册");
                    return false;
                }
                break;

            case 'CHANGE_MOBILE': //换绑手机号
                if ($exist) {
                    $result['error_code'] = '205';
                    $result['error_msg'] = " 手机号码已注册.";
                    Logger::warn('  手机号换绑：' . "来自iP" . $clientIp . "." . $mobile . "手机号码已注册");
                    return false;
                }
                $this->session->set('CHANGE_MOBILE', time());//验证码有效期
                break;

            case 'NORMAL_AUTH': //默认认证
                if (!$exist) {
                    $result['error_code'] = '206';
                    $result['error_msg'] = " 手机号码未注册.";
                    Logger::warn("来自iP" . $clientIp . " 短信 ： " . $mobile . "手机号码未注册");
                    return false;
                }
                break;

            default:
                $result['error_code'] = '203';
                $result['error_msg'] = " 参数格式不正确.";
                Logger::warn("来自iP" . $clientIp . " 手机号" . $mobile . "参数格式不正确");
                return false;
        }
        if ( $this->ComSmsService->sendSmsWithCode( $mobile , $smsType , $result  ) ) { // 保存 短信历史
            return true;
        } else {
            return false;
        }
    }


    /** 通用发送短信
     * @param $mobile
     * @param bool|true $isCache 是否缓存 code ，true 缓存code, 否则，不缓存
     * @param int $channel
     * @param  $content
     * @return bool
     */
    public function sendSms($mobile, &$content, $isCache = false, $code = null, &$channel = 1)
    {
        if($this->constant->application_mode != 'prod' ){
            $code = '1111';
        }

        if ($isCache && isset($code)) { // 缓存
            $this->cache->set('smscode_' . $mobile, $code, 600); // 缓存 code
        }
        $res = $this->SmsService->sendMs($mobile, $content);
        if (empty($res)) { // fail
            $res = $this->SmsBackupService->sendMessage($mobile, $content);
            $channel = 2;
            Logger::info("sendSms 使用通道2： result:%s",$res);
        }
        return $res;
    }

    /**
     * @param  array $params
     * @param        $userId
     * @return bool
     */
    public function updateById($params = array(), $userId)
    {
        $user = Users::findFirst(" id ='$userId' ");
        if (empty($params) || empty($user)) {
            return false;
        }
        $update = array();
        foreach ($params as $field => $value) {
            if (!empty($value)) {
                $update[$field] = $value;
            }
        }
        $res = $user->update($update);
        Logger::info('update user message:' . var_export($user->getMessages(), true));
        return $res;
    }

    //修改用户绑定的手机号
    public function changeMobile($uid, $mobile)
    {
        $usr = \Users::findFirst(array("mobile = ?1", 'bind' => [1 => $mobile]));
        if ($usr)
            return false;

        $usr = \Users::findFirst(array("id = ?1", 'bind' => [1 => $uid]));
        if (!$usr)
            return false;

        $usr->mobile = $mobile;
        $ret = $usr->update();

        Logger::info('change mobile. :' . var_export($usr->getMessages(), true));
        return $ret;
    }

    //企业管理员批准用户加入公司后，要修改用户的企业ID
    public function setEnterprise($usrid, $comId)
    {
        $ret = false;
        $usr = \Users::findFirst("id='$usrid'");
        $com = \TbEnterprise::count("enterprise_id='$comId'");
        if ($usr && $com) {
            $usr->enterpriseid = $comId;
            $ret = $usr->update();
            Logger::info(" add user:" . $usrid . " into Company:" . $comId);
        }
        Logger::info('create enterprise :未找到用户或企业');
        return $ret;
    }

    public function comId($id)
    {
        $usr = \Users::findFirst("id='$id'");

        if ($usr && !empty($usr->enterpriseid)) {

            return $usr->enterpriseid;
        }

        return false;
    }


    /** 要求 必须是企业的管理员; 用户必须已认证；
     *  给用户分配群组
     */
    public function assignUser($userId, $groupId)
    {
        Logger::info(" assign user:" . $userId . "  to group:" . $groupId);
        $user = Users::findFirst("id='$userId'");
        if (empty($user)) {
            return false;
        } else {
            $user->enterprise_groupid = $groupId;
            return $user->update();
        }
    }

    /**
     *  获取用户的详细信息：包括User 和 车队 或 货代
     */
    public function getDetails($userId)
    {
        $sql = " select u.id,u.usertype,u.mobile,u.contactName,u.contactNumber,u.enable, u.telephone_number,u.username,u.real_name,u.email,u.invite_userid,u.avatarpicurl,u.enterprise_licence,u.enterpriseid,u.unverify_enterprisename,u.enterprise_groupid,u.invite_token,eg.group_name,eg.description from users u  LEFT JOIN enterprise_group eg on u.enterprise_groupid = eg.id where u.id ='$userId' ";
        $userArray = $this->db->fetchAll($sql);
        if (empty($userArray)) {
            return array();
        } else {
            $user = $userArray[0];
        }
        // 基础信息
        $user['mobile'] = StringHelper::markStr($user["mobile"], 3, 4);
        // 'telephone_number'  =>  $phone // 座机
        if( !empty( $user['telephone_number'] ) ){
            $phone = explode("-", $user['telephone_number']);
            if( count( $phone ) == 3  ){
                $user['contactMobile_city'] = $phone[0];
                $user['contactMobile_number'] = $phone[1];
                $user['contactMobile_fenji'] = $phone[3];
            }elseif( count( $phone ) == 2 ){
                $user['contactMobile_city'] = $phone[0];
                $user['contactMobile_number'] = $phone[1];
            }
        }
        $result['user'] = $user;
        // 角色信息
        switch ($user["usertype"]) {
            case 2: //freight_agent 货代
                $agent = $this->AgentService->getByUserId($userId);
                $result['roleInfo'] = $agent;
                break;
            case 1:// carteam 车队
                $carTeam = $this->CarTeamService->getByUserId($userId);
                $result['roleInfo'] = $carTeam;
        }
        Logger::info('getDetails userId:' . $userId . " result:" . var_export(json_encode($result), true));
        return $result;
    }


    public function  getByUserId($userId)
    {
        $sql = 'select id,usertype,mobile,contactName,contactNumber,enable,remember_token,username,real_name,pwd,salt,
                email,invite_userid,avatarpicurl,enterprise_licence,enterpriseid,unverify_enterprisename,enterprise_groupid,invite_token
                from  Users WHERE id = ?1';
        $query = $this->modelsManager->createQuery($sql);
        $user = $query->execute(array('1' => $userId))->getFirst();
        return $user;
    }

    /**
     *  查询企业的 所有员工 或 查询 该企业 某个群组 所有员工
     * @param  $groupBy 是否按 group 分组
     */
    public function getUsersOfEnterprise($enterpriseId, $groupBy = false, $groupId = null)
    {
        $sql = 'select id,usertype,mobile,contactName,contactNumber,enable,remember_token,username,real_name,pwd,salt,
                email,invite_userid,avatarpicurl,enterprise_licence,enterpriseid,unverify_enterprisename,enterprise_groupid,invite_token
                from  users WHERE enterpriseid =? ';
        $params = array($enterpriseId);
        if (!empty($groupId)) {
            $sql .= "  and enterprise_groupid= ? ";
            $params[] = $groupId;
        }
        if ($groupBy) { // 分组
            $sql .= " group by enterprise_groupid ";
        }
        $users = $this->db->query($sql, $params)->fetchAll();
        Logger::info(" getUsersOfEnterprise" . var_export($users, true));
        return $users;
    }

    /**
     *  查询 用户的 权限列表
     */
    public function getUserACL($userId)
    {
        $sql = " SELECT  f.function_url FROM users u INNER JOIN group_function gf  ON u.enterprise_groupid = gf.groupid INNER JOIN tb_function f ON f.id = gf.functionid WHERE u.user_id = ? ";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    //通过 user_id 返回 id
    public function getIdByUserid($userid)
    {
        if (empty($userid)) {
            Logger::warn("userName 传递错误！代码居然有毒？");
            return false;
        }
        $sql = 'select id from users where id = ?';
        $user = $this->db->query($sql, [$userid])->fetchAll();
        if (count($user) > 1) {
            Logger::warn("此userName 对应多个 id，请问清楚逻辑！");
            return false;
        }
        return $user[0]['id'];
    }

    //通过id获取 some 信息
    public function getSomeInfoById($id)
    {
        $sql = 'select id, contactName, contactNumber from users where id = ?';
        return $this->db->query($sql, [$id])->fetchAll();
    }
    //校验密码是否正确
    public function checkPasswordWhenLogin($userid, $password){
        $user = $this->getById($userid);
        $result = false;
        if(!empty($user))
            if(strcmp($user->pwd, md5($password . $user->salt)) == 0)
                $result = true;
        return $result;
    }
    // 通过userid 获取联系人名字及号码
    public function getContectNumber($userid){
        $sql = 'SELECT `contactName`, `contactNumber` FROM `users` WHERE `id` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$userid]);
        $result = array(
            'contactName' => '',
            'contactNumber' => '',
        );
        if(!empty($arr)){
            $result['contactName'] = $arr[0]['contactName'];
            $result['contactNumber'] = $arr[0]['contactNumber'];
        }
        return $result;
    }

    /**
     *  查询 用户的状态
     */
    public function  getStatusByUserId($userId, $type)
    {
        try {
            switch ($type) {
                case 1:
                    $sql = " select audit_status as status from car_team_user WHERE userid=? ";
                    break;
                case 2:
                    $sql = " select audit_status as status from freightagent_user  WHERE userid=? ";
                    break;
                default:
                    $sql = false;
                    break;
            }
            if (!empty($sql)) {
                $res = $this->db->fetchOne($sql, 2, [$userId]);
                $status = empty($res) ? 0 : intval($res['status']);
            }
        } catch (\Exception $e) {
            Logger::warn(" get User status error msg:%s", $e->getMessage());
        }
        Logger::info("user:%s status:%s", $userId, $status);
        return $status;
    }
    //校验注册码是否正确
    public function checkRegisterCode($register_code){
        $registerCode  = $this->constant->Register_Code;
        $num = strcasecmp($register_code, $registerCode);
        return $num == 0 ? true : false;
    }

    /**
     *  清除session 和 cookie
     */
    public function  logout(){
        $this->session->remove('login_user');
        $this->session->remove("user_audit_status");
        $res = $this->session->destroy(); // 清除session;
        if( !$res ){
            Logger::warn("clear session failure:%s",$res);
        }
        if( !empty( $_COOKIE) ){
            foreach( $_COOKIE as $key => $value ){
                $cookie = $this->cookies->get( $key );
                if( !empty( $cookie ) ){
                    $cookie->delete();
                }
            }
        }
    }

    public function updateUserSession( $userId ){
        $user = Users::findFirst("id=$userId");
        $this->session->set('login_user', $user);
        return $this->session->get("login_user");
    }

    public function  getStatus( $userId, $type ){
        $status = $this->session->get('user_audit_status');
        if( $status == null || $status == false || $status <= 3 ){
            $status = $this->getStatusByUserId( $userId, $type );
            $this->session->set('user_audit_status', $status);
        }
        // 存在 且 > 4
        return $this->session->get('user_audit_status');
    }


    /**
     *
     */
    private function checkWhenSms(){

    }
}
