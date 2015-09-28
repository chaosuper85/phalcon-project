<?php

class AdminLog extends \Phalcon\Mvc\Model
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
    public $username;

    /**
     *
     * @var integer
     */
    public $admin_user_id;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var integer
     */
    public $action_type;

    /**
     *
     * @var string
     */
    public $json_content;

    /**
     *
     * @var string
     */
    public $log_msg;

    /**
     *
     * @var integer
     */
    public $targetReamId;

    /**
     *
     * @var integer
     */
    public $targetReamType;

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
        return 'admin_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminLog[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
