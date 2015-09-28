<?php

class TbFunction extends \Phalcon\Mvc\Model
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
    public $function_code;

    /**
     *
     * @var string
     */
    public $function_name;

    /**
     *
     * @var string
     */
    public $function_url;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $enterprise_type;

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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tb_function';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbFunction[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbFunction
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
