<?php

class PdfHelper
{
    public static function  pdfToImage($pdf, $path, $page=0)
    {
        if(!extension_loaded('imagick'))
        {
            return false;
        }

        if(!file_exists($pdf))
        {
            return false;
        }

        $im = new Imagick();
        $im->setResolution(120,120);
        $im->setCompressionQuality(100);
        if($page==-1)
            $im->readImage($pdf);
        else
            $im->readImage($pdf."[".$page."]");
        foreach ($im as $Key => $Var)
        {
            $Var->setImageFormat('png');
            $filename = $path."/". md5($Key.time()).'.png';
            if($Var->writeImage($filename) == true)
            {
                $Return= $filename;
            }
        }
        Logger::info('pdfToImage success:'.$Return);
        return $Return;
    }

    /**
     *  需要加水印的文件绝对路径 生成的图片替换 旧图片
     */
    public static function addWatermarkPic( $filePath )
    {
        $imagick = new Imagick();
        $watermarkPath=$_SERVER['DOCUMENT_ROOT'].'/images/watermark.png';
        if( !file_exists($filePath) || !file_exists($watermarkPath)){
            return false ;
        }
        $imagick->readImage($filePath);
        $height=$imagick->getImageHeight();
        $width=$imagick->getImageWidth();

        $watermark = new Imagick($watermarkPath);
        $draw = new ImagickDraw();
        $draw->composite($watermark->getImageCompose(),$width-$watermark->getImageWidth(),$height-$watermark->getImageHeight(), $watermark->getImageWidth(), $watermark->getimageheight(), $watermark);
        $imagick->drawImage($draw);
        $imagick->setImageFormat( 'png' );
        $imagick->writeImages($filePath, false);
        Logger::info('addWatermarkPic success:'.$filePath);
        return true;
    }

}