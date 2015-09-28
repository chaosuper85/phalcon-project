<?php

namespace Library\Cache;

use Redis;

class Cache
{

    protected  $redis;

    public function __construct()
    {
        $this->redis = new Redis();

        $this->connect();
    }

    public function connect()
    {
        $cache_config = require __DIR__."/../../config/cache_config.php";

        $ip     = $cache_config->redis->ip;
        $port   = $cache_config->redis->port;

        $ret = $this->redis->pconnect($ip, $port);

        return $ret;
    }


    public function set( $key, $value, $seconds = 3600 )
    {
        $ret = $this->redis->IsConnected();

        if(  !$ret )
        {
            $this->connect();
        }

        return  $this->redis->setex( $key, $seconds, $value );
    }


    public function get( $key )
    {
        $ret = $this->redis->IsConnected();

        if(  !$ret )
        {
            $this->connect();
        }

        return $this->redis->get( $key );
    }


    public function delete( $key )
    {

        $ret = $this->redis->IsConnected();

        if(  !$ret )
        {
            $this->connect();
        }

        return $this->redis->delete( $key );
    }

    public function exists( $key )
    {

        $ret = $this->redis->IsConnected();

        if(  !$ret )
        {
            $this->connect();
        }

        return $this->redis->exists( $key );
    }

}