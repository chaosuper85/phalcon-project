<?php

class TbTickets extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $target_type;

    /**
     *
     * @var string
     */
    public $target_id;

    /**
     *
     * @var string
     */
    public $employee_id;

    /**
     *
     * @var integer
     */
    public $ticket_type;

    /**
     *
     * @var string
     */
    public $ticket_title;

    /**
     *
     * @var string
     */
    public $ticket_text_content;

    /**
     *
     * @var string
     */
    public $ticket_attach_content_url;

    /**
     *
     * @var integer
     */
    public $ticket_status;

    /**
     *
     * @var integer
     */
    public $ticket_result;

    /**
     *
     * @var string
     */
    public $ticket_result_info;

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
    public $settle_time;

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
        return 'tb_tickets';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbTickets[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbTickets
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
