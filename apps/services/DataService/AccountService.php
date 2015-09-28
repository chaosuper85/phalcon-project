<?php

namespace Services\DataService;

use \Users as Users;
use Library\Helper\StatusHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

use \Phalcon\DiInterface;

class AccountService extends Component
{

    /**
     *  货代/车队 上传认证资料
     */
    public function updateAuthData($userId,$enterpriseName,$idCardPic,$backPic,$licensePic,$teamName,$teamType,
                    $teamPic,$contactName,$contactNumber,$licenseNumber,$ownerName,$ownerIdCard,&$result){

        $user = Users::findFirst("id ='$userId' ");
        if( empty($user) ){
            $result['error_code'] = 1;
            $result['error_msg' ] = "更新失败，请重新上传。";
            return false;
        }
        // 更新user
        $user->contactName            = $contactName;
        $user->contactNumber          = $contactNumber;
        $user->enterprise_licence     = $licenseNumber;
        $user->unverify_enterprisename= $enterpriseName;
        $user->update();

        switch( $user->usertype ) {
            case 1:// carteam
                $carTeam = $this->CarTeamService->getByUserId($userId);

                $params = array(
                    'teamName'           => $teamName,
                    'teamPic'            => $teamPic ,
                    'idcard_pic'         => $idCardPic,
                    'teamType'           => $teamType,
                    'ownerName'          => $ownerName,
                    'ownerIdentityCardId'=> $ownerIdCard
                );

                $status = new StatusHelper($carTeam);
                $status->guest && $status->del('audit_guest');
                $status->audit_reject && $status->del('audit_reject');

                $params['status'] = $status->add($status->audit_pass)->getStatus();
                $this->CarTeamService->updateByUserId($params,$user->id);

            break;

            case 2: // 货代
                $agent  = $this->AgentService->getByUserId($userId);
                $params = array(
                    'enterpriseName'    => $enterpriseName,
                    'avartar_idcard_pic'=> $idCardPic,
                    'idcard_back_pic'   => $backPic,
                    'cargo_pic'         => $licensePic,
                );

                $status = new StatusHelper($agent);
                $status->guest && $status->del('audit_guest');
                $status->audit_reject && $status->del('audit_reject');

                $params['status'] = $status->add('audit_pass')->getStatus();
                $this->AgentService->updateByUserId($params,$user->id);
            break;

            default:
            break;
        }

        $result['error_code'] = 0;
        $result['error_msg']  = "更新成功";
        return true;
    }



}