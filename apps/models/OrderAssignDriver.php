<?php

class OrderAssignDriver extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $order_freight_boxid;

    /**
     *
     * @var integer
     */
    public $order_product_addressid;

    /**
     *
     * @var integer
     */
    public $order_product_timeid;

    /**
     *
     * @var integer
     */
    public $driver_user_id;

    /**
     *
     * @var double
     */
    public $current_latitude;

    /**
     *
     * @var double
     */
    public $current_longitude;

    /**
     *
     * @var integer
     */
    public $assign_status;

    /**
     *
     * @var integer
     */
    public $address_status;

    /**
     *
     * @var integer
     */
    public $drop_box_status;

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
        return 'order_assign_driver';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderAssignDriver[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderAssignDriver
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
