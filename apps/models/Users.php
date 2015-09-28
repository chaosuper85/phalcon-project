<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Users extends \Phalcon\Mvc\Model
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
    public $usertype;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $telephone_number;

    /**
     *
     * @var string
     */
    public $contactName;

    /**
     *
     * @var string
     */
    public $contactNumber;

    /**
     *
     * @var string
     */
    public $pwd;

    /**
     *
     * @var string
     */
    public $salt;

    /**
     *
     * @var integer
     */
    public $enable;

    /**
     *
     * @var integer
     */
    public $regist_platform;

    /**
     *
     * @var string
     */
    public $remember_token;

    /**
     *
     * @var string
     */
    public $regist_version;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $invite_userid;

    /**
     *
     * @var string
     */
    public $avatarpicurl;

    /**
     *
     * @var string
     */
    public $enterprise_licence;

    /**
     *
     * @var integer
     */
    public $enterpriseid;

    /**
     *
     * @var string
     */
    public $unverify_enterprisename;

    /**
     *
     * @var integer
     */
    public $enterprise_groupid;

    /**
     *
     * @var string
     */
    public $invite_token;

    /**
     *
     * @var string
     */
    public $real_name;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        /*
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }
        */

        return true;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
