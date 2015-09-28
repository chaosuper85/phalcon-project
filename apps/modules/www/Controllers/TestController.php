<?php

use Library\Helper\ArrayHelper;
use Library\Helper\IdGenerator;
use Library\Helper\ObjectHelper;
use Library\Helper\OrderLogHelper;
use Library\Helper\StatusHelper;
use Library\Helper\LocationHelper;
use Library\Helper\StringHelper;
use Library\Helper\PageHelper;
use Library\Helper\YuDanNoHelper;
use Modules\www\Validation\AgentCreateOrderValidation;
use Services\Service\AsyncTaskService;


/**
 * @RoutePrefix("/test")
 */
class TestController extends  ControllerBase
{

    /**
     * @Get("/")
     */
    public function testAction(){
        $searchParams = array(
            'searchType' => 0,
            'searchValue' => '',
        );

        //$a = $this->OrderFreightListService->getMsg(866714686, 2, 0, 1, $searchParams);
       // $a = $this->FreightTransportService->getMsg(1);
        //$sql1 = 'SELECT id, codeid FROM `tbl_province` WHERE `id` = ?';
        //$a = $this->db->fetchAll($sql1, 2, [1]);
        //$a = $this->CityService->getSubLocation(52);
        //$b = $this->OrderFreightService->getOrderFreightByid(1);
        //$a = $this->FreightTransportService->getMsg(23);
        //$data = date('Y-m-d h:m:s', time());
        //$sql = 'update order_box_timeline set create_time = ? where id = 1';
        //$a = $this->db->query($sql, [$data]);
        //$str = 'gyao';
        //$aa = md5($str).'|';
        //$bb = md5($str);
        //$a = $this->UserService->checkPasswordWhenLogin(8667$14695, '123456a');
        $ship_info = array(
            'shipping_company_id' => 10,
            'ship_name_id' => 10,
            'ship_ticket' => '星期五',
            'tidan_code' => 21,
            'yard_id' => 10,
        );
        $product_info = array(
            'product_box_type' => 2,
            'box_20gp_count' => 8,
            'box_40gp_count'=> 9,
            'box_40hq_count' => 18,
            'product_name' => 'gyao',
            'product_desc' => 'gyao',
            'product_weight' => 8888,
        );
        //$a = $this->OrderFreightReconstructService->getShipId('dandan');
        //$a = $this->FreightTransportService->getBoxTimelineByBoxId(52);
        //$a = strcmp('我是中国人，我爱我的国家。我为他骄傲', '我是中国人，我爱我的国家。我为他骄傲');
        //die;
        //$a = $this->OrderFreightReconstructService->copyToNewOrder(47, 777103);
        //$b = $this->OrderFreightReconstructService->changeNewOrderInfo($a, $ship_info, $product_info);
        //$a = $this->OrderFreightReconstructService->reConstruct(79, $ship_info, $product_info);

        /*$a = $this->OrderFreightReconstructService->getTuizaiParams(100);
        if(!empty($a)){
            foreach($a as $key => $value){
                $b = 'driverid: '.$value['driver_id'].'   assign_id:'.$value['assign_id'].'||||';
                echo $b;
            }
        }
        die;*/
        $res1 = $this->CityService->addLocation();
        foreach($res1 as $key => $value) {
            echo $value['location'].'|';
        }
        die;
    }

    /**
     * @Get("/doc")
     */
    public function  createdocAction(){
        $this->WordDocService->createDoc();
    }

    /**|
     * @Get("/excute")
     */
    public function excuteAction(  ){
         $order = new OrderFreight();
        $order->cock_city_code = "3030";
        $order->freightagent_user = "2"; // 货代
        $order->import_type = 1; // 进出口类型
        $order->carrier_userid = "1" ;// 承运人
        $order->adresscontact_file_urls = "3333333";// 产状联系单文件URL json
        $order->tixiangdan_file_url = "dddddd"; // 提箱单URL json
        $order->tidan_code = "DFHGBCFGHH678".rand( 1000,900000);// 提单号
        $order->order_status = 1;     // 订单状态： 等待承运人 确认接单
        $order->create_time = date('Y-m-d h:i:s',time());
        $order->update_time = date('Y-m-d h:i:s',time());
        ObjectHelper::ifNullDefault( $order );
    }

    /**
     * @Get("/work")
     */
    public function workIdAction(){
        $yundanCode = new YuDanNoHelper( 12 );
        for( $i=0; $i<10000; $i++){
            $id = $yundanCode->nextId();

        }

    }

    /**
     *   @Get("/makeExcel")
     */
    public  function  excelAction(){
        $header = array("xiangzi ","chuizi ","hezi ");
        $data   = array( $header,$header,$header);
        $this->ExcelService->exportExcel( $header , $data ,"test2");
//        $out = array();
      //  $this->ExcelService->read('testxxxx.xlsx', $out, array(''));
        $res = $this->constant->boxType_Enum[2];

        echo $res;
    }

