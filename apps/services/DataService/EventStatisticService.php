<?php
namespace Services\DataService;

use Library\Helper\ArrayHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Users;
use OrderProductAddress;
use Library\Helper\QueryHelper;

/**
 * admin事件统计service
 */
class EventStatisticService extends Component
{

    /**
     * @param $eventType
     * @param $timeType 1 hourly  2 daily
     * @param $startTime
     * @param $endTime
     * @param $platform_type 1 www  2 admin
     */
    public function  getTimesByEventType($eventType, $timeType, $startTime, $endTime, $platform_type = 1)
    {
        $times_event_time = $this->admin_cfg->CACHE_TIME->EVENT_TIMES;
        $tableName = 'event_daily_statistic';
        $timeStrLen = 10;

        if ($timeType == 1) {
            $tableName = 'event_hourly_statistic';
            $timeStrLen = 13;
        }
        $sql = "SELECT  static_event_type ,left(record_time ," . $timeStrLen . ")  as timeInfo ,times_int as eventCounts
                from " . $tableName . "
                WHERE  static_event_type = ? ";

        $bind[] = $eventType;
        if (isset($startTime)) {
            $sql .= " and record_time >=  ?";
            $bind[] = $startTime;
        }
        if (isset($endTime)) {
            $sql .= " and record_time <=  ?";
            $bind[] = $endTime;
        }
        if (isset($platform_type)) {
            $sql .= " and platform_type =  ? ";
            $bind[] = $platform_type;
        }

        $sql .= "  order by record_time asc   limit 100 ";
        $results = $this->db->fetchAll($sql, 2, $bind);

        $isOk = $this->cache->set('times_event_' . $timeType, json_encode($results), $times_event_time);
        $isOk || Logger::error('save TimesEvent to cache fail!!!');

        return $results;

    }


    /**
     *
     *
     */
    public function geUserStat( )
    {
        $data = array();
        $sql = "select  usertype  , sum(1) as userCount from  users  GROUP BY usertype";
        $results = $this->db->fetchAll($sql, 2, array());

        foreach ($results as $k => $result) {
            $userType = $result['usertype'];
            $data[$userType]['totalCount'] = $result['userCount'];
            $tableMap = $this->constant->usertype_table_map;
            $tableName = $tableMap[$userType];
            $statusSql = "select  status , sum(1) as totalCount from  " . $tableName . "  GROUP BY status";
            $statusResults = $this->db->fetchAll($statusSql, 2, array());
            $data[$userType]['statusDetail'] = $statusResults;
        }

        return $data ;
    }

    public function geTableStat($tableName , $statusFieldName = 'status')
    {
        $statusSql = "select  " . $statusFieldName . " as status , sum(1) as totalCount from  " . $tableName . "  GROUP BY ".$statusFieldName;
        $statusResults = $this->db->fetchAll($statusSql, 2, array());
        return $statusResults ;
    }

    /**
     * @param $eventTypeArr
     * @param $timeType
     * @param $startTime
     * @param $endTime
     * @param int $platform_type
     * @return array
     */
    public function  getTimesByEventTypes($eventTypeArr, $timeType, $startTime, $endTime, $platform_type = 1)
    {
        $times_event_time = $this->admin_cfg->CACHE_TIME->EVENT_TIMES;
        $tableName = 'event_daily_statistic';
        $timeStrLen = 10;

        if ($timeType == 1) {
            $tableName = 'event_hourly_statistic';
            $timeStrLen = 13;
        }

        $sql = "SELECT   static_event_type, left(record_time ," . $timeStrLen . ")  as timeInfo ,times_int as eventCounts
                from " . $tableName . "
                WHERE  static_event_type  in  ";

        $arr_string = join(',', $eventTypeArr);

        $sql .= "(" . $arr_string . ") ";

        if (isset($startTime)) {
            $sql .= " and record_time >=  ?";
            $bind[] = $startTime;
        }

        if (isset($endTime)) {
            $sql .= " and record_time <=  ?";
            $bind[] = $endTime;
        }

        if (isset($platform_type)) {
            $sql .= " and platform_type =  ?";
            $bind[] = $platform_type;
        }

        $sql .= " order by record_time asc  ";
        $results = $this->db->fetchAll($sql, 2, $bind);
        $resultsByEventId = array();

        foreach ($results as $k => $result) {
            $resultsByEventId[$result['static_event_type']][] = $result;
        }

        $isOk = $this->cache->set('times_event_' . $timeType, json_encode($results), $times_event_time);

        $isOk || Logger::error('save TimesEvent to cache fail!!!');
        return $resultsByEventId;
    }

