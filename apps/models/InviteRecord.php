<?php

class InviteRecord extends \Phalcon\Mvc\Model
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
    public $inviter_userid;

    /**
     *
     * @var integer
     */
    public $invitee_userid;

    /**
     *
     * @var string
     */
    public $inviteer_enterpriseid;

    /**
     *
     * @var integer
     */
    public $invite_type;

    /**
     *
     * @var integer
     */
    public $status;

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
        return 'invite_record';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return InviteRecord[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return InviteRecord
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
