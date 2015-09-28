<?php
namespace Services\DataService;

use Library\Helper\ObjectHelper;
use Phalcon\Logger;
use Phalcon\Mvc\User\Component;
use OrderFreightBox;

class OrderFreightBoxService extends Component {
    public function create($orderFreightid){

    }
    //通过 order_freight_id 获取 产装联系单部分信息
    public function getSomeInfoByOrderFreightId($orderFreightId){
        $sql = 'select box_code, box_ensupe from order_freight_box where order_freight_id = ?';
        return $this->db->fetchAll($sql, 2, [$orderFreightId]);
    }
}