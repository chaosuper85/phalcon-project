<?php   namespace Services\Service;
//auth:haibo

use Phalcon\Config;
use Phalcon\Mvc\User\Component;

class JiZhanYunService extends Component
{

    private  $apiUrl;
    const  curl_param = '56xdd.com';

    public  function __construct(  )
    {

        $cfg = $this->common_config->cell_track;

        $url = $cfg['url'].'?';
        foreach($cfg as $k=>$v) {
            $url.= $k.'='.$v.'&';
        }
        $this->apiUrl = $url;

    }


    /**
     * 功能: GPS 定位失效的时候用基站定位
     * @param $mnc 0/1 移动还是联通好
     * @param $cell 基站号
     * @param $lac 小区号
     * @return array
     * eg: $this->JiZhanYunService->getPos(0,59051,20857);
     */
    public  function getPos($mnc,$cell,$lac)
    {
        $url = $this->apiUrl."&mnc=$mnc&lac=$lac&cell=$cell";

        $json = $this->curl_file_get_contents($url);

        $ret = array();
        $data = json_decode($json);
        if ($data->code == '001') {
            $ret['error_code'] = 0;
            $ret['data'] = $data->data[0];
        } else {
            $ret['error_code'] = $data->code;
            $ret['error_msg'] = $data->about;
        }

        return $ret;
    }

    private  function curl_file_get_contents($durl){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, self::curl_param);
        curl_setopt($ch, CURLOPT_REFERER, self::curl_param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }


}
