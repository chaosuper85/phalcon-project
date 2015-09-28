<?php


namespace Services;


use Library\Log\Logger;
use \UnitTestCase as UnitTestCase;






    /**
        select now();//输出当前数据库时间
        select sysdate();//输出系统时间
        select curdate();//输出 2011-11-08
        select curtime();//输出17:18:49

        $year_int = 0;
        $month_int = 0;
        $day_int = 0;
        $str = date('Y ').date('m ').date('d');
        sscanf($str,"%s%s%s",$year_int,$month_int,$day_int);
        // 显示类型和值
        var_dump($year_int.$month_int.$day_int);
    **/



class UserServiceTest extends UnitTestCase
{
    public function testMain()
    {
//        $data[] = array();
// $str = 'sdfdsf';
//        sscanf($str,"%s%s%s",date('Y'),date('m'),date('d'));
//        var_dump($data);


//        //导入船公司
//        $start = time();
//        $arr = array();
//        $model = '\ActivityLog';
//$test = new $model();
//        var_dump(property_exists($test,'reamId'));die;

        ///---
//
//        $sum = 0;
//        foreach($arr as $v) {
//            $ship = new \ShipName();
//            $ship->china_name = $v['china_name'];
//            $ship->eng_name = $v['eng_name'];
//            $ship->shipname_code = $v['shipname_code'];
//            $ret = $ship->save();
//
//            if( !$ret)
//                var_dump($ship->getMessages());
//            else
//                $sum+=1;
//        }
//
//        $end = time();
//        var_dump($sum.'  time:'.$end-$start);die;
//        //导入船公司








//        $sql = " select user_id from admin_user_group where id = (select min(id) from admin_user_group where user_id > 1) ";
//
//var_dump($this->di['db']->fetchOne($sql,2));

        //var_dump($this->di->get('JpushService')->toGroup('admin','test'));
//var_dump($this->di->get('DriverService')->createByExcel());
        //var_dump( $this->di->get('DriverService')->createByExcel(1,32));
        //var_dump( $this->di->get('DriverService')->create('SS',1,'13720044402',23));

        //var_dump( $this->di->get('JiZhanYunService')->getPos(1,59051,20857));
        //var_dump($this->di->get('PVService')->queryPVUVInfo('2015-07-02 16:49:19','2015-07-02 16:55:19','','','pv'));


        //$this->assertFalse( false );
    }


}