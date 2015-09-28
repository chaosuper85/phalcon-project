<?php

namespace Services\DataService;
use Library\Helper\PageHelper;
use Library\Log\Logger;
use InviteRecord;
use Phalcon\Mvc\User\Component;
use TbEnterprise;
use Users;

/**
 *  企业员工管理
 */

class EmployeeService extends Component
{

    const MAX_LIST = 10; // 最多显示 记录条数

    /**
     * 获取企业所有 员工 按部门  =》进行分组
     */
    public  function  listEmpsGroupBy( $enterpriseId ,&$result = array() ){
        $data = array();
        $noAssignEmps = array(); // 未分配 群组的员工
        $department   = array(); // 部门员工分组
        // 公司所有的员工 分组
        $employers = Users::find(array(
            "conditions" => " enterpriseid = ?1 ",
            "bind"       =>  array( 1 => $enterpriseId)
        ));
        if( !empty($employers) ){
            foreach( $employers as $employee){
                if( empty($employee->enterprise_groupid) && count($noAssignEmps) <= self::MAX_LIST ){ // 员工未分配群组
                    $noAssignEmps[] = $employee->toArray();
                }else{
                    $department[ $employee->enterprise_groupid ][] = $employee->toArray();
                }
            }
            //分组显示
            $groups = $this->EnterpriseGroupService->getAllGroups( $enterpriseId );
            if( !empty( $department ) ){
                foreach( $groups as $group ){
                    $groupUsers = array(
                        "depId"          => $group->id,
                        "depName"        => $group->group_name,
                        "description"    => $group->description,
                        "employees"      => array_key_exists( $group->id, $department) ? $department[$group->id] :"",
                    );
                    $data[] = $groupUsers;
                }
            }
        }
        $result ['depEmployers'] = $data; // 部门员工
        $result ['noAssignUsers']= $noAssignEmps; //没有分配的员工
        $result ['groups']       = $groups->toArray(); // 所有的部门
        return true;
    }



    /**
     *   查询被企业  邀请的员工信息 (带分页)
     */
    public  function  getInvitedEmps( $enterpriseId , $type = null ,$status = null, $startRow = 0, $pageSize = 10 ){
        $sql  = "select u.id,u.usertype,u.mobile,contactName,contactNumber,enable,u.username,u.real_name,u.email,invite_userid,avatarpicurl,enterprise_licence,u.enterpriseid,unverify_enterprisename,enterprise_groupid,invite_token,ir.id as inviteRecordId,ir.status as inviteStatus
                 from users u inner join invite_record ir on u.id = ir.invitee_userid  where ir.inviteer_enterpriseid = ?  ";
        $params = array( $enterpriseId );
        if( isset($type) ){
            $sql.=" and ir.invite_type = ? ";
            $params[] = $type;
        }
        if( isset($status) ){
            $sql.=" and ir.status = ? ";
            $params[] = $status;
        }

        $sql.= " order by ir.created_at desc limit $startRow,$pageSize ";
        $invitors = $this->db->query($sql,$params)->fetchAll();
        return $invitors;
    }

    public function  countInvitedEmps( $enterpriseId , $type = null ,$status = null){
        $sql  = " select COUNT(*) as times FROM users u INNER JOIN invite_record ir ON u.id = ir.invitee_userid  WHERE ir.inviteer_enterpriseid = ?  ";
        $params = array( $enterpriseId );
        if( isset($type) ){
            $sql.=" and ir.invite_type = ? ";
            $params[] = $type;
        }
        if( isset($status) ){
            $sql.=" and ir.status = ? ";
            $params[] = $status;
        }
        $invitors = $this->db->query($sql,$params)->fetch();
        return $invitors[0];
    }


    public function getInvitedEmpsWithPage( $enterpriseId , $type = null ,$status = null, PageHelper $pageHelper){
        $data      = $this->getInvitedEmps( $enterpriseId,$type,$status,$pageHelper->getStartRow(),$pageHelper->getPageSize());
        $totalRows = $this->countInvitedEmps( $enterpriseId,$type,$status);
        $pageHelper->setData( $data )->setTotalRows($totalRows);
        return $pageHelper;
    }

    /**
     *  推荐员工 ； 条件是 企业名称相同，但未 加入企业
     */
    public function getRecommendEmpsWithPage( $enterpriseId ,PageHelper $pageHelper ){
        $com = TbEnterprise::findFirst("id='$enterpriseId'");
        if( !$com)
            return $pageHelper;
        $data      = $this->getRecommendEmps( $com->enterprise_name,$pageHelper->getStartRow(),$pageHelper->getPageSize());
        $totalRows = $this->countRecommendEmps( $com->enterprise_name );
        $pageHelper->setData( $data)->setTotalRows( $totalRows);
        return $pageHelper;
    }


    public function getRecommendEmps( $companyName, $startRow,$pageSize){
        $sql    = " SELECT id,usertype,mobile,username,email,avatarpicurl,unverify_enterprisename,real_name FROM users WHERE unverify_enterprisename =? AND (enterpriseid IS NULL OR enterpriseid ='') ORDER BY created_at  limit $startRow,$pageSize ";
        $params = [ $companyName ];
        return $this->db->query($sql,$params)->fetchAll();
    }

    public function countRecommendEmps( $companyName ){
        $sql = " SELECT COUNT(*) FROM users WHERE unverify_enterprisename =? AND (enterpriseid IS NULL OR enterpriseid ='')";
        $params = [ $companyName ];
        return $this->db->query($sql,$params)->fetch()[0];
    }

    /**
     *  企业 未分组的员工 （带分页）
     */
    public function getEmpsNoGroupWithPage( $enterpriseId,PageHelper $pageHelper){
        $pageHelper->setData($this->getEmpsNoGroup( $enterpriseId,$pageHelper->getStartRow(),$pageHelper->getPageSize()))
                   ->setTotalRows($this->countEmpsNoGroupWithPage($enterpriseId));
        return $pageHelper;
    }

    public function  getEmpsNoGroup( $enterpriseId ,$startRow,$pageSize){
        $sql    = " SELECT id,usertype,mobile,username,email,avatarpicurl,unverify_enterprisename,real_name FROM users WHERE enterpriseid =? AND ( enterprise_groupid IS NULL OR enterprise_groupid ='' ) ORDER BY created_at limit $startRow,$pageSize ";
        $data   = $this->db->query($sql,[ $enterpriseId ])->fetchAll();
        return $data;
    }

    public function countEmpsNoGroupWithPage( $enterpriseId ){
        $sql     = " SELECT COUNT(*) FROM users WHERE enterpriseid =? AND ( enterprise_groupid IS NULL OR enterprise_groupid ='' )";
        $params  = [ $enterpriseId ];
        return  $this->db->query($sql,$params)->fetch()[0];
    }



}