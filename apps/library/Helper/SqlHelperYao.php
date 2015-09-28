<?php

namespace Library\Helper;
use Phalcon\Mvc\User\Component;

class SqlHelperYao extends Component {

    /**
     *  分页显示
     */
    public function getTotalPage($tableName, $pageSize, $paramsArr = array()) {
        if(!empty($tableName) && !empty($pageSize)){
            $sql = "select id from $tableName";
            if(!empty($paramsArr)) {
                $i = 0;
                foreach ($paramsArr as $key => $value) {
                    $sql .= $i == 0 ? " where $key = '$value'" : " and $key = '$value'";
                    $i++;
                }
            }
            $totalId = $this->db->fetchAll($sql);
            $totalInfo = 0;
            foreach($totalId as $key)
                $totalInfo++;
            return ($totalInfo % $pageSize > 0) ? (int)($totalInfo / $pageSize + 1) : (int)($totalInfo / $pageSize);
        }
        return -1;
    }
    /*
     *  生成 数组
     */
    public function getArr($tableName, $pageSize, $reConditions, $paramsArr, $order) {
        $result = array(
            'tableName' => $tableName,
            'pageSize' => $pageSize,
            'reConditions' => $reConditions,
            'paramsArr' => $paramsArr,
            'order' => $order
        );
        return $result;
    }
    /**
     *  返回分页信息 及 总页数
     *  参数数组
     * array(
            'tableName' => '',//表名字
            'pageSize' => '',//单页显示文件大小
            'reConditions' => array( //返回字段集合，对 key 不要求
            'reConditions1' => 'value1',
            ),
            'paramsArr' => array( // 搜寻 条件， 'key' => value key字段名 value取值
            'params1_name' => 'value1',
            ),
            'order'=> array(//排序
            'orderBy' => 'orderByName', //排序字段
            'orderStyle' => 'desc' //排序方式
            )
        );
     */
    public function getOnePage($arr = array(), &$total_page, $page = 1) {   //page 将要显示的页数
        if(!empty($sql = $this->autoProduceSQL($arr)) && ($total_page = $this->getTotalPage($arr['tableName'], $arr['pageSize'], $arr['paramsArr'])) != -1){
            $pageSize = $arr['pageSize'];
            $offset = ($page - 1) * $pageSize;
            $sql .= " limit $offset, $pageSize";
            return $this->db->query($sql)->fetchAll();
        }
        return false;
    }
    /**
     *  sql语句自动生成  不考虑表之间的关系
     */
    public function autoProduceSQL($arr = array()) {
        $sql = '';
        if (!empty($arr)) {
            if (!empty($arr['reConditions'])) {
                $i = 0;
                foreach ($arr['reConditions'] as $key => $value) {
                    $sql .= ($i == 0) ? "select $value" : " ,$value";
                    $i++;
                }
            } else
                $sql .= "select *";
            if (!empty($tableName = $arr['tableName']))
                $sql .= " from $tableName";
            else
                return '';
            if (!empty($arr['paramsArr'])) {
                $i = 0;
                foreach ($arr['paramsArr'] as $key => $value) {
                    $sql .= ($i == 0) ? " where $key = '$value'" : " and $key = '$value'";
                    $i++;
                }
            }
            if (!empty($arr_order = $arr['order']))
                if (!empty($arr_order['orderBy'])) {
                    $orderBy = $arr_order['orderBy'];
                    $orderStyle = $arr_order['orderStyle'];
                    $sql .= " order by $orderBy $orderStyle";
                }
        }
        return $sql;
    }
    /**
     * 返回不分页的信息 及 特定的一个
     */
    public function getAllInfo($arr = array()) {
        if(!empty($sql = $this->autoProduceSQL($arr)))
            return $this->db->query($sql)->fetchAll();
        return false;
    }
    /**
     *
     */

}