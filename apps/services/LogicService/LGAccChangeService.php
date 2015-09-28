<?php
/**
 * Created by PhpStorm.
 * User: 后台
 * Date: 15/9/18
 * Time: 下午7:33
 * Auth: haibo
 */

namespace Services\LogicService;

use Library\Helper\StatusHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;



class LGAccChangeService extends Component
{
    const ERR_DEL_GROUP = '删除账户的分组信息失败';
    const ERR_AUDIT_1 = '账户当前不能被做此审核';
    const ERR_AUDIT_2 = '修改账户数据失败，请稍后再试或联系管理员';
    const ERR_AUDIT_3 = '用户审核-申请表状态修改失败';
    const ERR_AUDIT_4 = '用户审核-创建公司失败';
    const MSG_AUDIT_PASS = '您的账户已通过认证！';
    const MSG_AUDIT_REJECT = '您的账户未通过认证。';
    const ERR_SYS2 = '更换账户类型失败，请联系管理员';


    /**
     * 功能:删除后台账户
     * 备注:
     * @param $id
     */
    public function delAdminUsr($id)
    {
        //置状态位为删除态
        $err_msg = $this->AdminUserService->setStatus($id,'delete');
        if( !empty($err_msg)) {
            Logger::warn('delAdminUsr: setStatus fail');
            return $err_msg;
        }

        //用户的组分配置为删除态
        $disable = $this->admin_cfg->GROUP->ASSIGN_FALSE;
        $sql = "UPDATE admin_user_group SET enable=$disable WHERE user_id=$id";
        $isOk = $this->db->execute($sql);
        if( !$isOk) {
            Logger::warn('delAdminUsr: delete group info fail');
            return self::ERR_DEL_GROUP;
        }

        //跟单值为空
        $suc = intval($this->order_config->order_status_enum->TRADE_SUCCESS);
        $close = intval($this->order_config->order_status_enum->TRADE_CLOSE);

        $sql = "SELECT order_freight.id FROM order_freight
                INNER JOIN admin_order ON order_freight.admin_id=admin_order.admin_id
                WHERE admin_order.id=$id AND order_freight.order_status<>$suc AND order_freight.order_status<>$close";
        $res = $this->db->fetchAll($sql,2);
        var_dump($res);die;
        //res:orderids


        //自动更换跟单员
    }







}