    /**
     * 功能: 统计某小时的各种事件发生的次数
     * @param $hour_int 0-23
     * @return int
     */
    public function  statTimesHourly($hour_int)
    {
        //todo test by haibo
        $hour_int = $hour_int % 24;

        $year_int = 0;
        $month_int = 0;
        $day_int = 0;
        $str = date('Y ') . date('m ') . date('d');
        sscanf($str, "%s%s%s", $year_int, $month_int, $day_int);

        $time = date('Y-m-d ', time());
        $time_start = $time . "$hour_int:00:00";
        $time_end = $time . ($hour_int + 1) . ":00:00";

        //查询条件
        $param = array();
        $param['columns'] = 'count(id) as sum,action_type as type';
        $param['group'] = 'action_type';
        $param['conditions'] = "created_at > '$time_start' AND created_at < '$time_end'";

        //查询
        $logs = \ActivityLog::find($param)->toArray();
        $admin_logs = \AdminLog::find($param)->toArray();
        if (empty($logs) && empty($admin_logs))
            return 0;

        //数据统计
        $addSql = "INSERT INTO event_hourly_statistic(year_int,month_int,day_int,hour_int,static_event_type,times_int,record_time,platform_type) VALUES";
        foreach ($logs as $v) {
            $addSql .= '(';
            $addSql .= $year_int . ',';
            $addSql .= $month_int . ',';
            $addSql .= $day_int . ',';
            $addSql .= $hour_int . ',';

            $addSql .= $v['type'] . ',';
            $addSql .= $v['sum'] . ',';
            $addSql .= "'" . $time_start . "',";
            $addSql .= 1;
            $addSql .= "),";
        }
        foreach ($admin_logs as $v) {
            $addSql .= '(';
            $addSql .= $year_int . ',';
            $addSql .= $month_int . ',';
            $addSql .= $day_int . ',';
            $addSql .= $hour_int . ',';

            $addSql .= $v['type'] . ',';
            $addSql .= $v['sum'] . ',';
            $addSql .= "'" . $time_start . "',";
            $addSql .= 2;
            $addSql .= "),";
        }
        $addSql = substr($addSql, 0, -1);

        //插入统计结果到表
        $res = $this->db->query($addSql);

        return $res->numRows();
    }

    /**
     * 功能: 统计1天内的各种事件次数
     * @param $day_int 0-31
     * @return int
     */
    public function  statTimesDaily($day_int)
    {
        //todo by haibo
        $year_int = 0;
        $month_int = 0;
        $day_int = $day_int % 31;

        $str = date('Y ') . date('m ');
        sscanf($str, "%s%s", $year_int, $month_int);

        $time = date('Y-m-', time());
        $time_start = $time . ($day_int + 1) . " 00:00:00";
        $time_end = $time . ($day_int + 1) . " 23:59:59";

        //查询条件
        $param = array();
        $param['columns'] = 'sum(times_int) as sum,static_event_type as type,platform_type';
        $param['group'] = 'static_event_type,platform_type';
        $param['conditions'] = "record_time > '$time_start' AND record_time < '$time_end'";

        //查询
        $logs = \EventHourlyStatistic::find($param)->toArray();
        if (empty($logs))
            return 0;

        //数据统计
        $addSql = "INSERT INTO event_daily_statistic(year_int,month_int,day_int,static_event_type,times_int,record_time,platform_type) VALUES";
        foreach ($logs as $v) {
            $addSql .= '(';
            $addSql .= $year_int . ',';
            $addSql .= $month_int . ',';
            $addSql .= $day_int . ',';

            $addSql .= $v['type'] . ',';
            $addSql .= $v['sum'] . ',';
            $addSql .= "'" . $time_start . "',";
            $addSql .= $v['platform_type'];
            $addSql .= "),";
        }
        $addSql = substr($addSql, 0, -1);

        //插入统计结果到表
        $res = $this->db->query($addSql);

        return $res->numRows();
    }


}
