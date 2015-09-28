<?php
namespace Services\Service;

//use Modules\Models\Repositories\Repositories;
use \Endroid\QrCode\QrCode;
use Phalcon\Config;

use \Phalcon\DiInterface;

use Phalcon\Mvc\User\Component;

class QrCodeService extends Component
{

    private  $qr_code;

    public  function __construct(  )
    {

        $config = $this->constant->qr_code;

        $this->qr_code = new QrCode();

        if(empty($config)) {
            $this->qr_code->setText('null code')
                ->setSize(200)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel('')
                ->setLabelFontSize(16);
        }else {
            $this->qr_code->setText($config->content)
                ->setSize($config->size)
                ->setPadding($config->padding)
                ->setErrorCorrection($config->errorCorrection)
                ->setForegroundColor($config->foreground_color)
                ->setBackgroundColor($config->bk_color)
                ->setLabel($config->label)
                ->setLabelFontSize($config->label_fontsize)
                ->setImageType($config->img_type);
        }

    }

    public  function toDate($content='')
    {
        if( !empty($content)) {
            $this->qr_code->setText($content);
        }

        $this->qr_code->render();

        return $this->qr_code->get();
    }

    public  function toUri($content='')
    {
        if( !empty($content)) {
            $this->qr_code->setText($content);
        }

        $this->qr_code->render('qrCodeFile_'.date('Y-m-d H:m:s'));

        return $this->qr_code->getDataUri();
    }

}
