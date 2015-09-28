<?php
/**
 * https://github.com/overtrue/pinyin
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/9/16
 * Time: 下午4:39
 */

namespace Services\Service;
use Overtrue\Pinyin\Pinyin;
use Phalcon\Mvc\User\Component;

class PinyinService extends Component {

    private $delimiter = ' ';   //分隔符默认空格
    private $accent = true;     //是否输出音调
    private $only_chinese = false;  //只保留 $string 中中文部分
    private $uppercase = false;     //取首字母时的大写
    private $charset = 'UTF-8';     //字符集，默认：UTF-8

    /**
     * 设置分隔符
     */
    public function setDelimiter($delimiter) {
        $this->delimiter = $delimiter;
    }

    /**
     * 设置是否输出音调
     */
    public function setAccent($accent) {
        $this->accent = $accent;
    }

    /**
     * 设置是否只保留中文部分
     */
    public function setOnlyChinese($only_chinese) {
        $this->only_chinese = $only_chinese;
    }

    /**
     * 设置首字母是否大写
     */
    public function setUppercase($uppercase) {
        $this->uppercase = $uppercase;
    }

    /**
     * 设置编码
     */
    public function setCharset($charset) {
        $this->charset = $charset;
    }


    /**
     * 获取拼音
     */
    public function getPinyin($content) {
        $setting = array(
            'delimiter' => $this->delimiter,
            'accent' => $this->accent,
            'only_chinese' => $this->only_chinese,
            'charset' => $this->charset
        );

        return Pinyin::trans($content, $setting);
    }

    /**
     * 获取首字母
     */
    public function getFirstLetter($content) {
        $setting = array(
            'delimiter' => $this->delimiter,
            'uppercase' => $this->uppercase,
            'only_chinese' => $this->only_chinese,
            'charset' => $this->charset
        );

        return Pinyin::letter($content, $setting);
    }

    /**
     * 两个同时获取
     */
    public function getAll($content) {
        $setting = array(
            'delimiter' => $this->delimiter,
            'accent' => $this->accent,
            'uppercase' => $this->uppercase,
            'only_chinese' => $this->only_chinese,
            'charset' => $this->charset
        );

        return Pinyin::parse($content, $setting);
    }
}