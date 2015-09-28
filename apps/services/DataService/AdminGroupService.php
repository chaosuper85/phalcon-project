<?php  namespace Services\DataService;


use Library\Log\Logger;

use Phalcon\Mvc\User\Component;

use Library\Helper\QueryHelper;

/**
 * haibo
 * last 15-8-31
 * Class AdminGroupService
 * @package Services\DataService
 * 用户-组
 */
class AdminGroupService extends Component
{
    const ERR_GROUP_NULL = '无此组信息，请联系管理员';
    const ERR_GROUP_DEL = '请先删除分组内的所有人员';
    const ERR_GROUP_REPET = '已经存在名称相同的组了';
    const ERR_GROUP_USR_REPET = '分组已有此用户';
    const ERR_USER_NULL = '无此用户信息';
    const ERR_FUN_NULL = '没有权限信息';


    /**
     * 功能: 增加分组里一个用户
     * @param $uid
     * @param $group name/id
     * @return bool
     */
    public function addGroupUser($uid, $group=false)
    {
        $ret = '';

        $usr = \AdminUsers::findFirst($uid);
        if($usr && $group)
        {
            $gid = false;
            if( is_numeric($group)) {
                if( \AdminGroup::findFirst( intval($group))) {
                    $gid = $group;
                }
            }else {
                $group = \AdminGroup::findFirst( array("group_name = ?1",'bind'=>[1=>$group],'columns'=>'id'));
                $group && $gid = $group->id;
            }

            //增加一条
            if( $gid) {
                $obj = \AdminUserGroup::findFirst("group_id = $gid AND user_id = $uid");
                if( $obj && $obj->enable==1) {
                    $ret = self::ERR_GROUP_USR_REPET;
                }else{
                    $obj || $obj = new \AdminUserGroup();
                    $obj->user_id = $uid;
                    $obj->group_id = $gid;
                    $obj->enable = 1;
                    $obj->save();
                    $ret = isset($obj->getMessages()[0]) ? $obj->getMessages():'';
                }
            }
        }else{
            $ret = self::ERR_USER_NULL;
        }

        Logger::info('addUserGroup'.$ret);
        return $ret;
    }

    /**
     * 功能: 删除分组里的一个用户
     * @param $usr_group_id
     * @return bool
     */
    public function delGroupUser($uid, $group_id)
    {
        $ret = false;
        $obj = \AdminUserGroup::findFirst("user_id = $uid AND group_id = $group_id");
        if( $obj) {
            $obj->enable = $this->admin_cfg->GROUP->ASSIGN_FALSE;
            $ret = $obj->update();
        }

        Logger::info('delUserGroup'.($ret?'成功':'失败'));
        return $ret? '':'失败';
    }

    /**
     * 功能:返回所有用户组
     * 备注:
     * @param $ret
     * @return mixed
     */
    public function groups(&$ret)
    {
        $options = array('columns'=>'id,group_name,mark');
        $ret = QueryHelper::cond('\AdminGroup',$this->request,$options);

        Logger::info('admin_group sum:'.$ret['data_sum']);
        return $ret['data_sum'];
    }

    public function addGroup($name, $level=0)
    {
        if ( \AdminGroup::findFirst(array("group_name = ?1",'bind'=>[1=>$name])))
            return self::ERR_GROUP_REPET;

        $group = new \AdminGroup();
        $ret = $group->save(['group_name'=>$name,'level'=>$level,'mark'=>'级别：A']);
        if( $ret)
            Logger::info('addGroup : 成功');
        else
            Logger::error('addGroup :'.var_export($group->getMessages(),true));

        return isset($group->getMessages()[0]) ? $group->getMessages()[0]:'';
    }

    public function delGroup( $id)
    {
        $id = intval($id);
        $group = \AdminGroup::findFirst($id);
        if( !$group) {
            Logger::info('delgroup info:无id为'.$id.'的用户组');
            return self::ERR_GROUP_NULL;
        }

        $obj = \AdminUserGroup::findFirst("group_id = $id");
        if( $obj) {
            Logger::info('delGroup info:请先删除组内的人');
            return self::ERR_GROUP_DEL;
        }

        $ret = $group->delete();
        if( $ret)
            Logger::info('delGroup : 成功');
        else
            Logger::error('delGroup :'.var_export($group->getMessages(),true));

        return isset($group->getMessages()[0]) ? $group->getMessages()[0]:'';
    }

    /**
     * 功能: 给用户分组重新分配权限、名字
     * @param $id 组id
     * @param $fun_ids array 权限
     * @param bool $name
     * @return bool
     */
    public function setGroup($id, $fun_ids, $name=false)
    {
        $ret = false;

        if( count($fun_ids) < 1)
            return self::ERR_FUN_NULL;

        //修改组名
        $group = false;
        $name && $group = \AdminGroup::findFirst( intval($id));
        if( $group) {
            $group->group_name = $name;
            $ret = $group->update();
            if( !$ret) {
                Logger::warn('setGroup set-group_name fail:'.var_export($group->getMessages(),true));
                return isset($group->getMessages()[0]) ? $group->getMessages()[0]:'';
            }
        }else {
            Logger::warn('setGroup group is null');
            return self::ERR_GROUP_NULL;
        }

        //插入权限
        $this->db->begin();
        try{
            //删除权限
            $delSql = "DELETE FROM admin_access WHERE group_id=$id";
            $ret = $this->db->execute($delSql);

            //注入权限
            $addSql = "INSERT INTO admin_access(group_id,fid) VALUES";
            foreach($fun_ids as $v) {
                $addSql .= '('.$id.',';
                $addSql .= $v."),";
            }
            $addSql = substr($addSql,0,-1);
            $ret = $ret && $this->db->execute($addSql);
        }catch (\Exception $e) {
            $this->db->rollback();
            Logger::warn('setGrooup err:'.var_export($e->getMessage(),true));
            return '系统错误，请查看日志';
        }
        $this->db->commit();

        Logger::info('setGroup'.($ret?'成功':'失败'));
        return $ret ? '':'数据插入失败.请查看日志';
    }


}