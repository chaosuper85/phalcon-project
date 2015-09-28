<?php
namespace Services\Service;

use Library\Helper\StringHelper;
use Library\Helper\YuDanNoHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use OrderFreightBox;
use OrderFreight;
use Users;
use TbDriver;

/**
 *  生成 word 文档
 */
class WordDocService extends Component
{
    /**
     *  创建产装联系单word 文档
     *  文件名格式 ： 提单579622617张师傅装货联系单”
     */
    public function  createProAddresContact($boxId, &$result)
    {
        $box = OrderFreightBox::findFirst("id=$boxId");
        if (empty($box)) {
            $result['error_code'] = 404;
            $result['error_msg'] = "该产装联系单的箱子找不到。";
            return false;
        } else {
            $order = OrderFreight::findFirst("id=$box->order_freight_id");
            $carriyer = Users::findFirst("id=$order->carrier_userid");
            try {
                $path = $this->config->application->tempDir . "/4.docx";
                $document = new TemplateProcessor($path);
                $document->setValue("companyName", $carriyer->unverify_enterprisename);
                $document->setValue("yudan", $order->yundan_code);
                $document->setValue("startDock", "天津");
                $document->setValue("createDate", StringHelper::strToDate($order->create_time, "Y-m-d"));

                $ship = $this->ShipNameService->getById($order->ship_name_id);
                $shipName = empty($ship->china_name) ? $ship->eng_name : $ship->china_name;
                $document->setValue("ship", $shipName);// 船名 、航次

                $company = $this->ShippingCompanyService->getById($order->shipping_company_id);
                $document->setValue("shipCompany", empty($company->china_name) ? $company->english_name : $company->china_name);

                $document->setValue("shipTicket", $order->ship_ticket);// 航次
                $document->setValue("tidan", $order->tidan_code);

                $yard = $this->YardInfoService->getById($order->yard_id);
                $document->setValue("yard", $yard->yard_name);
                $document->setValue("proName", $order->product_name);
                $boxTypeNum = $this->constant->boxType_Enum[$box->box_size_type] . "*1";
                $document->setValue("boxTypeNum", $boxTypeNum);
                $document->setValue("boxType", $this->order_config->box_type_define[$order->product_box_type]);
                $document->setValue("weight", "");
                $document->setValue("boxCode", $box->box_code);
                $document->setValue("ensupe", $box->box_ensupe);
                $document->setValue('backup', $order->product_desc);
                // 产装信息 一个箱子（ 一份产状联系单）  一个司机   可能多地产装 多时间产装
                $assignList = $this->OrderAssignDriverService->boxAssignInfo($order->id, $boxId);
                if (!empty($assignList)) {
                    $tag = count($assignList);// 复制的 次数
                    $document->cloneBlock2('tag', $tag);
                    for ($i = 1; $i <= $tag; $i++) {
                        $assign = $assignList[$i - 1];
                        $document->setValue("n_" . $i, $tag > 1 ? $i : "");
                        $address = $this->OrderProductAddressService->getProductAddressById($assign->order_product_addressid);
                        $date = $this->OrderProductTimeService->getProductTimeById($assign->order_product_timeid);
                        $document->setValue("proDate_" . $i, StringHelper::strToDate($date->product_supply_time, "Y-m-d H:i"));
                        $detailAdds = $this->CityService->getFullNameById($address->address_townid) . $address->address_detail;
                        $document->setValue("proAddress_" . $i, $detailAdds);
                        $document->setValue("conName_" . $i, $address->contact_name);
                        $document->setValue("conMobile_" . $i, $address->contact_number);
                    }
                    // 同一个司机
                    $assign = $assignList[0];
                    $user = Users::findFirst("id=$assign->driver_user_id");
                    $driver = TbDriver::findFirst("userid=$assign->driver_user_id");
                    $driverName = empty($driver->driver_name) ? $user->real_name : $driver->driver_name;
                    $document->setValue("driver", $driverName);
                    $document->setValue("driverMobile", empty($user->mobile) ? $user->contactNumber : $user->mobile);
                    $document->setValue("carNo", $driver->car_number);
                    $name = "提单" . $order->tidan_code . $driverName;
                    $result['fileName'] = $name;
                } else {
                    foreach ($document->getVariables() as $var) {
                        $document->setValue($var, "");
                    }
                }
                $randomCode = new YuDanNoHelper(12);
                $fileName = $this->config->application->tempDir . "/" . $randomCode->nextId() . rand(100000, 999999) . ".doc";
                $document->saveAs($fileName);
                return $fileName;
            } catch (\Exception $e) {
                Logger::warn("createProAddresContact Word error msg:{%s}", $e->getMessage());
                $result['error_code'] = 2000001;
                $result['error_msg'] = "系统内部错误.";
                return false;
            }
        }
    }

