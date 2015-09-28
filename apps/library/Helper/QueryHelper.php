<?php   namespace Library\Helper;
use Monolog\Logger;

/**
 * 在ORM查询前加入数据做分页、筛选、排序语句
 * Auth: haibo
 * Time: 15-8-1
 * MODIFY:15-8-18 加入防注入
 */
class QueryHelper
{

    public static $cond;
    public static $page;
    protected static $op_list = array(''=>'=','>'=>'>','<'=>'<','~'=>'LIKE'/*支持度低*/,'!'=>'<>');//组装查询语句

    /**
     * 功能: ORM的形式增加查询的过滤、排序条件
     * 使用: 单表查询的利器，支持分页，时间过滤，任意条件筛选，排序，分组。
     * @param $model
     * @param $request
     * @param bool $options
     * @param bool $isQuery
     * @return bool
     */
    public  static  function  cond($model, $request, $options = false, $isQuery = true)
    {
        if( empty($model))
            return false;

        //查询参数
        if( $options) {
            $order = isset($options['order']) ? $options['order']:null;
            $column = isset($options['columns']) ? $options['columns']:null;
        }

        $page_size = intval($request->get('page_size',null,10));
        $page_num = intval($request->get('page_no',null,1));
        $begin_time = $request->get('begin_time');
        $end_time = $request->get('end_time');
        $param = $request->get();
        unset($param['_url']);
        unset($param['PHPSESSID']);
        unset($param['page_size']);
        unset($param['page_no']);
        unset($param['begin_time']);
        unset($param['end_time']);

        //ORM的过滤器
        $bind = array();
        $cond = &self::$cond;
        self::$cond = array('bind'=>&$bind);

        //加入时间过滤
        if( $begin_time || $end_time)
        {
            $cond['conditions'] = '';
            if( property_exists($model,'created_at'))
            {
                if( $begin_time) {
                    $cond['conditions'] = "created_at > :begin_time:";
                    $bind['begin_time'] = $begin_time;
                }else {
                    $cond['conditions'] = "TRUE";
                }
                if( $end_time) {
                    $cond['conditions'] .= " AND created_at < :end_time: ";
                    $bind['end_time'] = $end_time;
                }
            }
            else if(property_exists($model,'create_time'))
            {
                if( $begin_time) {
                    $cond['conditions'] = "create_time >= :begin_time:";
                    $bind['begin_time'] = $begin_time;
                }else {
                    $cond['conditions'] = "TRUE";
                }
                if( $end_time) {
                    $cond['conditions'] .= " AND create_time <= :end_time: ";
                    $bind['end_time'] = $end_time;
                }
            }
        }

        // 加入Order
        if( isset($order)) {
            $cond['order'] = $order;
        }

        //加入columns
        if( isset($column)) {
            $cond['columns'] = $column;
        }

        //加入字段过滤
        foreach($param as $column=>$value)
        {
            $op = '=';
            if( !isset($cond['conditions']))
                $cond['conditions'] = '';
            else
                $cond['conditions'] .= ' AND ';

            if( !empty(self::$op_list[$value[0]])) {
                $op = self::$op_list[$value[0]];
                $value = explode($value[0], $value, 2)[1];
            }

            if( property_exists($model,$column)) {    //组装
                $cond['conditions'] .=  "$column $op :$column: ".' AND ';
                $bind[$column] = $value;
            }else {
                unset($param[$column]);
            }

            $cond['conditions'] = substr($cond['conditions'], 0, -4);   //修剪
        }

        //保存分页信息
        self::$page['paras'] = $param;
        $begin_time && self::$page['paras']['begin_time'] = $begin_time;
        $end_time && self::$page['paras']['end_time'] = $end_time;
        if($page_num > 0) {
            $count = $model::count($cond);
            self::$page['data_sum'] = $count;
            self::$page['page_size'] = $page_size;
            self::$page['page_no']  = $page_num;
            self::$page['page_sum'] = intval( ceil($count/$page_size));
            //加入Limit
            $page_start = intval(($page_num-1)*$page_size);
            $cond['limit'] = ['number'=>$page_size, 'offset'=>$page_start];
        }

        //查询
        if( $isQuery){
            $res = $model::find($cond);
            self::$page['data'] = $res->toArray();
            if( !isset(self::$page['data_sum']))
                self::$page['data_sum'] = $res->count();
            return self::$page;
        }else {
            return $cond;
        }


    }


