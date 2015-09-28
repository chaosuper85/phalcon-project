<?php

class UnarrivedBox extends \Phalcon\Mvc\Model
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
    public $order_freight_id;

    /**
     *
     * @var string
     */
    public $tidan_code;

    /**
     *
     * @var string
     */
    public $box_code;

    /**
     *
     * @var string
     */
    public $box_ensupe;

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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'unarrived_box';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnarrivedBox[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnarrivedBox
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
