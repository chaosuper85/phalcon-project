<?php
namespace Services\DataService;

use Library\Log\Logger;
use Library\Helper\IdGenerator;
use TbFunction;
use GroupFunction;
use \Phalcon\DiInterface;

use Phalcon\Mvc\User\Component;

/**
 *  每个组拥有的访问权限控制
 */
class FunctionService extends  Component
{

    public function  create($fName,$fUrl,$desc,$type)
    {
        $function = new TbFunction();
        $function->function_code    =  IdGenerator::guid();
        $function->function_name    =  $fName;
        $function->function_url     =  $fUrl;
        $function->description      =  $desc;
        $function->enterprise_type  =  $type;
        $function->updated_at       =  date('Y-m-d h:i:s',time());
        $function->created_at       =  date('Y-m-d h:i:s',time());
        $res                        =  $function->save();
        Logger::info("create FUnction message:".var_export($function->getMessages(),true));
        return  $res?  $function:false;
    }

    public function  updateById($id, $params =array())
    {
        $function = TbFunction::findFirst(" id ='$id' ");
        if( empty($function) || empty($params) )
            return false;
        $params['updated_at']  = date('Y-m-d h:i:s',time());
        $res = $function->update($params);
        Logger::info(" update Function msg:".var_export($function->getMessages(),true));
        return $res ? $function : false;
    }

}