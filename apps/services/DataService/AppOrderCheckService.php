<?php
/**
 * 获取天津港数据，验证订单状态
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/30
 * Time: 上午10:53
 */
namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Sunra\PhpSimple\HtmlDomParser;
use Library\Log\Logger;

class AppOrderCheckService extends Component {
    # 请求url
    const REQUEST_URL = 'http://www.tjgportnet.com/sc/containerdelivery.aspx';

    # 请求referer url
    const REQUEST_REFERER_URL = 'http://www.tjgportnet.com/';

    # cookie存储目录
    const COOKIE_PATH = '/tmp';

    # cookie文件前缀
    const COOKIE_PREFIX = 'JSESSIONID_';

    # 日志文件名
    const LOG_FILENAME = 'orderCheck';

    # 浏览器useragent
    private $_useragents = array(
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36',
        'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
        'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
        'Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11',
        'Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)'
    );

    /**
     * 获取请求结果
     */
    public function getResult($tidan_code) {
        $data = array();
        $res = array();
        $tidan_code_list = $this->getTidanCodeList($tidan_code);

        # 发送post数据，并解析页面
        foreach ($tidan_code_list as $key => $value) {
            $is_ok = $this->getCookieInfo($res);
            $formguid = $this->getFormguid($res['content']);

            if ($is_ok && $formguid) {
                $re_content = $this->getDataByPostFields($value, $formguid, $res['cookie_jar']);
                $data = $this->getBoxInfo($value, $re_content);
                if ($data) {
                    # 删除cookie_jar文件
                    unlink($res['cookie_jar']);
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * 自动生成提单号list
     */
    public function getTidanCodeList($init_code) {
        $tidan_code_list[] = $init_code;

        $last_char = strtoupper(substr($init_code, -1));
        $sub_str = substr($init_code, 0, -1);
        switch ($last_char) {
            case 'A' :
                $tidan_code_list[] = $sub_str . 'B';
                break;
            case 'B' :
                $tidan_code_list[] = $sub_str . 'A';
                break;
            default :
                $tidan_code_list[] = $sub_str . 'A';
                $tidan_code_list[] = $sub_str . 'B';
                break;
        }

        return $tidan_code_list;
    }


    /**
     * 第一次请求获取cookie信息
     */
    public function getCookieInfo(&$res, $cookie_path = self::COOKIE_PATH, $cookie_prefix = self::COOKIE_PREFIX) {
        $cookie_jar = tempnam($cookie_path, $cookie_prefix);

        try {
            # 创建curl
            $curl = curl_init();
            $options = array(
                CURLOPT_URL => self::REQUEST_URL,
                CURLOPT_HEADER => false,
                CURLOPT_REFERER => self::REQUEST_REFERER_URL,
                CURLOPT_USERAGENT => $this->_useragents[array_rand($this->_useragents)],
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_COOKIEJAR => $cookie_jar
            );
            curl_setopt_array($curl, $options);
            $content = curl_exec($curl);
            curl_close($curl);

            if (empty($content)) {
                Logger::info(
                    $this->constant->LOG_SEPORATER . 'First Request: Content is empty',
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );
                return false;
            }

            $res['cookie_jar'] = $cookie_jar;
            $res['content'] = $content;

            return true;

        } catch (\Exception $e) {
            Logger::warn(
                $this->constant->LOG_SEPORATER . $e->getCode() . $this->constant->LOG_SEPORATER . $e->getMessage(),
                $this->constant->LNS,
                self::LOG_FILENAME
            );
            return  false;
        }
    }

    /**
     * 解析内容，获取formguid
     */
    public function getFormguid($content) {
        $formguid = '';

        try {
            $html = HtmlDomParser::str_get_html($content);
            $ret = $html->find("#formguid", 0);
            if(empty($ret) || empty($ret->value)) {
                Logger::info(
                    $this->constant->LOG_SEPORATER . 'First Request: Formguid is empty',
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );
            }
            else {
                $formguid = $ret->value;
            }
            $html->clear();

        } catch(\Exception $e) {
            Logger::warn(
                $this->constant->LOG_SEPORATER . $e->getCode() . $this->constant->LOG_SEPORATER . $e->getMessage(),
                $this->constant->LNS,
                self::LOG_FILENAME
            );
        }

        return $formguid;
    }

    /**
     * 第二次请求，发送cookie，获取内容
     */
    public function getDataByPostFields($tidan_code, $formguid, $cookie_jar) {
        $re_content = '';

        try{
            $fields = array(
                'formname' => 'wssearchform',
                'formproc' => 1,
                'dpboxchange' => 0,
                'formguid' => $formguid,
                'blno' => $tidan_code,
                'CtnNo' => ''
            );

            $ch = curl_init();
            $opts = array(
                CURLOPT_URL => self::REQUEST_URL,
                CURLOPT_HEADER => false,
                CURLOPT_REFERER => self::REQUEST_URL,
                CURLOPT_USERAGENT => $this->_useragents[array_rand($this->_useragents)],
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_COOKIEFILE => $cookie_jar,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $fields
            );
            curl_setopt_array($ch, $opts);
            $re_content = curl_exec($ch);
            curl_close($ch);

            if(empty($re_content)) {
                Logger::info(
                    $this->constant->LOG_SEPORATER . 'Second Request: Tidan_Code is '.$tidan_code.' ;Content is empty',
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );
            }

        } catch (\Exception $e) {
            Logger::warn(
                $this->constant->LOG_SEPORATER . $e->getCode() . $this->constant->LOG_SEPORATER . $e->getMessage(),
                $this->constant->LNS,
                self::LOG_FILENAME
            );
        }

        return $re_content;
    }

    /**
     * 解析内容获取箱子信息
     */
    public function getBoxInfo($code, $re_content) {
        $data = array();

        try {
            # 解析post请求返回页面，解析
            $html = str_get_html($re_content);
            $obj_table = $html->find(".result_wrap table", 1);
            if (!$obj_table) {
                Logger::info(
                    $this->constant->LOG_SEPORATER . 'Second Request: Tidan_Code is '.$code.' ;Box information is empty',
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );
            }
            else {
                $res = $obj_table->find('tr');
                foreach($res as $k => $v) {
                    if ($k > 0) {
                        $tmp = array($v->find('td', 0)->plaintext, $v->find('td', 1)->plaintext);
                        $data[] = $tmp;
                    }
                }
            }
            $html->clear();

        } catch(\Exception $e) {
            Logger::warn(
                $this->constant->LOG_SEPORATER . $e->getCode() . $this->constant->LOG_SEPORATER . $e->getMessage(),
                $this->constant->LNS,
                self::LOG_FILENAME
            );
        }

        return $data;
    }
}