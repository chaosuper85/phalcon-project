<?php

class ShipName extends \Phalcon\Mvc\Model
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
    public $shipping_companyid;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var string
     */
    public $com_address;

    /**
     *
     * @var string
     */
    public $phone_mobile;

    /**
     *
     * @var string
     */
    public $shipname_code;

    /**
     *
     * @var string
     */
    public $china_name;

    /**
     *
     * @var string
     */
    public $eng_name;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $update_time;

    /**
     *
     * @var string
     */
    public $pinyin;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ship_name';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ShipName[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ShipName
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
