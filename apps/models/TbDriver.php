<?php

class TbDriver extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $work_status;

    /**
     *
     * @var integer
     */
    public $team_id;

    /**
     *
     * @var string
     */
    public $driver_name;

    /**
     *
     * @var string
     */
    public $id_number;

    /**
     *
     * @var string
     */
    public $car_number;

    /**
     *
     * @var string
     */
    public $Industry_auth;

    /**
     *
     * @var string
     */
    public $drive_number;

    /**
     *
     * @var string
     */
    public $car_trans_auth;

    /**
     *
     * @var integer
     */
    public $enable;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tb_driver';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbDriver[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbDriver
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
