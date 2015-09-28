<?php
namespace Services\Service;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

/**
 *  抓取船公司信息
 */
class SearchShipCompanyService extends Component
{

    const  api     = "http://www.chinaports.com/shiptracker/newshipquery.do";
    const  initURL = "http://www.chinaports.com/shiptracker/shipinit.do";



    public function searchCompany( $param )
    {
        $this->init();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,self::api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"vession=0&method=search&isall=0&cnqp=$param&queryParam=$param");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
        $response = curl_exec($ch);
        /**
         *   <ul class="dw-r">
                <li class="longtd" title="AL QIBLA"><b>船名：</b>AL QIBLA</li>
                <li class="longtd" title=""><b>中文船名：</b></li>
                <li class="longtd"><b>呼号：</b>9KEW</li>
                <li class="longtd"><b>IMO:</b>9525924</li>
                <li class="longtd"><b>MMSI:</b>447171000</li>
                <li class="longtd"><b>船舶类型：</b>货船</li>
            </ul>
         *
         * $html=getHTML("http://www.website.com",10);
        preg_match("/<title>(.*)</title>/i", $html, $match);
        $title = $match[1];
         */


        //preg_match_all('/<ul class="dw-r">\s*<li class="longtd"[^>]+>(.+)<\/li>\s*<td[^>]+>(.+)<\/td>\s*<td[^>]+>(.+)<\/td>\s*<td[^>]+>(.+)<\/td>\s*<td[^>]+>(.+)<\/td>\s*<\/tr>/sU',$s,$m,PREG_SET_ORDER);
//        print_r($m);
//        curl_close($ch);
    }

    public function init(){
        $http = curl_init();
        $param ="?method=datetTime&_=".time();
        curl_setopt($http, CURLOPT_URL, 'http://www.ip.cn/index.php?ip=111.199.157.170');
        curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($http,CURLOPT_HEADER,0);
        $rep = curl_exec( $http );
        $matchs = array();
        // (?i)<a([^>]+)>(.+?)</a>
        preg_match('(?i)<div([^>]+)>(.+?)</div>',$rep,$matchs);
        preg_match_all('%i<div([^>]+)class=\"well\">(.*?)</div>%i',$rep,$matchs);
        $doc  = strip_tags( $rep,array("p"));
        $error  = curl_errno($http);
        curl_close($http);
        var_dump( $matchs );

    }
}