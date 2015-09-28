<?php

/*
 * 下载订单的所有文件
 */


namespace Services\LogicService;


use Library\Helper\ArrayHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Exception;


class OrderDownLoadAllService extends Component
{

    public function downLoadAll( $orderId )
    {


        /*
         * 1，下载提箱单，
         * 2，下载产装联系单，
         * 3，两个文件打包成zip文件，
         * 4，下载zip文件，
         */
        try{
            $orderFreight = $this->OrderFreightService->getByOrderId( $orderId );
            if( empty( $orderFreight ) ){
                return false;
            }else{
                $tixiangdan_file_url = $orderFreight->tixiangdan_file_url;
                $addresscontact_file_urls = $orderFreight->addresscontact_file_urls;
                $parentDir  = $this->config->application->tempDir."/";
                $tempDir = $parentDir.rand(1000,10000).time();
                $temZip  = $parentDir.rand(1000,10000).time().".zip";
                if( mkdir( $tempDir ) ){
                    $extension1 = StringHelper::getExtension( $addresscontact_file_urls);
                    $temName1   = "产装联系单.".$extension1;
                    $proContactFIle = $this->downLoadFile( $addresscontact_file_urls,$tempDir, $temName1);

                    $extension2 = StringHelper::getExtension( $tixiangdan_file_url );
                    $temName2 = "提箱单.".$extension2;
                    $tixiangFIle = $this->downLoadFile( $tixiangdan_file_url,$tempDir, $temName2);
                    $res  = shell_exec(" cd $tempDir && zip  $temZip $temName1 $temName2 ");
                    if( empty( $proContactFIle ) || empty( $tixiangFIle )  ){
                        Logger::warn(" download file from qiniu failure:procontact:{%s},tixiangFIle:{%s}. shell execute result:{%s}",$proContactFIle,$tixiangFIle,$res);
                    }
                    shell_exec("rm -rf ".$tempDir);
                    unlink( $temName2 );
                    unlink( $temName1 );
                }
            }
        }catch (\Exception $e){
            Logger::warn("download file errormsg:{%s}",$e->getMessage());
        }
        return $temZip;
    }



    private  function  downLoadFile( $url ,$tempDir,$fileName){

        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        file_put_contents( $tempDir."/".$fileName , curl_exec($ch));
        curl_close($ch);
        if( file_exists( $fileName )){
            return $fileName;
        }else{
            Logger::warn(" download file:{%s} from qiniu failed.",$url);
            return false;
        }
    }
}