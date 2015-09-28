<?php

class AppVersions extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $app_url;

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
     * @var integer
     */
    public $enable;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'app_versions';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AppVersions[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AppVersions
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
