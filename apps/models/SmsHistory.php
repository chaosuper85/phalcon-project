<?php

class SmsHistory extends \Phalcon\Mvc\Model
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
    public $objectId;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
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
    public $mobile;

    /**
     *
     * @var string
     */
    public $msgContent;

    /**
     *
     * @var integer
     */
    public $channel;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'sms_history';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SmsHistory[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SmsHistory
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
