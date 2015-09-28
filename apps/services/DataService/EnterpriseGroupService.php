<?php

namespace Services\DataService;

use Library\Log\Logger;
use \Phalcon\DiInterface;
use EnterpriseGroup;
use GroupFunction;

use Phalcon\Mvc\User\Component;
/**
 *  企业建立群组
 *  给群组添加 权限
 */
class EnterpriseGroupService extends  Component
{

    /**
     *  创建 群组 (组名不能重复)
     */
    public function  createGroup($enterpriseId,$gName,$desc){
        $group = $this->getByName( $enterpriseId,$gName );
        if( empty( $group) ){ // 创建
            $group = new EnterpriseGroup();
            $group->group_name    = $gName;
        }
        $group->enterprise_id = $enterpriseId;
        $group->description   = $desc;
        $group->updated_at    = date('Y-m-d h:i:s',time());
        $group->created_at    = date('Y-m-d h:i:s',time());
        return $group->save() ? $group:false ;
    }

    /**
     *  给群组 添加 权限
     */
    public function addFunctionsToGroup( $groupId,$functions = array()){
        $sql = " SELECT functionid FROM group_function WHERE groupid ='$groupId' ";
        $result = $this->db->query($sql)->fetchAll();
        foreach( $functions as $functionId ){
            if( empty($result) || !in_array($functionId,$result) ){
                $gfs                = new GroupFunction();
                $gfs->groupid       = $groupId;
                $gfs->functionid    = $functionId;
                $gfs->save();
            }
        }
        return true;
    }

    /**
     *  获取企业 所有的群组（对象）
     */
    public  function getAllGroups( $enterpriseId ){
        $groups = EnterpriseGroup::find( "enterprise_id ='$enterpriseId' ");
        return $groups;
    }

    /**
     *  检查 公司是否存在 这个群组
     */
    public function  isEnterpriseHasGroup( $enterpriseId, $groupId ){
        return EnterpriseGroup:: count(" enterprise_id ='$enterpriseId'  and id='$groupId' ") >0 ;
    }


    public function getById( $groupId,$enterpriseId ){
        return EnterpriseGroup::findFirst("id ='$groupId' and enterprise_id ='$enterpriseId' ");
    }


    public function getByName( $enterpriseId ,$name ){
       return EnterpriseGroup::findFirst(array(
            " enterprise_id =?1 and group_name = ?2 ",
            "bind" => [ 1=> $enterpriseId, 2 => $name ]
        ));
    }









}