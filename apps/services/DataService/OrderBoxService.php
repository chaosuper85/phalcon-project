<?php
namespace Services\DataService;


use Library\Helper\StringHelper;
use Phalcon\Mvc\User\Component;
use OrderFreightBox;
use Library\Log\Logger;
use OrderFreight;
/**
 *  订单的集装箱
 */
class OrderBoxService extends Component
{
    public function  getById( $id ){
        $case =OrderFreightBox::findFirst(array(
            "conditions"  => " id= ?1",
            "bind"        => array( 1 => $id )
        ));
        return $case;
    }
    //产装联系单列表
    public function getOrderFreightBoxList($user_id){
        $arr = array();
        $orderStatus = 3;
        $arrlist = $this->OrderFreightService->getSomeInfoByCarrierid($user_id, $orderStatus);
        $ret = $this->OrderFreightService->completeAllOrderInfo($arrlist);
        if(empty($ret))
            return false;
        //格式化
        return $arrlist;
        $arr = $this->toformatOrderFreightBoxList($arrlist);
        if(empty($arr)){
            Logger::warn('格式化错误!');
            return false;
        }
        return $arr;
    }
    //格式化产装联系单列表
    public function toformatOrderFreightBoxList($arrlist){
        $arr = array();
        $i = 0;
        foreach($arrlist as $key => $value){
            $_20gp = $value['box_20gp_count'];
            while($_20gp > 0){
                $arr[$i] = $value;
                $arr[$i]['box'] = '普通20GP';
                $i++;
                $_20gp--;
            }
            $_40gp = $value['box_40gp_count'];
            while($_40gp > 0){
                $arr[$i] = $value;
                $arr[$i]['box'] = '普通40GP';
                $i++;
                $_40gp--;
            }
            $_40hq = $value['box_40hq_count'];
            while($_40hq > 0){
                $arr[$i] = $value;
                $arr[$i]['box'] = '增高40HQ';
                $i++;
                $_40hq--;
            }
        }
        return $arr;
    }

    /**
     *   货代 确认承接订单时， 创建订单的 集装箱 todo
     */
    public function create( $orderId , $boxSizeType,$weight ){
            $box = new OrderFreightBox();
            $box->order_freight_id = $orderId;
            $box->box_ensupe = "";
            $box->box_code ="";
            $box->box_size_type = $boxSizeType;
            $box->box_weight = $weight;
            $box->box_status = 1;// 待提箱
            $box->contact_telephone = "";
            $box->address_provinceid = 0;
            $box->address_cityid = 0;
            $box->address_townid = 0;
            $box->address_detail = "";
            $res = $box->create();
        if( !$res ){
            Logger::warn("create order:".$orderId."  box error Msg:".var_export($box->getMessages(),true));
        }
        return $res ;
    }

    /**
     *  货代 确认承接订单时， 创建订单的 集装箱 todo
     */
    public function createBoxes( $orderId, $boxTypeNumberArr,$totalWeitht,$units_20GP,$totalBox){
        $res = true;
        $units_20GP = $units_20GP <=0 ? 1:$units_20GP;
        $unitsWeight = round( floatval($totalWeitht / $units_20GP),0,PHP_ROUND_HALF_EVEN); // 每个20GP 的箱子 重量
        try{
            foreach( $boxTypeNumberArr as $boxTypeNum ){
                $boxType = $boxTypeNum['type'];
                $boxNum =  intval( $boxTypeNum['number']) ;
                switch( $boxType ){
                    case "20GP":
                        $type = $this->constant->boxType->$boxType;
                        for( $i=0; $i< $boxNum; $i++){
                            $res  = $this->create( $orderId,$type,$unitsWeight );
                            if( !$res ){
                                break;
                            }
                        }
                        break;
                    case "40GP":
                        $type = $this->constant->boxType->$boxType;
                        for( $i=0; $i< $boxNum; $i++){
                            $res  = $this->create( $orderId,$type,$unitsWeight*2 );
                            if( !$res ){
                                break;
                            }
                        }
                        break;
                    case "40HQ":
                        $type = $this->constant->boxType->$boxType;
                        for( $i=0; $i< $boxNum; $i++){
                            $res  = $this->create( $orderId,$type,$unitsWeight*3 );
                            if( !$res ){
                                break;
                            }
                        }
                        break;

                    case "45HQ":
                        $type = $this->constant->boxType->$boxType;
                        for( $i=0; $i< $boxNum; $i++){
                            $res  = $this->create( $orderId,$type,$unitsWeight*3.5 );
                            if( !$res ){
                                break;
                            }
                        }
                    default:
                        break;
                }

            }
        }catch (\Exception $e){
            Logger::warn("orderId:".$orderId."  create box error:".$e->getMessage());
            $res = false;
        }
        return $res;
    }

