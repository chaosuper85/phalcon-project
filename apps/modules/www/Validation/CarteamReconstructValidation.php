<?php
namespace Modules\www\Validation;
use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;


class CarteamReconstructValidation extends Validation{
    public function initialize(){
        $noNull = array('login_user', 'password', 'orderid', 'ship_ticket', 'tidan_code',
            'product_box_type', 'box_20gp_count', 'box_40gp_count', 'box_40hq_count', 'product_weight');
        foreach($noNull as $key)
            $this->add($key, new PresenceOf(array(
                'message' => 'The '.$key.' is required'
            )));
    }
}