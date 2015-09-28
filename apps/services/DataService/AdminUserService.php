<?php namespace Services\DataService;


use Library\Helper\PageHelper;
use Library\Log\Logger;

use Phalcon\Mvc\User\Component;

use Library\Helper\QueryHelper;

/**
 * Class AdminUserService
 * @package Services\DataService
 * 后台用户相关
 * haibo
 * time 15-8-23
 */
class AdminUserService extends Component
{
    const ERR_SYS = '网络异常';
    const ERR_USR_REPET = '已有此用户，请修改用户名或真实姓名';
    const ERR_USR_FINDNO = '此用户信息丢失，详情请查看日志';
    const ERR_USR_NAME = '词汇不被允许';


    /**
     * 功能:
     * 备注:
     * @param $username
     * @param $password
     * @param string $verify
     * @return bool
     */
    public function login($username, $password, $verify_code = 0)
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        //验证码核对
        $captchaService = $this->CaptchaService;
        if( !$captchaService->validateCapcha($verify_code)) {
            //return  '验证码输入错误';
        }

        //登录是否成功的判定
        $cryptPassword = strtoupper(md5(strtoupper(md5($password))));

        $status = $this->admin_cfg->USER_STATUS->ENABLE;
        $cond = array(
            'conditions' => "username = ?1 AND password = ?2 AND user_status=$status",
            'bind' => [1 => $username, 2 => $cryptPassword],
        );
        $usr = \AdminUsers::findFirst($cond);

        //登录成功则加session
        $isOk = $usr && $this->saveLogin($username);

        if( !$isOk) {
            return  '用户名或密码错误';
        }

