<?php

class PageViews extends \Phalcon\Mvc\Model
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
    public $page_url;

    /**
     *
     * @var string
     */
    public $ip;

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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'page_views';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PageViews[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PageViews
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
