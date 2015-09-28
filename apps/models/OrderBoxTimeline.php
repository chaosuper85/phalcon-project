<?php

class OrderBoxTimeline extends \Phalcon\Mvc\Model
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
    public $boxline_type;

    /**
     *
     * @var integer
     */
    public $verify_ream_type;

    /**
     *
     * @var string
     */
    public $location_msg;

    /**
     *
     * @var string
     */
    public $verify_ream_id;

    /**
     *
     * @var string
     */
    public $jsonContent;

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
        return 'order_box_timeline';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderBoxTimeline[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderBoxTimeline
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
