<?php  namespace Services\DataService;

use Library\Helper\QueryHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use ShipName;
use Overtrue\Pinyin\Pinyin;


/**
 *  船名
 */
class ShipNameService extends Component
{
    const ERR_SHIP_ADD1 = '船名等信息重复，请修改后重新提交';
    const ERR_SHIP_ALTER1 = '船名等信息重复，请修改后重新提交';

    /**
     * @param $id
     * @return ShipName
     */
    public function getById( $id){
        $shipName = ShipName::findFirst(array(
            "conditions" =>"id =?1",
            "bind" =>[ 1 => $id ]
        ));
        return $shipName;
    }

    /** 通过 中文名 搜索
     *  获取某个船公司 的 所有船名的 列表
     */
    public function searchByChinaName( $name, $companyId = null ,$limit =10 ){
        if( empty($companyId) ){
            $sql = " select id as ship_name_id,china_name,eng_name from ship_name where  china_name like CONCAT('%','$name','%') limit $limit";
        }else{
            $sql = " select id as ship_name_id,china_name,eng_name from ship_name where shipping_companyid =$companyId and china_name like CONCAT('%','$name','%')";
        }
        $result = $this->db->fetchAll($sql);
        return $result;
    }

    /**
     *  通过 英文名 或者 代码 搜索
     */
    public function searchShipName( $keyWord, $companyId =null , $limit = 10 ){
        $likeStr = '%'.$keyWord.'%';
        $params = [ $likeStr,$likeStr,$likeStr];
        if( empty( $companyId) ){
            $sql = "select id as ship_name_id,china_name,eng_name from ship_name where ( china_name LIKE ? OR eng_name like ?  OR pinyin LIKE ? ) limit $limit";
        }else{
            $sql = "select id as ship_name_id,china_name,eng_name from ship_name where ( china_name LIKE ? OR eng_name like ?  OR pinyin LIKE ? ) AND shipping_companyid =? limit $limit";
            $params[] = $companyId;
        }
        $result = $this->db->fetchAll($sql,2,$params);
        return $result;
    }

    /**
     * haibo
     * 功能: 获取所有船信息,支持筛选，分页
     * @return mixed
     */
    public function ships($name = false)
    {
        if( $name) {
            if( StringHelper::isHaveHanzi($name)) {
                $_REQUEST['china_name'] = '~%'.$name.'%';
            }else {
                $_REQUEST['eng_name'] = '~%'.$name.'%';
            }
        }

        $param['order'] = 'create_time DESC,id ASC';
        $data = QueryHelper::cond('\ShipName', $this->request, $param);

        Logger::info('ships sum:'.$data['data_sum']);
        return $data;
    }

    /**
     * haibo
     * 功能:修改船信息
     * 备注:
     * @param $id
     * @param $name_zh
     * @param $name_en
     * @param $mobile
     * @param $contact_address
     * @param $contact_name
     * @param $code
     * @return \Phalcon\Mvc\Model\MessageInterface[]|string
     */
    public function alterShip($id, $name_zh, $name_en, $mobile, $contact_address, $contact_name, $code)
    {
        $ship = \ShipName::findFirst( intval($id));
        if( !$ship) {
            Logger::info('modifyShip 无此船id');
            return self::ERR_SHIP_ALTER1;
        }

        $name_en && $ship->eng_name = $name_en;
        $name_zh && $ship->china_name = $name_zh;
        $code && $ship->shipname_code = $code;
        $mobile && $ship->phone_mobile = $mobile;
        $contact_name && $ship->contact_name = $contact_name;
        $contact_address && $ship->com_address = $contact_address;
        $ship->update();

        Logger::info('alterShip info:'.var_export($ship->getMessages(),true));

        return isset($ship->getMessages()[0]) ? $ship->getMessages()[0]:'';
    }

    /**
     * haibo
     * 功能:增加一个船
     * 备注:后台添加船信息
     * @param $name_zh
     * @param $name_en
     * @param $mobile
     * @param $com_address
     * @param $contact_name
     * @param $code
     * @return \Phalcon\Mvc\Model\MessageInterface[]
     */
    public function addShip( $name_zh, $name_en, $mobile, $com_address, $contact_name, $code)
    {
        //重复判定
        $ship = \ShipName::findFirst( ["china_name = ?1",'bind'=>[1=>$name_zh]]);
        if( $ship) {
            Logger::info('addship - 信息重复');
            return self::ERR_SHIP_ADD1;
        }

        $ship = new \ShipName();
        $ship->eng_name = $name_en;
        $ship->china_name = $name_zh;
        $ship->shipname_code = $code;
        $ship->contact_name = $contact_name;
        $ship->com_address = $com_address;
        $ship->phone_mobile = $mobile;
        $ship->save();

        Logger::info('addship info:'.var_export($ship->getMessages(),true));

        return isset($ship->getMessages()[0]) ? $ship->getMessages()[0]:'';
    }


    /**
     *  查询 船名
     */
    public function  createIfNullByName( $shipName,$type =2 ){
        if( !StringHelper::isHaveHanzi( $shipName ) ){ // 英文名
            $ship = ShipName::findFirst(array(
                "conditions" =>"eng_name =?1",
                "bind" =>[ 1 => $shipName ]
            ));
        }else{ // 中文名
            $ship = ShipName::findFirst(array(
                "conditions" =>"china_name =?1",
                "bind" =>[ 1 => trim($shipName) ]
            ));
        }
        if( empty( $ship ) ){
            $ship = $this->create( $shipName, $type);
        }
        return $ship;

    }

    public function  create( $shipName , $type = 2 ){
        $ship = new ShipName();
        if( StringHelper::isHaveHanzi( $shipName ) ){
            $ship->china_name = $shipName;
            $setting = [
                'delimiter' => '',
                'accent'    => false,
            ];
            $ship->pinyin = Pinyin::trans( $shipName ,$setting );
            $ship->eng_name   = "";
        }else{
            $ship->eng_name   = $shipName;
            $ship->china_name = "";
        }
        $ship->shipname_code = "";
        $ship->shipping_companyid = 0;
        $ship->type = $type;// 待审核
        $res  = $ship->create();
        Logger::info("ship create error msg:".var_export( $ship->getMessages(), true ));
        return $ship;
    }

}
