<?php

class ApplyAdmin extends \Phalcon\Mvc\Model
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
    public $userid;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $enterprise_name;

    /**
     *
     * @var string
     */
    public $enterprise_licence;

    /**
     *
     * @var string
     */
    public $cargo_pic;

    /**
     *
     * @var string
     */
    public $official_letter;

    /**
     *
     * @var string
     */
    public $ownerName;

    /**
     *
     * @var string
     */
    public $ownerIdentityCardId;

    /**
     *
     * @var integer
     */
    public $account_type;

    public $remark;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var string
     */
    public $established_date;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'apply_admin';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ApplyAdmin[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ApplyAdmin
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
