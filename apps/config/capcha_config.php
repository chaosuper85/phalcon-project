<?php
/**
 *  图片验证码的配置
 * Created by PhpStorm.
 * User: wanghui
 * Date: 15/7/22
 * Time: 上午10:08
 */

 return  new Phalcon\Config( array(

         'default' => array(
             'length'   => 100, // 图片的长度
             'width'    => 44,// 宽度
             'fontSize' => 34,//字体大小
             'bgcolor'  => 'rgb(255,255,255)',//背景颜色
             'angle'    => 5,
             'lines'    => 5,//干扰线

         ),
         'xdd' => array(
             'length'   => 109, // 图片的长度
             'width'    => 33,// 宽度
             'fontSize' => 34,//字体大小
             'bgcolor'  => 'rgb(225,225,225)',//背景颜色
             'angle'    => 5,
             'lines'    => 4,//干扰线

         ),
         'xdd_3' => array(
             'length'   => 109, // 图片的长度
             'width'    => 33,// 宽度
             'fontSize' => 34,//字体大小
             'bgcolor'  => 'rgb(250,240,230)',//背景颜色
             'angle'    => 10,
             'lines'    => 4,//干扰线
         ),


     )

 );