<?php
namespace Services\DataService;

use Library\Helper\StringHelper;
use SpamUsername;
use Library\Log\Logger;


use Phalcon\Mvc\User\Component;

/**
 *  过滤敏感词汇
 *  bo
 */
class SpamUserNameService extends  Component
{

    /**
     *  过滤用户名 存在过滤词 =》true  否则 false
     * @return bool
     */
    public function filterName( $userName ,&$result = array() ){
        // 过滤 单词的数组
        $filters = StringHelper::filterNum( $userName );
        Logger::info(" userName:".$userName."  filterArray:".var_export($filters,true));
        foreach( $filters as $word ){
            if ( $this->checkExist( $word )){ // 存在 过滤词
                Logger::info(" userName:".$userName."  filterArray:".var_export($filters,true)."  wordExist:".$word." need filter");
                $result['filterWord'] = $word;
                return  true;
            }
        }
        return false;
    }

    public function checkExist( $word ){
        return SpamUsername::count( ["spamword= ?1 ", 'bind'=>[1=>$word]]) >0 ? true:false;
    }


    //增加一个敏感词
    public function add($word)
    {
        $spam = \SpamUsername::findFirst( ["spamword= ?1 ", 'bind'=>[1=>$word]]);
        if($spam)
            return true;

        $spam = new \SpamUsername();
        $spam->spamword = $word;
        $ret = $spam->save();

        Logger::info('spamusername-add '.var_export($spam->getMessages(),true));
        return $ret;
    }

    //删除一个敏感词
    public function del($word)
    {
        $spam = \SpamUsername::findFirst( ["spamword= ?1 ", 'bind'=>[1=>$word]]);
        if( !$spam)
            return false;

        $ret = $spam->delete();

        Logger::info('spamusername-add '.var_export($spam->getMessages(),true));
        return $ret;
    }

    //显示所有敏感词
    public function words(&$out)
    {
        $out = \Library\Helper\QueryHelper::cond('\SpamUsername',$this->request);

        Logger::info('Spam-words sum:'.$out['data_sum']);
        return $out['data_sum'];
    }


}