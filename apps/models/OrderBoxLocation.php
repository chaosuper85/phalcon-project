<?php

class OrderBoxLocation extends \Phalcon\Mvc\Model
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
    public $order_assign_driver;

    /**
     *
     * @var integer
     */
    public $order_freight_boxid;

    /**
     *
     * @var double
     */
    public $box_latitude;

    /**
     *
     * @var double
     */
    public $box_longitude;

    /**
     *
     * @var integer
     */
    public $location_source_type;

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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'order_box_location';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderBoxLocation[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderBoxLocation
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
