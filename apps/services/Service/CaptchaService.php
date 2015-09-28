<?php
/**
 * Created by PhpStorm.
 * User: wanghui  MODIFY:haibo
 * Date: 15/7/21
 * LAST: 15/7/24
 * Time: 上午11:44
 */

namespace Services\Service;

use \Imagick;
use \ImagickPixel;
use \ImagickDraw;
use Phalcon\Session;

use Phalcon\Mvc\User\Component;

use \Phalcon\DiInterface;

class CaptchaService extends Component
{

    protected $imagick;
    protected $content;// 默认大写
    protected $length;
    protected $width;
    protected $fontSize;
    protected $angle;
    protected $lines;
    protected $bgcolor = "rgb(255,255,225)";
    protected $characters = '123467890abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ';
    protected $config = 'default';

    public function __construct()
    {
        $this->imagick = new Imagick();

    }

    /**
     *  加载配置文件
     */
    public function  init()
    {
        $capcha_config  = $this->capcha_config;
        $this->width    = $capcha_config[$this->config]->width;
        $this->length   = $capcha_config[$this->config]->length;
        $this->fontSize = $capcha_config[$this->config]->fontSize;
        $this->bgcolor  = $capcha_config[$this->config]->bgcolor;
        $this->angle    = $capcha_config[$this->config]->angle;
        $this->lines    = $capcha_config[$this->config]->lines;
    }


    public function create($config ='default')
    {
        $this->config = $config;
        $this->init();
        /* Create the ImagickPixel object (used to set the background color on image) */
        $bg = new ImagickPixel();
        $bg->setColor($this->bgcolor);
        $draw = new ImagickDraw();
        $draw->setFontSize($this->fontSize);
        $this->imagick->newImage($this->length, $this->width, $bg);

        /* Write the text on the image */

        $this->generateNum();
        $this->text($draw);

        /* Add some swirl */
        $this->imagick->swirlImage(20);

        /* Create a few random lines */
        for ($i = 0; $i < $this->lines; $i++) {
            $draw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));
        }
        /* Draw the ImagickDraw object contents to the image. */
        $this->imagick->drawImage($draw);

        /* Give the image a format */
        $this->imagick->setImageFormat('png');
        $this->contrast($this->imagick);
        $this->session->set('captcha', strtoupper($this->content));
        return $this->imagick->getImageBlob();
    }

    function generateNum($length = 4)
    {
        $possibleChars = $this->characters;
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = rand(0, strlen($possibleChars) - 1);
            $password .= substr($possibleChars, $rand, 1);
        }
        $this->content = $password;

        //临时增加 todo more test  by:haibo 15-9-16
        $this->session->set('captcha', strtoupper($this->content));
    }

    /*
 函数说明：对比度处理
 函数参数：
  $type:表示增加或减少对比度,逻辑型,true:增加; false:减少
  $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
  $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
*/
    function contrast(Imagick $imagick, $type = true, $apply = false, $w = 0, $h = 0, $x = 0, $y = 0)
    {
        if ($type)
            $s = 9;
        else
            $s = 0;
        if ($apply) {
            $region = $imagick->getImageRegion($w, $h, $x, $y);
            $region->contrastImage($s);
            $imagick->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        } else
            $imagick->contrastImage($s);
        return $imagick;
    }


    /**
     * @return int
     */
    protected function angle()
    {
        return rand((-1 * $this->angle), $this->angle);
    }


    protected function text(ImagickDraw $draw)
    {
        $marginTop = 31;
        $i = 0;
        foreach (str_split($this->content) as $char) {
            $marginLeft = ($i * $this->width / $this->length) + 10;
            $draw->setFillColor(new ImagickPixel($this->getRandomColor($i)));
            $this->imagick->annotateImage($draw, $marginLeft + 20 * $i, $marginTop, $this->angle(), $char);
            $i++;
        }
    }


    public function  getRandomColor($i)
    {
        return 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')';
    }


    
    //检验验证码
    public function  checkCapcha($code)
    {
        if ($this->session->get('isVerify')) {
            if (empty($code) || strtoupper($code) != $this->session->get('captcha')) {
                return false;
            }
        }
        return true;
    }

    //失败次数统计，大于额定次数生成验证码
    public function  updateVerify($isLogin)
    {
        $session = $this->session;
        if ($isLogin) {
            //取消验证码
            $session->remove('isVerify');
        }
        else {
            //失败次数统计
            if ($session->has('verify')) {
                $session->set('verify', $session->get('verify') + 1);
            } else {
                $session->set('verify', 1);
            }
            //增加验证码
            if ($session->get('verify') >= $this->admin_cfg->VERIFY->COUNT) {
                $session->set('verify', 0);
                $session->set('isVerify', true);
            }
            //每次登录错误要刷新验证码
            if ($session->get('isVerify')) {
                $this->generateNum();
            }

        }

    }

    public function  validateCapcha($code)
    {
        if ( empty($code) || strtoupper($code) != $this->session->get('captcha')) {
            return false;
        }
        return true;
    }


}