<?php
namespace Services\LogicService;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Exception;
use YardInfo;
use ShipName;
use ShippingCompany;
use Users;

/**
 *  ApiCarTeamOrderController LoginService
 */
class ApiCarTeamOrderService extends Component
{
    /**
     *  车队确认接单，填写必要信息 船务信息 \ 货物信息  \ 产装地址可选
     */
    public function confirmOrder($reqArr, $userId, &$result = array())
    {
        $orderId = $reqArr['orderid'];
        $order = $this->OrderFreightService->getByOrderId($orderId);
        if (empty($order)) {
            $result['error_code'] = 200001;
            $result['error_msg'] = "找不到该订单。订单Id:" . $orderId;
            return 1;
        } else {
            if ($order->carrier_userid != $userId) { // 不是承运人
                $result['error_code'] = 2000002;//
                $result['error_msg'] = "您无权承接此订单。";
                return 2;
            } else if ($order->order_status >= $this->order_config->order_status_enum->TO_ASSIGN) { // 该订单已经完善；
                $result['error_code'] = 2000003;
                $result['error_msg'] = "此订单已经确认接单，不能重复接单。";
                return 3;
            } else {
                //货物信息
                $productInfo = $reqArr['product_info'];
                $boxTypeNumArr = $productInfo['box_type_number'];
                $boxWeight = $productInfo['product_weight']; //货物中总量
                $units_20GP = 0; // 单位 20GP,总的单位数
                $boxNumbers = 0; // 订单所有的 箱子数目
                foreach ($boxTypeNumArr as $boxTypeNum) { // 箱型箱量
                    $boxType = $boxTypeNum['type'];
                    $boxNum = $boxTypeNum['number'];
                    $boxNumbers += $boxNum;
                    switch ($boxType) {
                        case "20GP":
                            $order->box_20gp_count = $boxNum;
                            $units_20GP += $boxNum;
                            break;
                        case "40GP":
                            $order->box_40gp_count = $boxNum;
                            $units_20GP += $boxNum * 2;
                            break;
                        case "40HQ":
                            $order->box_40hq_count = $boxNum;
                            $units_20GP += $boxNum * 3;
                            break;
                        case '45HQ':
                            $order->box_45hq_count = $boxNum;
                            $units_20GP += $boxNum * 3.5;
                            break;
                        default:
                            break;
                    }
                }
                // 创建订单的 集装箱
                do {
                    try {
                        $this->db->begin();// 开启事物
                        $flag = $this->OrderBoxService->createBoxes($orderId, $boxTypeNumArr, $boxWeight, $units_20GP, $boxNumbers);
                        if (!$flag) {
                            Logger::warn("order:{%s} create boxs error :boxTypeAndNum:{%s}", $orderId, var_export($boxTypeNumArr, true));
                            break;
                        }
                        $order->product_box_type = $productInfo['product_box_type'];
                        $order->product_name = isset($productInfo['product_name']) ? $productInfo['product_name'] : "";
                        $order->product_desc = $productInfo['product_notice'];
                        $order->product_weight = $productInfo['product_weight'];
                        // 船务信息
                        $shipInfo = $reqArr['ship_info'];
                        $yardId = trim($shipInfo['yard_id']);
                        $shipNameId = trim($shipInfo['ship_name_id']);
                        $shipCompId = trim($shipInfo['ship_company_id']);
                        // 堆场
                        if ( !empty( $yardId ) ) {
                            $order->yard_id = $yardId;
                        } else {
                            $yard = $this->YardInfoService->createIfNullByName($shipInfo['yard_name'], 1); // 待审核
                            $order->yard_id = $yard->id;
                        }

                        // 船名
                        if ( !empty( $shipNameId ) ) {
                            $order->ship_name_id = $shipNameId;
                        } else {
                            $ship = $this->ShipNameService->createIfNullByName($shipInfo['ship_name'], 2);// 待审核
                            $order->ship_name_id = $ship->id;
                        }

                        // 船公司
                        if ( !empty( $shipCompId ) ) {
                            $order->shipping_company_id = $shipCompId;
                        } else {
                            $comany = $this->ShippingCompanyService->createIfNullByName($shipInfo['ship_company_name'], 2);//待审核
                            $order->shipping_company_id = $comany->id;
                        }

                        $order->ship_ticket = $shipInfo['ship_ticket'];// 航次
//                        $order->ship_ticket_desc = $shipInfo['ship_ticket_desc']; // 船期
                        $flag = $order->update();
                        if (!$flag) {
                            Logger::warn("order:{%s} update result:%s ", $orderId, var_export($order->getMessages(), true));
                            $this->db->rollback();
                            break;
                        }
                        $params = array("order_status" => $this->order_config->order_status_enum->TO_ASSIGN);// 货代确认接单，待配车
                        $flag = $this->OrderFreightService->agentConfirmOrder($orderId, $params);
                        if (!$flag) {
                            $this->db->rollback();
                            break;
                        }
                        if (isset($reqArr['address_info'])) {
                            $addressInfoArr = $reqArr['address_info']; // 创建产装地址,可能有多个
                            if (is_array($addressInfoArr)) {
                                foreach ($addressInfoArr as $addressInfo) {
                                    $addressDetail = $addressInfo['box_address_detail'];
                                    $address = array(
                                        'provinceid' => $addressInfo['address_provinceId'],
                                        'cityid' => $addressInfo['address_cityId'],
                                        'townid' => $addressInfo['address_townId']
                                    );
                                    $productDate = $addressInfo['box_date'];
                                    $productAddress = $this->OrderProductAddressService->create($orderId, $productDate, $address, $addressDetail, $addressInfo['contactName'], $addressInfo['contactNumber']);
                                    if (empty($productAddress)) {
                                        Logger::warn(" carTeam:{%s} confirm order:{%s} create address error ", $userId, $orderId);
                                        $flag = false;
                                        break;
                                    } else {
                                        $flag = true;
                                    }
                                }
                            } else {
                                $addressDetail = $addressInfoArr['box_address_detail'];
                                $address = array(
                                    'provinceid' => $addressInfoArr['address_provinceId'],
                                    'cityid' => $addressInfoArr['address_cityId'],
                                    'townid' => $addressInfoArr['address_townId']
                                );
                                $productDate = $addressInfoArr['box_date'];
                                $productAddress = $this->OrderProductAddressService->create($orderId, $productDate, $address, $addressDetail, $addressInfoArr['contactName'], $addressInfoArr['contactNumber']);
                                if (empty($productAddress)) {
                                    Logger::warn(" carTeam:{%s} confirm order:{%s} create address error ", $userId, $orderId);
                                    $flag = false;
                                    break;
                                } else {
                                    $flag = true;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Logger::warn("confirm order:{%s} error msg:{%s}", $orderId, $e->getMessage());
                        $flag = false;
                    }
                } while (0);
                if ( $flag ) {
                    $result['error_code'] = 0;
                    $result['error_msg'] = "确认接单成功。";
                    $this->db->commit();
                } else {
                    $result['error_code'] = 200006;
                    $result['error_msg'] = "接单失败。";
                    $this->db->rollback();
                }
                // 车队接单 日志
                $this->ActivityLogService->insertActionLog
                (
                    $this->constant->ACTION_TYPE->CARTEAM_CONFIRM_ORDER,
                    $this->request->getClientAddress(),
                    $userId,  // reamid
                    $this->constant->ACTION_REAM_TYPE->CARTEAM,     //reamType
                    $order->freightagent_user, // targetid
                    $this->constant->ACTION_REAM_TYPE->CARGOER, // target type
                    json_encode(array('result' => $result))  // json
                );
                return 4;
            }
        }
    }

    /**
     *  车队搜索 船公司名字
     */
    public function searchShipCompany($keyWord)
    {
        $result = $this->ShippingCompanyService->search( $keyWord );
        return $result;
    }


    /**
     *  完善订单 事务控制
     */
    private function  completeOrderInTransaction()
    {
        $this->db->begin();

    }


}
