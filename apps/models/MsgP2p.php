<?php

class MsgP2p extends \Phalcon\Mvc\Model
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
    public $sender_id;

    /**
     *
     * @var string
     */
    public $rec_id;

    /**
     *
     * @var integer
     */
    public $msg_type;

    /**
     *
     * @var integer
     */
    public $msg_status;

    /**
     *
     * @var string
     */
    public $msg_title;

    /**
     *
     * @var string
     */
    public $msg_content;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var string
     */
    public $read_time;

    /**
     *
     * @var string
     */
    public $exp_time;

    /**
     *
     * @var string
     */
    public $deal_url;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'msg_p2p';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MsgP2p[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MsgP2p
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
