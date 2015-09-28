<?php

class CmsContent extends \Phalcon\Mvc\Model
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
    public $content;

    /**
     *
     * @var integer
     */
    public $cms_type;

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
        return 'cms_content';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CmsContent[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CmsContent
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
