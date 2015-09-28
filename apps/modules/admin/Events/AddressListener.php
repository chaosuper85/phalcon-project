<?php

namespace Modules\admin\Events;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use \Library\Helper\LocationHelper;


class AddressListener extends Component
{

    /**
     *   {
     *       "addressId"     =>
     *   }
     */
    public function  getLocation( $event )
    {
        $data = $event->getData();
        Logger::info("getLocation data:%s",$data);
        if (!empty($data)) {
            $addressId = $data;
            $address = \OrderProductAddress::findFirst("id=$addressId");
            if (!empty($address)) {
                $location = LocationHelper::getLocationByAdress($address->address_detail);
                if ( !$location ) {
                    $detailAddress = $this->OrderProductAddressService->getDetailAddressById($addressId);
                    $location = LocationHelper::getLocationByAdress($detailAddress);
                }
                if ( $location ) {
                    $resArr = explode(",", $location );
                    Logger::info("LocationHelper::getLocationByAdress return :%s, length:%s", var_export($resArr, true), count($resArr));
                    if (count($resArr) == 2) {
                         $latitude  = $resArr[0];// 纬度
                         $longitude = $resArr[1];// 经度
                        $address->latitude  =  round( floatval( $longitude),9);
                        $address->longitude =  round( floatval( $latitude),9);
                        $res = $address->update();
                        Logger::info("updateAddressEvent: id:%s,productAddress:%s, longitude:%s,latitude:%s,result:%s", $addressId,$address->address_detail, $longitude, $latitude, $res);
                    }
                }
            }
        }
    }
}