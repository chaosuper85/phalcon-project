<?php


namespace Services;


use Library\Log\Logger;
use \UnitTestCase as UnitTestCase;

class TestLocationTest extends UnitTestCase
{
    public function testLocationType()
    {
        $detailType = array(
            'yard_location_type' => 1,
            'yard_car_type' => 2,
            'yard_degree_type' => 0,
        );
        $intType = $this->di->get('YardLocationService')->getLocationTypeByDetail($detailType);
        $this->assertEquals($intType , 3);
        $detailArr = $this->di->get('YardLocationService')->getDetailLocationType($intType);
        $this->assertEquals($detailArr , $detailType);

    }



}
