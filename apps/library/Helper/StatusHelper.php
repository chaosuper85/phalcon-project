<?php   namespace Library\Helper;

use Library\Log\Logger;

/**
 * Created by PhpStorm.
 * User: wanghaibo
 * Date: 15/8/7
 * Time: 下午12:39
 *
 * 用于带有二进制状态位的model.
 */


class   StatusHelper
{
    static $baseCode = 192; //高位状态集

    protected $status;  //for _set/_get

    protected $model;   //带有二进制状态位的model对象

    protected $cfg;     //配置文件


    //保存状态到数据库
    public function saveModel()
    {
        $ret = $this->model->update();
        
        Logger::info('statusHelper-saveModel info:'.var_export($this->model->getMessages(),true));
        return $ret;
    }

    public function getStatus()
    {
        return $this->model->status;
    }
    /**
     * 功能: 重设状态
     * @param $status_new
     * @return $this
     */
    public function fill($status_new=false)
    {
        if( !is_numeric($status_new)) {
            $status_new = $this->toNumber($status_new);
        }

        $this->updateStatus($status_new);
        return $this;
    }

    /**
     * 功能: 增加一个状态
     * @param $status_new
     * @return $this
     */
    public function add($status_new)
    {
        if( !is_numeric($status_new)) {
            $status_new = $this->toNumber($status_new);
        }

        if( $this->model->status ^ $status_new) {
            $this->model->status += $status_new;
            $this->updateStatus($this->model->status);
        }

        return $this;
    }

    /**
     * 功能:删除一个状态
     * @param $status_old
     * @return $this
     */
    public function del($status_old)
    {
        if( !is_numeric($status_old)) {
            $status_old = $this->toNumber($status_old);
        }

        if( $this->model->status & $status_old) {
            $this->model->status -= $status_old;
            $this->updateStatus($this->model->status);
        }

        return $this;
    }

    /**
     * 功能:上/下移一个状态
     * @param $status_old
     * @return bool
     */
    public function shift($status_old, $scale=2)
    {
        if( !is_numeric($status_old)) {
            $status_old = $this->toNumber($status_old);
        }

        if( $this->del($status_old) && $this->add($scale*$status_old))
            return true;

        return false;
    }

    /**
     * 功能:status和arr是否有交集
     * @param $arr
     * @return bool
     */
    public  function has(&$arr)
    {
        foreach ($arr as $v) {
            if( isset($this->$v))
                return true;
        }

        return false;
    }

    /**
     * @param $model 未经过columns筛选的modelObj
     */
    public function __construct(&$model)
    {
        $this->model = $model;
        $cfg_key =  get_class($model).'_Status';
        $this->cfg = include __DIR__."/../../config/constant.php";
        $this->status = array();

        if( isset($this->cfg->$cfg_key)) {
            $this->cfg = $this->cfg->$cfg_key;
            $this->updateStatus($model->status);
        }else {
            $this->cfg = null;
        }
    }

    public function __get($name)
    {
        $status = $this->status;

        if( !isset($status[$name])) {
            return '';
        }

        return $status[$name];
    }

    public function __set($name, $value)
    {
        $this->status[$name] = $value;

        return $value;
    }


    private function updateStatus($status)
    {
        $this->model->status = $status;

        foreach($this->cfg as $k=>$v) {
            if($v & $this->model->status)
                $this->$k = $v;
            else
                $this->$k = false;
        }
    }

    //状态位名称转数字
    private function toNumber($status_world)
    {
        if( !is_numeric($status_world)) {

            if( !is_array($status_world))
                $status_world = array($status_world);
            $status = 0;
            foreach($status_world as $v) {
                if( isset($this->cfg->$v))
                    $status += $this->cfg->$v;
            }

            return $status;
        }

        return 0;
    }


    //状态位转中文
    private function toWorld($status_num)
    {
        if( is_numeric($status_num)) {

            if( !is_array($status_num))
                $status_world = array($status_num);

            $status = '';
            foreach($this->cfg as $k=>$v) {
                if( $v & $status_num)
                    $status .= $k.'/';
            }
            $status = substr($status,0,-1);

            return $status;
        }

        return 0;
    }

    public static function numToWord_2($cfg,$num)
    {
        $cfg_key =  $cfg.'_Status';
        $cfg = include __DIR__."/../../config/constant.php";
        $cfg && $cfg = $cfg->$cfg_key;
        $word = '';

        foreach($cfg as $k=>$v) {
            if( $k & $num)
                $word .= $v.'/';
        }
        $word = substr($word,0,-1);

        return $word;
    }

    public static function numToWord($cfg,$num)
    {
        $cfg_key =  $cfg.'_STATUS_WORD';
        $cfg = include __DIR__."/../../config/constant.php";
        $cfg && $cfg = $cfg->$cfg_key;


        if( isset($cfg[$num]))
            return $cfg[$num];
        else
            return $num;
    }

}



