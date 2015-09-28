<?php

class OrderFreightBox extends \Phalcon\Mvc\Model
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
    public $box_ensupe;

    /**
     *
     * @var string
     */
    public $box_code;

    /**
     *
     * @var integer
     */
    public $box_size_type;

    /**
     *
     * @var string
     */
    public $box_weight;

    /**
     *
     * @var double
     */
    public $target_latitude;

    /**
     *
     * @var double
     */
    public $target_longitude;

    /**
     *
     * @var string
     */
    public $contact_telephone;

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
     * @var integer
     */
    public $box_status;

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
        return 'order_freight_box';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderFreightBox[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrderFreightBox
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