        return '';
    }


    /**
     * 查询adminuser的操作历史    ref: admin_cfg   ADMIN_ACTION_TYPE
     * @param $admin_userid
     * @param $mobile
     * @param $start_time
     * @param $end_time
     * @param $admin_action_type
     * @param PageHelper $pageHelper
     */
    public function getAdminUserLogList($user_id, $mobile, $begin_time=false, $end_time=false, $type=false)
    {
        if( empty($user_id))
        {
            if( !empty($mobile)) {
                $usr = \AdminUsers::findFirst(["phone_number=?1",'bind'=>[1=>$mobile],'columns'=>'id']);
                if( $usr)
                    $user_id = $usr->id;
            }
            if( $user_id < 1) {
                Logger::info('getAdminUserLogList 没有或缺少uid/mobile');
                return false;
            }
        }

        $ret = array('active'=>array(),'aim'=>array());
        $param = array();
        $param['conditions'][] = "admin_user_id=$user_id";
        $param['order'][] = 'created_at asc';
        $param['columns'] = 'id,created_at,ip,targetReamId,targetReamType,json_content';
        $param['model'] = '\AdminLog';

        $type = intval($type);
        $type && $param['conditions'][] = "action_type=$type";
        $begin_time && $param['begin_time'] = $begin_time;
        $end_time && $param['end_time'] = $begin_time;

        //查询用户发起的log
        $sum = QueryHelper::query($param, $ret['active']);
        Logger::info('getAdminUserLogList sum:'.$sum);

        //查询用户是操作目标的log
        $param['conditions'][0] = "targetReamId=$user_id";
        $sum = QueryHelper::query($param, $ret['aim']);
        Logger::info('getAdminUserLogList sum:'.$sum);

        return $ret;
    }



    //缓存登录成功的用户 todo more test
    public function saveLogin($username)
    {
        $usr = \AdminUsers::findFirst(array("username = ?1", 'bind' => [1 => $username]));

        $usr = $usr->toArray();
        if (!$usr) {
            Logger::warn('saveLogin: 缓存失败，无此用户');
            return false;
        }

        $this->session->set('login_user', $usr);

        Logger::info('saveLogin info:' . '成功');
        return true;
    }


    public function checkLogin()
    {
        return $this->session->get('login_user', false);
    }

    public function logOut()
    {
        $this->session->remove('login_acl');
        return $this->session->get('login_user', false, true);
    }

    //获取缓存的后台用户信息   return: userObj
    public function getSessionUser($isDelete = false)
    {
        $ret = $this->session->get('login_user', false, $isDelete);
        return $ret;
    }

    //获取缓存的权限列表
    public function getSessionAcl($isDelete = false)
    {
        return $this->session->get('login_acl', array(), $isDelete);
    }

    //增加后台用户
    public function addUser($name, $real_name, $pwd, $email, $mobile, $avatar)
    {
        if( \AdminUsers::findFirst( ["username = ?1 OR real_name = ?2 ",'bind'=>[1=>$name,2=>$real_name]])) {
            return self::ERR_USR_REPET;
        }

        //敏感用户名过滤
        if( $this->SpamUserNameService->filterName( $name) || $this->SpamUserNameService->filterName( $real_name) ) {
            return  self::ERR_USR_NAME;
        }

        $pwd = strtoupper(md5(strtoupper(md5($pwd))));
        $usr = new \AdminUsers();
        $isOk = $usr->save([
            'username' => $name,
            'password' => $pwd,
            'real_name' => $real_name,
            'mobile' => $mobile,
            'email' => $email
        ]);

        Logger::info('addUser: ' . var_export($usr->getMessages(), true));

        return isset($usr->getMessages()[0]) ? $usr->getMessages()[0]:'';
    }

    public function alterUser($id, $real_name, $pwd, $email, $mobile, $avatar)
    {
        $usr = \AdminUsers::findFirst( intval($id));
        if( !$usr) {
            Logger::info('alterUser :用户不存在');
            return self::ERR_USR_FINDNO;
        }

        //敏感用户名过滤
        if( $this->SpamUserNameService->filterName( $real_name)) {
            return  self::ERR_USR_NAME;
        }

        $real_name && $usr->real_name=$real_name;
        $pwd && $usr->password=strtoupper(md5(strtoupper(md5($pwd))));
        $email && $usr->email=$email;
        $mobile && $usr->phone_number=$mobile;
        $usr->update();
        Logger::info('alterAdminUser : '.var_export($usr->getMessages(),true));
        $loginusr = $this->AdminUserService->getSessionUser();

        $this->ActivityLogService->addAdminLog(
            $this->admin_cfg->ADMIN_ACTION_TYPE->ALTER_USER,
            json_encode(['admin_user_id'=>$id]),
            $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->USER,
            $id,
            $loginusr['username'].'修改了用户:'.$usr->username.'的信息'
        );
        return isset($usr->getMessages()[0]) ? $usr->getMessages()[0]:'';
    }

    public function users()
    {
        $cond['column'] = 'id,username,phone_number,email,pic,real_name';
        $cond['order'] = 'created_at DESC';
        $data = QueryHelper::cond('\AdminUsers', $this->request, $cond);

        Logger::info('admin_user sum:' . $data['data_sum']);

        return $data;
    }

    public function userList(&$data)
    {
        $_REQUEST['page_no'] = -1;
        $data = QueryHelper::cond('\AdminUsers', $this->request,['columns'=>'id,username,real_name']);

        Logger::info('admin_user sum:' . $data['data_sum']);
        return '';
    }

    //todo test by haibo
    public function details($id)
    {
        $data = array();
        //用户信息
        $usr = \AdminUsers::findFirst($id);
        if( !$usr) {
            Logger::warn('user-details :无此id用户');
            return self::ERR_USR_FINDNO;
        }
        //用户权限组信息
        $sql = "SELECT admin_group.name FROM admin_user_group INNER JOIN admin_group ON admin_group.id=admin_user_group.group_id WHERE admin_user_group.user_id = $id";

        //登录信息
        $type = $this->admin_cfg->ADMIN_ACTION_TYPE->LOGIN;
        $logs = \AdminLog::find("action_type= $type AND admin_user_id=");

        $data['user'] = $usr->toArray();
        $data['group'] = $this->db->fetchAll($sql,2);
        return $data;
    }


    public function setStatus($id, $status='')
    {
        $usr = \AdminUsers::findFirst( intval($id));
        if( !$usr) {
            Logger::info('admin_user-setStatus :无此用户');
            return self::ERR_USR_FINDNO;
        }

        $cfg = $this->admin_cfg->USER_STATUS;
        switch($status)
        {
            case 'delete':
                if( $usr->user_status == $cfg->DELETE)
                    break;
                $usr->user_status = $cfg->DELETE;

            break;

            case 'disable':
                if( $usr->user_status != $cfg->ENABLE)
                    break;
                $usr->user_status = $cfg->DISABLE;
            break;

            case 'enable':
                if( $usr->user_status == $cfg->ENABLE)
                    break;
                $usr->user_status = $cfg->ENABLE;
            break;
        }

        $isOk = $usr->update();
        if( !$isOk)
            Logger::error('admin_user-setStatus :'.var_export($usr->getMessages(),true));
        else
            Logger::info('admin_user-setStatus :成功');
        $loginusr= $this->AdminUserService->getSessionUser();

        return isset($usr->getMessages()[0]) ? $usr->getMessages()[0]:'';
    }


}