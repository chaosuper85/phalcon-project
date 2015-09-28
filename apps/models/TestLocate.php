<?php

class TestLocate extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

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
    public $locate_info;

    /**
     *
     * @var integer
     */
    public $locate_type;

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
        return 'test_locate';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TestLocate[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TestLocate
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
