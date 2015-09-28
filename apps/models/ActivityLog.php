<?php

class ActivityLog extends \Phalcon\Mvc\Model
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
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var integer
     */
    public $action_type;

    /**
     *
     * @var integer
     */
    public $child_action_type;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $reamId;

    /**
     *
     * @var integer
     */
    public $reamType;

    /**
     *
     * @var string
     */
    public $targetReamId;

    /**
     *
     * @var integer
     */
    public $targetReamType;

    /**
     *
     * @var integer
     */
    public $platform;

    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var string
     */
    public $deviceId;

    /**
     *
     * @var string
     */
    public $jsonContent;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'activity_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ActivityLog[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ActivityLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
