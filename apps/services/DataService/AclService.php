<?php namespace Services\DataService;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use  Library\Helper\PageHelper;
/**
 * Class AclService
 * @package Services\DataService
 * auth :haibo
 * 15-7-25
 */

class AclService extends Component
{

    /**
     * 功能: 获取某人或某组的所有权限列表
     * @param bool $name
     * @param bool $isGroup
     * @return array
     */
    public function getAccFun($name = false, $isGroup = false)
    {
        $acl = array();

        if( !$name) {
            $usr = $this->AdminUserService->getSessionUser();
            if( !$usr) {
                Logger::warn('getAccFun :getSessionUser null');
                return $acl;
            }
            $name = $usr['username'];
        }

        //从数据库获取url权限
        if( $this->isRoot($name)) {
            $acl = $this->funList(); //超级管理员
        }else {
            if( $isGroup) //获取用户组的权限
            {
                $group = \AdminGroup::findFirst( array("group_name = ?1",'bind'=>[1=>$name],'columns'=>'id'));
                $acl = $this->funList($group->id);
            }
            else    //获取用户的权限
            {
                $uid = $usr['id'];
                $group_ids = \AdminUserGroup::find( ["user_id = $uid",'columns'=>'group_id'])->toArray();
                if( !empty($group_ids)) {
                    foreach($group_ids as $k=>$v) {
                        $group_ids[$k] = $v['group_id'];
                    }
                }
                $acl = $this->funList($group_ids);
            }
        }

        Logger::info('getAccFun sum:'.count($acl));
        return $acl;
    }

    //接口2
    //判断当前用户是否有权限执行某route
    public function hasAccFun($route = '')
    {
        $isOk = false;

        if( empty($route))
            return $isOk;

        $list = $this->getAccFun();

        if( !empty($list))
        foreach($list as $k=>$v) {
            if($v['url'] == $route) {
                $isOk = true;
                break;
            }
        }

        Logger::info('hasFunAcc  :'.($isOk?'有此url权限':'无此url权限'));
        return $isOk;
    }

    //增加权限
    public function addPower($group_id, $fun_ids)
    {
        $ret = 0;
        if( !\AdminGroup::findFirst($group_id)) {
            return $ret;
        }

        if( !is_array($group_id))
            $group_id = array($group_id);

        //循环插入
        if( !empty($fun_ids) ) {
            foreach($fun_ids as $v)
            {
                if( !is_numeric($v)) {  //权限名称 to 权限ID
                    $obj = \AdminFunction::findFirst( ["name = ?1",'bind'=>[1=>$v]]);
                    if( !$obj)
                        continue;
                    $v = $obj->id;
                }

                if( !\AdminFunction::findFirst($v))
                    continue;
                if ( \AdminAccess::findFirst( ["fid= ?1 AND group_id=?2 ",'bind'=>[1=>$v,2=>$group_id]]))
                    continue;

                $acl = new \AdminAccess();
                $ret += $acl->save(['group_id'=>$group_id,'fid'=>$v,'type'=>2]);
            }
        }

        Logger::info('addPower. 插入条数:'.$ret);
        return $ret;
    }

    //删除权限
    public function delPower($group_id, $fun_ids)
    {
        $ret = 0;

        if( !\AdminGroup::findFirst($group_id)) {
            return $ret;
        }

        if( !is_array($group_id))
            $group_id = array($group_id);

        if( !empty($fun_ids) )
        {
            foreach($fun_ids as $v)
            {
                if( !is_numeric($v)) {  //权限名称 to 权限ID
                    $function = \AdminFunction::findFirst("name = '$v'");
                    if( !$function)
                        continue;

                    $v = $function->id;
                }

                if( !\AdminFunction::findFirst($v))
                    continue;
                $acl = \AdminAccess::findFirst("fid=$v AND group_id=$group_id");
                if($acl)
                    $ret += $acl->delete();
            }
        }

        Logger::info('delPower. 删除条数:'.$ret);
        return $ret;
    }

    //返回登录用户或某组的结构化权限
    //todo update
    public function listMenu($group_id =false)
    {
        //拥有权限
        if( $group_id)
            $list = $this->getAccFun($group_id,true);
        else
            $list = $this->getAccFun();

        return $this->treeFunction($list);
    }

    /**
     * 功能:  获取预置的用户组模板
     * @param $temlate  'ROOT'
     * @return array
     */
    public function accFunTpl( $temlate=false)
    {
        if( !$temlate)
            $temlate = $this->admin_cfg->POWER_TPL->ORDER_SUPER;   //默认跟单员权限

        if( !is_numeric($temlate))
            $temlate = $this->admin_cfg->POWER_TPL[$temlate];

        //模板权限
        $list = $this->funList($temlate);
        $list = $this->treeFunction($list);

        //所有权限
        $listAll = $this->funList();
        $listAll = $this->treeFunction($listAll);


        //在所有权限中标记拥有的权限
        $this->check($listAll,$list);

        return $listAll;
    }

    //判断某route是否无权限约束
    private function isPublic($route = '')
    {
        $isOk = false;
        $acl = \AdminFunction::findFirst("url='$route'");

        if( $acl)
            $isOk = $acl->public;

        Logger::trace('isPublic :'.($isOk ? '是公有url':'是私有url'));
        return  $isOk;
    }

