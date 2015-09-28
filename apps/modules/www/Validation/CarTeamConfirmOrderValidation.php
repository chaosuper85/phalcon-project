<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;

/**
 *  车队确认接单 字段检查
 */
class CarTeamConfirmOrderValidation extends Validation
{
    /**
     *
     */
    public function __construct(  ){
        $noNull = array(
            "ship_info","product_info","box_type_number","type",
            "number","product_box_type","ship_company_id","ship_name_id","yard_id",
            "box_address_detail","address_provinceId",'address_cityId',
            "address_townId","box_date","product_weight","ship_ticket",
            "orderid","ship_name", "ship_company_name",'contactName','contactNumber'
        );
        $include =  array("type" => array("20GP","40GP","40HQ","45HQ"),"product_box_type" => array( 1,2,3,4,5,6) );
        $number  =  array("number","orderid","product_weight", "product_box_type","address_cityId","yard_id",
            "address_provinceId","address_townId");


        foreach($number as $key){
            $this->add( $key, new Numericality(array(
                'message' => $key.' is not numeric'
            )));
        }

        foreach($noNull as $key){
            $this->add( $key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
        }

        foreach( $include as $key => $value){
            $this->add( $key , new InclusionIn(array('message' => $key.'参数格式不正确 。', 'domain'  => $value )));
        }
    }
}