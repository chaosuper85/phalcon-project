<?php

class EventDailyStatistic extends \Phalcon\Mvc\Model
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
    public $year_int;

    /**
     *
     * @var integer
     */
    public $month_int;

    /**
     *
     * @var integer
     */
    public $day_int;

    /**
     *
     * @var integer
     */
    public $static_event_type;

    /**
     *
     * @var integer
     */
    public $child_action_type;

    /**
     *
     * @var integer
     */
    public $times_int;

    /**
     *
     * @var integer
     */
    public $platform_type;

    /**
     *
     * @var string
     */
    public $record_time;

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
        return 'event_daily_statistic';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EventDailyStatistic[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EventDailyStatistic
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
