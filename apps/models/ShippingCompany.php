<?php

class ShippingCompany extends \Phalcon\Mvc\Model
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
    public $company_code;

    /**
     *
     * @var string
     */
    public $china_name;

    /**
     *
     * @var string
     */
    public $english_name;

    /**
     *
     * @var integer
     */
    public $companny_type;

    /**
     *
     * @var string
     */
    public $phone_mobile;

    /**
     *
     * @var string
     */
    public $com_address;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var integer
     */
    public $type;

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
        return 'shipping_company';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ShippingCompany[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ShippingCompany
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
