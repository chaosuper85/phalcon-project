<?php

use Library\Helper\PdfHelper;
use Library\Log\Logger;
use Phalcon\Http\Client\Exception;

use Library\Helper\ArrayHelper;

/** 账户管理
 * @RoutePrefix("/api/account")
 */
class ApiAccountController extends ApiControllerBase
{

    private $extensArr = array('BMP', 'JPG', 'PNG', 'GIF', 'JPEG', 'PDF');

    /**
     * @Route("/uploadPic", methods={"GET", "POST"})
     */
    public function uploadPicAction()
    {

        $result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array(),
        );
        $user = $this->session->get('login_user');

        try {
            $ret = $this->request->hasFiles();
            if ($ret == true) {
                $files = $this->request->getUploadedFiles();
                $file = $files[0];
                $extension = $file->getExtension();
                if (ArrayHelper::inArray($extension, $this->extensArr)) {
                    $ret = $this->QiNiuService->uploadFileToQiNiu($file, $user->id);
                    $result['data'] = array('pic_url' => $ret, 'fileType' => $extension);
                    Logger::info('upload success');
                } else {
                    $result['error_code'] = 100005;
                    $result['error_msg'] = "您上传的文件格式不正确，请重新上传。";
                }
            } else {
                $result = array(
                    'error_code' => '10001',
                    'error_msg' => '没有上传文件',
                    'data' => array(),
                );
                Logger::warn('no file upload ');
            }

        } catch (\Exception $e) {
            Logger::warn($e->getMessage());

            $result = array(
                'error_code' => '10002',
                'error_msg' => '网络异常',
                'data' => array(),
            );

        }