    /**
     *  查询订单的 所有的 箱子 by orderId
     * @return OrderFreightBox[] or array()
     */
    public function  listAllBoxByOrderId( $orderId ){
        $boxes = OrderFreightBox::find(array(
            "conditions"  => " order_freight_id = ?1",
            "bind"        => array( 1 => $orderId ),
            "order"       => "id",
        ));
        if( count( $boxes) ){
            return $boxes;
        }else{
            return array();
        }
    }

    /**
     *  导出 订单的所有箱子的 箱号 铅封号
     */
    public  function  exportAllBoxInfo( $orderId ,$userId ){
        $order = OrderFreight::findFirst(array(
            "conditions"    => " id= ?1",
            "bind"          =>  array(1 => $orderId )
        ));
        $data   = array();
        $header = array("箱型","箱号","铅封号");
        try{
            if( !empty( $order ) ){
                $isUser = $userId == $order->carrier_userid || $userId == $order->freightagent_user ;
                if( $isUser ){
                    $boxType =  $this->order_config->box_type_define[ $order->product_box_type ];
                    $boxList =  $this->listAllBoxByOrderId( $orderId );
                    if(!empty( $boxList ) ){
                        foreach( $boxList as $box ){
                            $typeName = $boxType.$this->constant->boxType_Enum[ $box->box_size_type ];
                            $data[] = array( $typeName ,$box->box_code,$box->box_ensupe);
                        }
                    }
                }
            }
            Logger::info("export data:{%s}",var_export( $data,true ));
            $fileName = $this->ExcelService->exportExcel( $header , $data );
        }catch (\Exception $e){
            Logger::info("orderId $orderId export OrderBox error msg:".$e->getMessage());
        }

        if( file_exists( $fileName) ){
            return $fileName;
        }else{
            return false;
        }
    }

    /**
     *  检查箱号 铅封号 去重复
     */
    public function checkBoxCodeAndSeal( $code , $seal , $orderId ,&$result = array() ){
        $codeExist = $this->checkBoxCode( $orderId,$code );
        $sealExist = $this->checkBoxSeal( $orderId,$seal );
        if( $codeExist && $sealExist ){
            $result['error_code'] = 200001;
            $result['error_msg']  = "同一笔订单的箱号、铅封号都已存在，请重新修改。";
        }else{
            if( $codeExist ){
                $result['error_code'] = 200002;
                $result['error_msg']  = "同一笔订单的箱号已经存在，请重新修改。";
            }elseif( $sealExist ){
                $result['error_code'] = 200003;
                $result['error_msg']  = "同一笔订单的铅封号已经存在，请重新修改。";
            }else{
                $result['error_code'] = 0;
                $result['error_msg'] = "同一笔订单的箱号、铅封号都不存在。";
            }
        }
        Logger::info("checkBoxCodeAndSeal orderId:{%s} code:{%s} seal:{%s} result:{%s}",$orderId,$code,$seal,var_export($result,true));
        return $result['error_code'] > 0;
    }

    /**
     *   同一笔订单内 箱号 不能重复
     */
    public function checkBoxCode( $orderId , $code ){
        $exist = true;
        try{
            $sql = "  SELECT COUNT(*) as times FROM order_freight_box WHERE order_freight_id =? AND box_code =? ";
            $res =  $this->db->fetchOne( $sql,2,[ $orderId,$code ]);
            $exist = $res['times'] > 0 ;
        }catch (\Exception $e){
          Logger::warn("checkBoxCode:orderId{%s} code:{%s} error msg:{%s}",$orderId,$code,$e->getMessage());
        }
        return $exist;
    }

    /**
     * 同一笔订单内 铅封号 不能重复
     */
    public function checkBoxSeal( $orderId , $seal ){
        $exist = true;
        try{
            $sql = "  SELECT COUNT(*) as times FROM order_freight_box WHERE order_freight_id =? AND box_ensupe =? ";
            $res =  $this->db->fetchOne( $sql,2,[ $orderId,$seal ]);
            $exist = $res['times'] > 0 ;
        }catch (\Exception $e){
            Logger::warn("checkBoxSeal:orderId{%s} seal:{%s} error msg:{%s}",$orderId,$seal,$e->getMessage());
        }
        return $exist;
    }


}