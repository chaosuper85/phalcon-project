<?php

namespace Services\DataService;

use LoginRecord;
use \Phalcon\DiInterface;
use Library\Log\Logger;

use Phalcon\Mvc\User\Component;

class LoginRecordService extends  Component
{


    public function create($user_id,$token,$status,$expireDate)
    {
        $record = new LoginRecord();
        $record->created_at = date('Y-m-d h:i:s');
        $record->updated_at = date('Y-m-d h:i:s');
        $record->user_id = $user_id;
        $record->token = $token;
        $record->status = $status;
        $record->expire_date = $expireDate;
        $res  = $record->save();
        Logger::info('create LOginRecord:'.var_export($record->getMessages(),true));
        return $res ? $record : false;
    }

    public function  getByMobileAndToken($user_id, $token)
    {
        $sql = 'select id,user_id,token,status,expire_date from LoginRecord WHERE user_id = ?1 and  token =?2';
        $query  = $this->modelsManager->createQuery($sql);
        $record = $query->execute( array('1' => $user_id, '2' => $token))->getFirst();
        return $record;
    }

    //可用于踢出用户
    public function updateLoginRecord( $user_id, $fromStatus, $toStatus  )
    {
        $conditions = "user_id = :user_id: AND status = :status:";

        $parameters = array(
            "user_id" => $user_id,
            "status" => $fromStatus
        );

        $res = LoginRecord::find(array(
            $conditions,
            "bind" => $parameters
        ));

        foreach( $res as $key => $value )
        {
            $token = $value->token;

            $tokenArr = explode("-", $token);
            $user_id = $tokenArr[0];
            $tokenExpire = $tokenArr[1];


            $expire = $this->constant->LOGIN_RECORD->LOGIN_EXPIRED;
            $expireTime = $this->constant->LOGIN_SESSION_SECONDS;

            //如果以往的记录过期了，修改为过期
            if( time() > $tokenExpire  ){
                $this->cache->set($token, $expire, $expireTime);

                $value->status = $expire;
                $value->save();
            }else{
                $this->cache->set($token, $toStatus, $expireTime);

                $value->status = $toStatus;
                $value->save();
            }
        }

        return 0;
    }
}