<?php

class AdminFunction extends \Phalcon\Mvc\Model
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
    public $url;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $pid;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var integer
     */
    public $public;

    /**
     *
     * @var integer
     */
    public $display;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     *
     * @var string
     */
    public $mark;

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
        return 'admin_function';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminFunction[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminFunction
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