    /**
     * 功能: 追加分页数据
     * @param $ret
     * @param bool $count
     * @return bool
     */
    public static function page(&$ret, $count=false)
    {
        $ret = array('data'=>$ret);

        if( empty(self::$page)) {
            self::$page['page_size'] = 10;
            self::$page['page_no']  = 1;
            self::$page['begin_time'] = '';
            self::$page['end_time'] = '';
            self::$page['data_sum'] = $count;
        }

        $ret['paras']['begin_time']  = self::$page['begin_time'];
        $ret['paras']['end_time']    = self::$page['end_time'];
        $ret['page_size']   = self::$page['page_size'];
        $ret['page_no']     = self::$page['page_no'];

        if( isset(self::$page['data_sum'])) {
            $ret['data_sum']  = self::$page['data_sum'];
            $ret['page_sum'] = intval( ceil($ret['data_sum']/self::$page['page_size']));
        }

        return true;
    }


    //数据库数字字段转中文
    public  static function  codeToWord(&$data,$cfg)
    {}


    /**
     * eg:
     * $param['conditions'][] = 'a=3';
     * $param['conditions'][] = 'b<?1';
     * $param['bind'] = [1=>'4'];
     * $param['columns'] = 'id,time';
     * $param['page_no'] = 2(分页)/false(不分页);...........
     * $param['model'] = '\AdminLog';   //如果加了model，就会将执行结果存入data['data']
     * $o_data = array();
     * QueryHelper::query($param,$o_data);
     *
     * 功能: 单表查询，支持分页、筛选、返回数据补全。
     * @param $param
     * @param $data
     * @return mixed
     */
    //todo update by haibo
    public  static function  query(&$param, &$data)
    {
        //查询参数
        $model = isset($param['model']) ? $param['model']:false;
        $conditions = isset($param['conditions']) ? $param['conditions']:false;
        $column = isset($param['columns']) ? $param['columns']:false;

        $page_size = isset($param['page_size']) ? $param['page_size']:10;
        $page_num = isset($param['page_no']) ? $param['page_no']:false;

        $begin_time = isset($param['begin_time']) ? $param['begin_time']:false;
        $end_time = isset($param['end_time']) ? $param['end_time']:false;
        $order = isset($param['order']) ? $param['order']:false;

        //ORM的过滤器
        $bind = array();
        $cond = array();

        if( $column) {
            $cond['columns'] = $column;
        }
        //加入时间过滤
        if( $begin_time || $end_time) {
            $cond['conditions'] = '';

            if( $begin_time) {
                $cond['conditions'] = "created_at > :begin_time:";
                $bind['begin_time'] = $begin_time;
            }else {
                $cond['conditions'] = "TRUE";
            }
            if( $end_time) {
                $cond['conditions'] .= " AND created_at < :end_time: ";
                $bind['end_time'] = $end_time;
            }
        }

        //加入字段过滤   todo more test
        if( $conditions)
        {
            if( !isset($cond['conditions']))
                $cond['conditions'] = '';
            else
                $cond['conditions'] .= ' AND ';

            //组装查询语句
            $obj = new $model();
            $ops = array('=','>','<','<>','LIKE');
            foreach( $conditions as $k=>$v)
            {
                foreach($ops as $op) {       //拆分
                    $arr = explode($op,$v,2);
                    if( count($arr)==2) {
                        $left = $arr[0];
                        $mid  = $op;
                        $right = $arr[1];
                        break;
                    }
                }
                if( isset($left) && property_exists($obj,$left)) {    //组装
                    $cond['conditions'] .=  "$left $mid ?$k  ".' AND ';
                    $bind[$k] = $right;
                }
            }
            $cond['conditions'] = substr($cond['conditions'], 0, -4);   //修剪
        }

        //加入Order
        if( $order) {
            $cond['order'] = '';
            foreach($order as $v) {
                $left = strstr($v,' ',true);
                $right = strstr($v,' ',false);
                if( $right!=' desc' && $right!=' asc')
                    $right = ' desc';
                if( !$left)
                    continue;

                $cond['order'] .= $left.$right.',';
            }
            $cond['order'] = substr($cond['order'],0,-1);
        }

        $count = 0;
        if( $model) {
            $cond['bind'] = $bind;
            $count = $model::count($cond);
        }


        //加入Limit
        if( $page_num) {
            $page_start = ($page_num-1)*$page_size;
            $cond['limit'] = array('number' => intval($page_size), 'offset' => intval($page_start));
        }

        //开始查询
        $res = false;
        if( $model) {
            $cond['bind']=$bind;
            $res = $model::find($cond);
        }

        //保存分页信息
        $data['data_sum']  = $res?$res->count():0;
        $data['data']      = $res?$res->toArray():array();
        $data['page_sum']  = intval( ceil( $count/$page_size));
        $data['begin_time']= $begin_time;
        $data['end_time']  = $end_time;
        $data['page_size'] = $page_size;
        $data['page_no']  = $page_num;

        return $data['data_sum'];
    }

//    public  static function querySql(&$reg,$sqlWithoutWhere)
//    {
//
//    }

}