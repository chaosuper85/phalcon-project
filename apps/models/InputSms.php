<?php

class InputSms extends \Phalcon\Mvc\Model
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
    public $mobileNumber;

    /**
     *
     * @var string
     */
    public $channelnumber;

    /**
     *
     * @var string
     */
    public $addSerialRev;

    /**
     *
     * @var string
     */
    public $addSerial;

    /**
     *
     * @var string
     */
    public $smsContent;

    /**
     *
     * @var string
     */
    public $sentTime;

    /**
     *
     * @var string
     */
    public $receive_time;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'input_sms';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return InputSms[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return InputSms
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