    /**
     *   @Get("/log")
     */
    public function jsonAction(){
        {

//            $data = \Library\Helper\LocationHelper::getLocationByAdress("安徽省安庆市太湖县太湖中学");
//            var_dump( $data );
//            $data = 3;
//            $this->AddressComponent->getLocationEvent( $data );
            $res1 = $this->YardInfoService->create("中国堆场");
            $res2  = $this->YardInfoService->searchYard("天津");
            $re3 = $this->ShipNameService->searchShipName("主");
            $res5 = $this->ShipNameService->create("公主1号");
            $res4 = $this->ShippingCompanyService->search("津");
            $res6 = $this->ShippingCompanyService->create("天剑w公司");
//            $data= array(
//                "tidan" =>"123E",
//                "tixiang" =>"3456",
//                "yundan"=>"DFG",
//            );
//            $val = new AgentCreateOrderValidation( $data );
//            $msf = $val->validate( $data );
//            $re = ArrayHelper::validateMessages( $msf);
//            var_dump( $re );
            $id= 88;
            $sql = "SELECT * FROM tbl_province WHERE id=? ";
            $res = $this->db->fetchOne( $sql, 2,[ $id ]);
            $value  = $this->session->get("user_strratus");


            $time1 ="2014-09-03 12:04:00";
            $time2 = "14-09-03 12:4";
            $time3 = "2014-9-3 12:4:0";
            $date = array(
                $time2,$time1,$time3,"2014-09-03 12:04"
            );


            $s1 = strtotime( $time1 );
            $s2 = strtotime( $time2 );
            $s3 = strtotime( $time3 );
            var_export($time1."=". $time2. ":".$s1 == $s2);
            var_export($time2 ."=". $time3 .":".$s2 == $s3);
            var_export($time3 ."=".$time1 .":".$s3 == $s1);
            $res3 = ArrayHelper::filterSameTime( $date );
            $data = array(
                "carteam" => "",
                "freight" => ""
            );
            $this->ActivityLogComponent->noticeCarTeam( $data );



        }
    }

    /**
     *   @Get("/addDriver")
     */
    public function  createDriverAction(){
        $str =" 1-5-";
        $s = explode("-",$str);
        $this->SearchShipCompanyService->init();

//         $user = $this->getUser();
//         $carteam = $this->CarTeamService->getByUserId( $user->id );
//         if( empty( $carteam ) ){
//             return ;
//         }
//         $data = array();
//         for( $i=0 ;$i++ <10; ){
//
//             $user = new Users();
//             $user->mobile = "13".rand(100000,999999)."888";
//             $user->pwd = "48ecfad6c8f8cad29c8f40123a69390c";
//             $user->salt ="1234";
//             $user->usertype = 3;
//             $user->enable = true;
//             $user->regist_version = "1.0";
//             $user->regist_platform =1;
//             $user->invite_token = time().rand(100000,9999999);
//             $user->create();
//             $driver = new TbDriver();
//             $driver->car_number  = "皖".rand(10000,99999);
//             $driver->driver_name = "司机姓名：".rand(100,1000);
//             $driver->enable = 1;
//             $driver->work_status =1;
//             $driver->team_id = $carteam->id;
//             $driver->userid  = $user->id;
//             $driver->create();
//             $data[] = $driver->toArray();
//         }
//        echo var_export( $data,true) ;
    }

    /**
     *   @Get("/task")
     */
    public function  taskAction()
    {
//        $jobs = array();
//        $task = new AsyncTaskService( 'tube_1');
//        for( $i= 0 ; $i++ < 20; ){
//
//            $data = array('id' => $i );
//            $id   = $task->asyncTask( $data );
//            $jobs[] = $id;
//        header("Cache-Control: public");
//        header("Content-Description: File Transfer");
//        header('Content-disposition: attachment; filename='.basename($filename)); //文件名
//        header("Content-Type: application/zip"); //zip格式的
//        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
//        header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
//        }
        $url = "http://7xjljc.com2.z0.glb.qiniucdn.com/1442391192-73-t1.docx";
        $url2 = "http://7xjljc.com2.z0.glb.qiniucdn.com/1442391187-73-t1";
        $extension = StringHelper::getExtension( $url );
        $res  = strpos( $extension,".");
        $res  =shell_exec("zip  /Users/wanghui/wh/work/project/php/phpweb/apps/var/temp/test.zip /Users/wanghui/wh/work/project/php/phpweb/apps/var/temp/test1.docx ");
        $dir  = "/Users/wanghui/wh/work/project/php/phpweb/apps/var/temp/".time().rand(1000,10000);
        mkdir( $dir );
        shell_exec("rm -rf ".$dir);
        //shell_exec("zip  /Users/wanghui/wh/work/project/php/phpweb/apps/var/temp/test.zip /Users/wanghui/wh/work/project/php/phpweb/apps/var/temp/test1.docx ");


    }
}