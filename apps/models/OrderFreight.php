<?php

class OrderFreight extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $yundan_code;

    /**
     *
     * @var integer
     */
    public $freightagent_user;

    /**
     *
     * @var string
     */
    public $cock_city_code;

    /**
     *
     * @var integer
     */
    public $import_type;

    /**
     *
     * @var integer
     */
    public $carrier_userid;

    /**
     *
     * @var string
     */
    public $tixiangdan_file_url;

    /**
     *
     * @var string
     */
    public $addresscontact_file_urls;

    /**
     *
     * @var string
     */
    public $tidan_code;

    /**
     *
     * @var integer
     */
    public $ship_name_id;

    /**
     *
     * @var integer
     */
    public $shipping_company_id;

    /**
     *
     * @var string
     */
    public $ship_ticket;

    /**
     *
     * @var string
     */
    public $ship_ticket_desc;

    /**
     *
     * @var integer
     */
    public $yard_id;

    /**
     *
     * @var string
     */
    public $product_name;

    /**
     *
     * @var string
     */
    public $product_desc;

    /**
     *
     * @var integer
     */
    public $product_weight;

    /**
     *
     * @var integer
     */
    public $product_box_type;

    /**
     *
     * @var integer
     */
    public $box_20gp_count;

    /**
     *
     * @var integer
     */
    public $box_40gp_count;

    /**
     *
     * @var integer
     */
    public $box_40hq_count;


    /**
     *
     * @var integer
     */
    public $box_45hq_count;

    /**
     *
     * @var integer
     */
    public $order_status;

    /**
     *
     * @var string
     */
    public $order_total_percent;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var string
     */
    public $submit_time;

    /**
     *
     * @var string
     */
    public $update_time;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'order_freight';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderFreight[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderFreight
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
