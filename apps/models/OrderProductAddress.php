<?php

class OrderProductAddress extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $order_freight_id;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var string
     */
    public $contact_number;

    /**
     *
     * @var integer
     */
    public $address_provinceid;

    /**
     *
     * @var integer
     */
    public $address_cityid;

    /**
     *
     * @var integer
     */
    public $address_townid;

    /**
     *
     * @var string
     */
    public $address_detail;

    /**
     *
     * @var double
     */
    public $latitude;

    /**
     *
     * @var double
     */
    public $longitude;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var string
     */
    public $update_time;

    /**
     *
     * @var integer
     */
    public $enable;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'order_product_address';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderProductAddress[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderProductAddress
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