    /**
     *  生成箱号铅封号word 文档
     */
    public function  makeCodeAndSealWord($orderid, &$result = array())
    {
        if (empty($orderid)) {
            $result['error_code'] = 200001;
            $result['error_msg'] = "您查找的订单找不到。";
            return false;
        } else {
            try {
                $order = OrderFreight::findFirst("id=$orderid");
                if ( empty($order) ) {
                    $result['error_code'] = 200002;
                    $result['error_msg'] = "您查找的订单找不到。";
                    return false;
                } else {
                    $path = $this->config->application->tempDir . "/提箱单模板0922.doc";
                    $document = new TemplateProcessor($path);
                    $carriyer = Users::findFirst("id=$order->carrier_userid");
                    $agent = Users::findFirst("id=$order->freightagent_user");
                    $document->setValue("company", $carriyer->unverify_enterprisename);

                    $ship = $this->ShipNameService->getById($order->ship_name_id);
                    $shipName = empty($ship->china_name) ? $ship->eng_name : $ship->china_name;
                    $document->setValue("shipName", $shipName);// 船名 、航次
                    $document->setValue('startDate', "");
                    $document->setValue("tixiang", $order->yundan_code);
                    $document->setValue('agent', $agent->unverify_enterprisename);
                    $document->setValue('carTeam', $carriyer->unverify_enterprisename);
                    $document->setValue('tidan', $order->tidan_code);
                    $document->setValue('ticket', $order->ship_ticket);
                    $boxList = OrderFreightBox::find("order_freight_id=$orderid");
                    if (!empty($boxList) && count($boxList) > 0) {
                        $times = count($boxList);
                        $document->cloneRow('row', $times);

                        for ($i = 1; $i <= $times; $i++) {
                            $box = $boxList[$i - 1];
                            $boxType = $this->order_config->box_type_define[$order->product_box_type];
                            $typeName = $boxType . $this->constant->boxType_Enum[$box->box_size_type];
                            $document->setValue('row#' . $i, $typeName);
                            $document->setValue('code#' . $i, $box->box_code);
                            $document->setValue('seal#' . $i, $box->box_ensupe);
                        }
                    } else {
                        foreach ($document->getVariables() as $var) {
                            $document->setValue($var, "");
                        }
                    }
                    $randomCode = new YuDanNoHelper(12);
                    $fileName = $this->config->application->tempDir . "/" . $randomCode->nextId() . rand(100000, 999999) . ".doc";
                    $document->saveAs($fileName);
                    $result['fileName'] = "提单" . $order->tidan_code . "箱号、铅封号.doc";
                    return $fileName;
                }
            } catch (\Exception $e) {
                Logger::warn("createProAddresContact Word error msg:{%s}", $e->getMessage());
                $result['error_code'] = 2000001;
                $result['error_msg'] = "系统内部错误.";
                return false;
            }
        }
    }


    /**
     *  订单的 全部产装联系单 下载 zip
     *  一个 箱子 一个产装联系单
     *  文件名 "提单".$order->tidan_code.$driverName.箱子-$boxid-产装联系单 ;
     */
    public function  zipOrderProContacts($orderId, &$result = array())
    {
        $randomCode = new YuDanNoHelper(12);
        $path = $this->config->application->tempDir . "/";
        $zipName = $randomCode->nextId() . rand(1000, 9999) . ".zip";
        try {
            $assignList = $this->OrderAssignDriverService->listByOrderId($orderId);
            $order = $this->OrderFreightService->getByOrderId($orderId);
            if (empty($order) || empty($assignList)) {
                $result['error_code'] = 2000001;
                $result['error_msg'] = "您的订单尚未分派司机，请先分派司机后才能下载";
            } else {
                $filterSameBox = []; // 过滤同一个箱子 有多条分派任务
                $zipFileName = "提单" . $order->tidan_code . "-产装联系单.zip";
                foreach ($assignList as $assign) {
                    if (in_array($assign->order_freight_boxid, $filterSameBox)) {
                        continue;
                    } else {
                        $filterSameBox[] = $assign->order_freight_boxid;
                        $fileName = $this->createProAddresContact($assign->order_freight_boxid, $result);
                        if (!empty($fileName)) {
                            $newName = $result['fileName'] . "-箱子-" . $assign->order_freight_boxid . "-装货联系单.doc";
                            $res = rename($fileName, $path . $newName);
                            if ($res) {
                                shell_exec("cd $path && zip $zipName $newName");
                                unlink($path . $newName);
                            } else {
                                Logger::warn(" rename file:{%s} failed");
                                $success = false;
                            }
                        } else {
                            $success = false;
                            Logger::warn("zipOrderProContacts:order:{%s} box:{%s} result:{%s}", $orderId, $assign->order_freight_boxid, var_export($result, true));
                        }
                    }
                }
                $result['fileName'] = $zipFileName;
                $success = true;
            }
        } catch (\Exception $e) {
            Logger::warn('zipOrderProContacts orderId:{%s} error msg:{%s}', $orderId, $e->getMessage());
        }
        if ($success && file_exists($path . $zipName)) {
            return $path . $zipName;
        } else {
            $result['error_code'] = 2000002;
            $result['error_msg'] = "网络错误，请重新下载";
            return false;
        }
    }
}