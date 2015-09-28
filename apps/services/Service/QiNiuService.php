<?php

namespace Services\Service;


use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

class QiNiuService extends Component
{



    public  function uploadFileToQiNiu( $file ,$user_id )
    {
        $picUrl = '';

        $tempDir = $this->config->application->tempDir;

        $newFileName = time().'-'.$user_id;
        $extension   = $file->getExtension();
        $newFilePath =  $tempDir."/".$newFileName.".".$extension;
        $file->moveTo( $newFilePath );
        Logger::info("uploadFileToQiNiu: fileName:{%s}",$newFilePath );
//        if( strcasecmp( $extension,'pdf') == 0 ){
//            Logger::info("pdf:{%s}",$newFilePath);
//            $newFilePath = \PdfHelper::pdfToImage( $newFilePath, $tempDir );
//        }


        $accessKey = $this->common_config->qiniu->accessKey;
        $secretKey = $this->common_config->qiniu->secretKey;

        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'publicpic';


        if( \Library\Helper\ArrayHelper::inArray($extension, array('doc','docx')) ){
            $mimeType = 'application/msword';
        }else if( strcasecmp("pdf",$extension) == 0 ){
            $mimeType = 'application/pdf';
        }else{
            $mimeType = 'application/octet-stream';
        }

        $token = $auth->uploadToken($bucket, null, 3600, array( 'saveKey'=>$newFileName ));
        $uploadMgr = New UploadManager();

        $key = $user_id."-".time()."-".rand(1000,10000).".".$extension;
        list($ret, $err) = $uploadMgr->putFile($token, $key , $newFilePath, null, $mimeType);

        if ($err !== null) {
            Logger::error('upload file to quniu fail ');
        } else {
            $picUrl = 'http://7xjljc.com2.z0.glb.qiniucdn.com/'.$ret['key'];
            Logger::info('upload file to quniu success ');
        }
        $ret = unlink($newFilePath);

        return $picUrl;
    }


    public function  deleteFile( $url ){
        $accessKey = $this->common_config->qiniu->accessKey;
        $secretKey = $this->common_config->qiniu->secretKey;

        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'publicpic';
        $resourceManager = new BucketManager( $auth  );
        $res  = $resourceManager->delete( $bucket , $url );
        if( $res === null ){
            return true;
        }else{
            return false;
        }
    }


}