    //是否是超级管理员
    private function isRoot($name = false)
    {
        $isOk = false;

        if( $name == $this->admin_cfg->ACL_ROLE_TYPE->ROOT_NAME )
            $isOk = true;

        Logger::trace('isRoot: '.$name.($isOk?' 是超级管理员':' 不是超级管理员'));
        return  $isOk;
    }

    public function funList($group_ids = false)
    {
        $db = $this->db;

        if( !$group_ids) {
            $res = $db->fetchAll('select id,url,name,pid,level FROM admin_function ORDER BY id ASC',2);
            return $res;
        }

        if( !is_array($group_ids)) {
            $group_ids = array($group_ids);
        }

        $group_ids_str = '(';
        if( !empty($group_ids) ){
            foreach($group_ids as $k=>$v) {
                $group_ids_str .= "'".$v."',";
            }
        }

        $group_ids_str = substr($group_ids_str,0,-2);
        $group_ids_str .= "')";

        $sql  = "select admin_function.id,admin_function.url,admin_function.name,admin_function.pid,admin_function.level
                ,admin_access.id as power_id FROM admin_access";
        $sql .= " left join admin_function on (admin_access.fid = admin_function.id or admin_function.public = 1)";
        $sql .= " WHERE admin_access.group_id IN ".$group_ids_str;

        $sql .= " group by admin_function.id order by admin_function.id DESC";

        return  $db->fetchAll($sql,2);
    }


    /**
     * 根据groupname查询admin user
     *
     * @param $groupName
     * @param $username
     * @param $mobile
     * @param PageHelper $pageHelper
     * @param null $status
     * @return PageHelper
     */
    public function getAdminUserByGroupName($groupName, $username=null, $mobile=null, PageHelper $pageHelper=null, $status = null)
    {
        $params = array();

        $sql = "select u.id,u.username, u.phone_number, u.created_at , u.updated_at
                 from admin_users u where  u.user_status =1  and  u.id in
                 (select aup.user_id  from  admin_user_group aup ,  admin_group ag  where  aup.group_id = ag.id and aup.enable = 1 and ag.group_name =? ) ";

        $sqlCount = "select count(u.id)
                 from admin_users u where  u.user_status =1  and  u.id in
                 (select aup.user_id  from  admin_user_group aup ,  admin_group ag  where  aup.group_id = ag.id  and aup.enable = 1 and ag.group_name =? ) ";

        $params[] = $groupName;
        if (isset($username)) {
            $sql .= " and u.username =? ";
            $sqlCount .= " and u.username =? ";
            $params[] = $username;

        }
        if (isset($mobile)) {
            $sql .= " and u.phone_number =? ";
            $sqlCount .= " and u.phone_number =? ";
            $params[] = $mobile;
        }

        if ( $pageHelper == null) {
            $pageHelper = new PageHelper(1, 10);
        }

        if(isset($status)){
            $sql .= " and u.user_status =? ";
            $sqlCount .= " and u.user_status =? ";
            $params[] = $status;
        }

        $startRow = $pageHelper->getStartRow();
        $pageSize = $pageHelper->getPageSize();

        //$sql .= "  limit $startRow ,$pageSize";
        $users = $this->db->fetchAll($sql, 2 , $params);
        $countRet = $this->db->query($sqlCount, $params)->fetch();
        $counts = $countRet[0];
        $pageHelper->setData($users)->setTotalRows($counts);
        return $pageHelper;
    }

    //返回按权限定制的用户菜单
    public function accFunMenu()
    {
        //拥有权限
        $list = $this->getAccFun();

        return $this->treeFunction($list);
    }

    //树形化function
    private function  treeFunction(&$list)
    {
        $ret = array();
        if( empty($list))
            return $ret;

        foreach($list as $k=>$v)        //根据第三级权限构建菜单
        {
            if($v['level'] == 3) {
                $ret[$v['pid']]['child'][$v['id']]['name'] = $v['name'];
                $ret[$v['pid']]['child'][$v['id']]['check'] = false;
                unset($list[$k]);
            }
        }

        foreach($list as $k=>$v)        //加入第二级菜单
        {
            if($v['level'] == 2) {
                $id = $v['id'];
                $ret[$v['id']]['name'] = $v['name'];
                $ret[$v['id']]['check'] = false;
                $ret[$v['pid']]['name'] =  $list[$v['pid']]['name'];
                $ret[$v['pid']]['check'] =  false;
                $ret[$v['pid']]['child'][$id] = $ret[$id];

                unset($ret[$id]);
            }
        }

        return $ret;
    }

    /**
     * 功能: 根据权限模板标记树形图菜单
     * 备注:
     * @param $list 全部权限
     * @param $checkList 拥有的权限
     */
    private function check(&$list, &$checkList)
    {
        foreach($checkList as $k1=>$v1)
        {
            $list[$k1]['check'] = true;
            if( isset($v1['child']))
            foreach($v1['child'] as $k2=>$v2)
            {
                $list[$k1]['child'][$k2]['check'] = true;
                if( isset($v2['child']))
                foreach($v2['child'] as $k3=>$v3)
                {
                    $list[$k1]['child'][$k2]['child'][$k3]['check'] = true;
                }
            }
        }

    }


}