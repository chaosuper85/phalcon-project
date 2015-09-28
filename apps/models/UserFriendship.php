<?php

class UserFriendship extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $friend_token;

    /**
     *
     * @var integer
     */
    public $friendship_type;

    /**
     *
     * @var integer
     */
    public $invite_type;

    /**
     *
     * @var integer
     */
    public $friendship_status;

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
     *
     * @var string
     */
    public $agree_time;

    /**
     *
     * @var string
     */
    public $exp_time;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_friendship';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserFriendship[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserFriendship
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