        Logger::info('uploadPicAction return ' . var_export($result, true));
        $this->response->setJsonContent($result)->send();
    }

    /**  个人信息更新
     * @Route("/updateBaseInfo", methods={"GET", "POST"})
     */
    public function updateBaseInfoAction()
    {

        $reqJson = $this->request->getJsonRawBody(true);
        Logger::info("update personalInfo request:{%s}", var_export($reqJson, true));
        $message = $this->AccountUpdateBValidation->validate($reqJson);
        if (count($message)) {
            $result['error_msg'] = "参数格式不正确，请检查联系方式或者座机号码。";
            $result['error_code'] = 100003;
        } else {
            $user = $this->getUser();
            try {
                $fenji = trim( $reqJson['contactMobile_fenji'] );
                $phone = $reqJson['contactMobile_city'].'-'.$reqJson['contactMobile_number'].( empty( $fenji )?"":"-".$fenji );
                $res = $this->UserService->updateById(array(
                    'real_name'         =>  $reqJson['realName'],
                    'avatarpicurl'      =>  $reqJson['avatarPicUrl'],
                    'contactNumber'     =>  $reqJson['contactMobile'], // 联系人手机
                    'telephone_number'  =>  $phone // 座机
                ), $user->id);
            } catch (Exception $e) {
                Logger::info("updateBaseInfoAction error msg:" . var_export($e->getMessage(), true));
            }

        }

        $result = array(
            'error_code' => 100001,
            'error_msg' => "参数格式错误。"
        );


        if (!$res) {
            $result['error_msg'] = '更新失败，请重试。';
            return $this->response->setJsonContent($result);
        } else {
            $result['error_code'] = 0;
            $result['error_msg'] = "更新成功";
        }

        return $this->response->setJsonContent($result);
    }

    /**
     * @Route("/updateAuthData", methods={"GET", "POST"})
     */
    public function updateAuthDataAction()
    {
        $userCache = $this->session->get('login_user');
        $result = array(
            'error_code' => 1,
            'error_msg' => " i don't think the exception will happen ."
        );

        $enterpriseName = $this->request->getPost('enterpriseName'); // 企业名称
        $idCardPic = $this->request->getPost('idCardPic'); //身份正面
        $backPic = $this->request->getPost('backPic '); // 背面
        $licensePic = $this->request->getPost('licensePic'); // 营业执照
        $teamName = $this->request->getPost('teamName'); //车队名
        $teamType = $this->request->getPost('teamType '); // 车队类型
        $teamPic = $this->request->getPost('teamPic '); // 车队图片
        $contactName = $this->request->getPost('contactName'); // 联系人
        $contactNumber = $this->request->getPost('contactNumber'); // 联系号码
        $licenseNumber = $this->request->getPost('licenseNumber'); // 营业执照号码
        $ownerName = $this->request->getPost('ownerName'); //
        $ownerIdCard = $this->request->getPost('ownerIdCard');
        try {
            $this->AccountService->updateAuthData($userCache->id, $enterpriseName, $idCardPic, $backPic, $licensePic, $teamName, $teamType,
                $teamPic, $contactName, $contactNumber, $licenseNumber, $ownerName, $ownerIdCard, $result);
        } catch (Exception $e) {
            Logger::info("updateAuthDataAction :" . var_export($e->getMessage(), true));
        }
        return $this->response->setJsonContent($result);
    }


    /**
     * @Route("/do_apply", methods={"GET", "POST"})
     */
    public function do_applyAction()
    {
        $user = $this->getUser();
        $result = array("error_code" => 0);
        $reqJson = $this->request->getJsonRawBody(true);
        Logger::info("do_applyAction request:" . var_export($reqJson, true));
        $msg = $this->AccountDoapplyValidation->validate($reqJson);
        if (count($msg)) {
            $result = array('error_code' => 101, 'error_msg' => "参数格式不正确", "data" => ArrayHelper::validateMessages($msg));
        } else {
            $fenji = trim($reqJson['contactMobile_fenji']);
            $contactMobile = $reqJson['contactMobile_city'] . "-" . $reqJson['contactMobile_number'] . (empty($fenji) ? "" : "-" . $fenji);
            $accountType = $this->constant->usertype->$reqJson["type"];
            try {
                $this->ApplyAdminService->applyAdmin($user->id, $reqJson["enterpriseName"], $reqJson["licenceNumber"], $reqJson["licencePic"], $reqJson["cityCode"], $reqJson['provinceId'], $reqJson["builddate"], $contactMobile, $accountType, $result);
            } catch (\Exception $e) {
                Logger::info("user:" . $user->id . " do_applyAction error:" . $e->getMessage());
                $result = array("error_code" => 102, "error_msg" => " ");
            }
        }
        $this->response->setJsonContent($result)->send();
    }

    /**
     *  账户安全 =》 修改密码
     * @Route("/changePwd", methods={"POST"})
     */

    public function changePwdAction()
    {
        $result = array('error_code' => 0, 'error_msg' => "");
        $oldPwd = $this->request->getPost("oldPwd");
        $newPwd = $this->request->getPost("newPwd");
        $message = $this->AccountChangePwdValidation->validate(array("newPwd" => $newPwd, "oldPwd" => $oldPwd));
        if (count($message)) {
            $result['error_msg'] = " 参数格式不正确。";
            $result['error_code'] = "10000002";
            $result['data'] = ArrayHelper::validateMessages($message);
        } else {
            $user = $this->getUser();
            $cryptPwd = md5($oldPwd . $user->salt);
            if ($cryptPwd != $user->pwd) {
                $result['error_code'] = 100001;
                $result['error_msg'] = "输入的密码与旧密码不相同";
            } else {
                $cryptNew = md5($newPwd . $user->salt);// 更新密码
                $this->UserService->updateById(array("pwd" => $cryptNew), $user->id);
                Logger::info(" user changePwdAction:" . $user->id . " newPwd:" . $cryptNew);
                $this->UserService->logout();
                $result['error_code'] = 0;
                $result['error_msg'] = "修改密码成功。";
            }
        }
        return $this->response->setJsonContent($result);
    }


    /**
     *  校验 营业执照号码 已经存在
     * @Route("/licenceCodeExist", methods={"POST","GET"})
     */
    public function  licenceCodeExistAction()
    {
        $req = $this->request->getJsonRawBody(true);
        Logger::info("licenceCodeExistAction req:{%s}", var_export($req, true));
        $user = $this->getUser();
        if (empty($req) || !isset($req['licenceCode'])) {
            $result['error_code'] = 10000003;
            $result['error_msg'] = "请填写营业执照号码。";
        } else {
            try {
                $exist = $this->ApplyAdminService->checkLicenceExist($req['licenceCode'], $user->id);
                if ($exist) {
                    $result = array('error_code' => 10000001, 'error_msg' => "此营业执照号码已存在。");
                } else {
                    $result['error_code'] = 0;
                    $result['error_msg'] = "此营业执照号码不存在。";
                }
            } catch (\Exception $e) {
                Logger::warn("licenceCodeExistAction error:{%s}" . $e->getMessage());
                $result['error_code'] = 10000002;
                $result['error_msg'] = "网络错误，请再次尝试。";
            }
        }
        return $this->response->setJsonContent($result);
    }

}
