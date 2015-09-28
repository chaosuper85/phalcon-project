<?php   namespace Services\DataService;

use Library\Helper\QueryHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use ShippingCompany;
use Overtrue\Pinyin\Pinyin;

/**
 * 船公司
 */
class ShippingCompanyService extends Component
{
    const ERR_COM_ADD1 = '船公司名等信息重复，请修改后重新提交';
    const ERR_COM_ALTER1 = '此船公司的信息已丢失，详细信息请查看日志';



    /**
     * @param $id
     * @return ShippingCompany
     */
    public function getById( $id ){
        $company = ShippingCompany::findFirst(array(
            "conditions" =>"id =?1",
            "bind" =>[ 1 => $id ]
        ));
        return $company;
    }


    /**
     * 通过 输入的公司   模糊匹配 所有的 船公司
     */
    public function search( $keyword, $limit =10 ){
        $likeStr = '%'.$keyword.'%';
        $keyword = [ $likeStr,$likeStr,$likeStr,$likeStr ];
        $sql = " select id as company_id,company_code,china_name,english_name  from shipping_company where (company_code like ? OR english_name like ? OR china_name LIKE ? OR pinyin LIKE  ? ) limit $limit ";
        $result = $this->db->fetchAll( $sql,2, $keyword );
        return $result;
    }


    /**
     * haibo
     * 功能: 获取所有船公司信息,支持筛选，分页
     * @return mixed
     */
    public function shipComs($name = false)
    {
        if( $name) {
            if( StringHelper::isHaveHanzi($name)) {
                $_REQUEST['china_name'] = '~%'.$name.'%';
            }else {
                $_REQUEST['english_name'] = '~%'.$name.'%';
            }
        }

        $options['order'] = 'create_time DESC';
        $data = QueryHelper::cond('\ShippingCompany',$this->request,$options);

        Logger::info('ships sum:'.$data['data_sum']);
        return $data;
    }

    /**
     * haibo
     * 功能:修改船公司
     * 备注:
     * @param $id
     * @param $name_zh
     * @param $name_en
     * @param $com_code
     * @param $mobile
     * @param $contact_address
     * @param $contact_name
     * @param bool $status
     * @return \Phalcon\Mvc\Model\MessageInterface[]|string
     */
    public function alterShipCom($id, $name_zh, $name_en, $com_code, $mobile, $contact_address, $contact_name, $status=false)
    {
        $ship_com = false;
        $id && $ship_com=\ShippingCompany::findFirst( intval($id));
        if( !$ship_com) {
            Logger::info('modifyShip 无此船公司信息');
            return  self::ERR_COM_ALTER1;
        }

        $ship_com->english_name = $name_en;
        $ship_com->china_name   = $name_zh;
        $ship_com->company_code = $com_code;
        $ship_com->phone_mobile = $mobile;
        $ship_com->contact_name = $contact_name;
        $ship_com->com_address   = $contact_address;
        $status && $ship_com->type = $status;
        $ship_com->update();

        Logger::info('modifyshipcom info:'.var_export($ship_com->getMessages(),true));

        return isset($ship_com->getMessages()[0]) ? $ship_com->getMessages()[0]:'';
    }

    /**
     * haibo
     * 功能:增加船公司
     * 备注:后端用户使用
     * @param $name_zh
     * @param $name_en
     * @param $com_type
     * @param $com_code
     * @param $mobile
     * @param $contact_address
     * @param $contact_name
     * @return \Phalcon\Mvc\Model\MessageInterface[]
     */
    public function addShipCom($name_zh, $name_en, $com_type, $com_code, $mobile, $contact_address, $contact_name)
    {
        //重复判定
        $ship_com = \ShippingCompany::findFirst( ["china_name = ?1 OR english_name = ?2 OR company_code = ?3",'bind'=>[1=>$name_zh,2=>$name_en,3=>$com_code]]);
        if( $ship_com) {
            Logger::info('addshipCom - 信息重复');
            return self::ERR_COM_ADD1;
        }

        $status = $this->order_config->CREATE_TYPE->CHECKED;

        $ship_com = new \ShippingCompany();
        $ship_com->english_name = $name_en;
        $ship_com->china_name = $name_zh;
        $ship_com->pinyin = Pinyin::trans( $name_zh, ['delimiter'=>'','accent'=>false,]);
        $ship_com->companny_type = $com_type;
        $ship_com->phone_mobile = $mobile;
        $ship_com->company_code = $com_code;
        $ship_com->com_address = $contact_address;
        $ship_com->contact_name = $contact_name;
        $ship_com->type = $status;   //审核状态， 但是字段竟然叫type，想改去找小辉
        $ship_com->save();

        Logger::info('addshipcom :'.var_export($ship_com->getMessages(),true));

        return isset($ship_com->getMessages()[0]) ? $com_code.' '.$ship_com->getMessages()[0]:'';
    }

    public function createIfNullByName( $name, $type =2 ){
        if( !StringHelper::isHaveHanzi( $name ) ){ //eng
           $condition = " english_name=?1 " ;
        }else{
            $condition = " china_name=?1 " ;
        }
        $company = ShippingCompany::findFirst(array(
            "conditions" => $condition,
            "bind" =>[ 1 => trim($name) ]
        ));
        if( empty( $company ) ){
           $company = $this->create( trim($name),$type);
        }
        return $company;
    }

    public function  create( $name, $type=2 ){
        $company = new ShippingCompany();
        if( !StringHelper::isHaveHanzi( $name) ){
            $company->english_name = $name;
            $company->china_name = "";
        }else{
            $company->china_name  = $name;
            $setting = [
                'delimiter' => '',
                'accent'    => false,
            ];
            $company->pinyin = Pinyin::trans( $name,$setting );
            $company->english_name = "";
        }
        $company->company_code = "";
        $company->companny_type = 0;
        $company->phone_mobile = "";
        $company->com_address ="";
        $company->contact_name = "";
        $company->type = $type;
        $res = $company->create();
        Logger::info("create SHippCOmpany error msg:".var_export( $res, true ));
        return $company;
    }


}
