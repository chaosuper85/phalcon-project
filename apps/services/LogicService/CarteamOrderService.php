<?php
namespace Services\LogicService;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use OrderAssignDriver;
use TbDriver;

/**  车队订单
 *  CarTeamOrderController
 */
class CarTeamOrderService extends Component
{
    /** 车队查看产装联系单列表
     *  一个箱子 《=》 一个联系单
     */
   public  function  productAddressList( $orderId,$userId ,&$result){
       $order = $this->OrderFreightService->getByOrderId( $orderId );
       $proAddresContactList = array();
       if( !empty( $order ) ) {
           $isOrderUser = $userId == $order->freightagent_user || $userId ==$order->carrier_userid;
           if( $isOrderUser ){
               $boxList = $this->OrderBoxService->listAllBoxByOrderId( $orderId );
               foreach( $boxList as $box ){ //
                   $tempData = array(
                       "box_id"             => $box->id,
                       "box_code"           => $box->box_code,
                       "box_ensupe"         => $box->box_ensupe,
                       "car_number"         => "",
                       "contactNumber"      => "",
                       "driver_name"        => "",
                       "product_box_type"   => $box->box_size_type,
                   );
                   // 箱子分派的信息
                   $boxAssignInfo = OrderAssignDriver::findFirst(array(
                       "conditions" => "order_freight_id = ?1 and order_freight_boxid=?2 ",
                       "bind" => array(1 => $orderId, 2 => $box->id),
                   ));
                   if( !empty( $boxAssignInfo ) ){
                       // 司机信息
                       $driver = TbDriver::findFirst("userid=$boxAssignInfo->driver_user_id");
                       $user   = \Users::findFirst("id=$boxAssignInfo->driver_user_id");
                       $tempData['driver_name']   = empty($driver->driver_name) ? $user->real_name:$driver->driver_name;
                       $tempData['contactNumber'] = $user->mobile;
                       $tempData['car_number']    = $driver->car_number;
                   }
                   $proAddresContactList[] = $tempData;
               }
               $result['orderInfo']   = array("orderId" =>$order->id,"yudan_code" =>$order->yundan_code);
               $result['assign_list'] = $proAddresContactList;
               return true;
           }else{
               $result['error_code'] = 403;
               $result['error_msg']  = "您无权查看的此订单的信息。";
               return false;
           }
       }else{
           $result['error_code'] = 404;
           $result['error_msg']  = "您查看的订单找不到。";
           return false;
       }
   }

    /**
     *  创建产状联系单详情(生成word 文档)
     *  模板：test1.docx
     */
    public function createProductAddressTable(){
        $path =  $this->config->application->tempDir."/test1.docx";
        if( file_exists( $path ) ){
            $document = new TemplateProcessor($path);

        }
    }
}