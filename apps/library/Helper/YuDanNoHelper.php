<?php
namespace Library\Helper;
use Exception;
use Library\Log\Logger;

/**
 *  运单号
 */
class YuDanNoHelper
{

    const  debug = 1;
    static $workerId;
    static $twepoch = 1361775855078;
    static $sequence = 0;
    const  workerIdBits = 4;
    static $maxWorkerId = 15;
    const  sequenceBits = 10;
    static $workerIdShift = 10;
    static $timestampLeftShift = 14;
    static $sequenceMask = 1023;
    private static $lastTimestamp = -1;

    function __construct($workId)
    {
        if ($workId > self::$maxWorkerId || $workId < 0) {
            throw new Exception("worker Id can't be greater than 15 or less than 0");
        }
        self::$workerId = $workId;
    }

    function timeGen()
    {
        //获得当前时间戳
        $time = explode(' ', microtime());
        $time2 = substr($time[0], 2, 3);
        $timestramp = $time[1] . $time2;
        return $time[1] . $time2;
    }

    function  tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

     function  nextId()
    {
        $timestamp = $this->timeGen();
        if (self::$lastTimestamp == $timestamp) {
            self::$sequence = (self::$sequence + 1) & self::$sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis(self::$lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        if ($timestamp < self::$lastTimestamp) {
            throw new \Exception("Clock moved backwards.  Refusing to generate id for " . (self::$lastTimestamp - $timestamp) . " milliseconds");
        }
        self::$lastTimestamp = $timestamp;
        $res1  =  ((sprintf('%.0f', $timestamp) - sprintf('%.0f', self::$twepoch)));
        $res2  = (self::$workerId << self::$workerIdShift);
        $res3  = self::$sequence;
        $nextId =  $res1 | $res2 | $res3 ;
        Logger::info("\n  \t timestamp:".$timestamp."  \t twepoch:".self::$twepoch."\t  res1:".$res1." \t res2:".$res2." \t res3:".$res3." \t workId:".$nextId." \n");
        return $nextId;
    }

}