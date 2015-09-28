<?php

use Phalcon\Http\Response;
use Services\Services as Services;
use Library\Log\Logger;


/**
 * @RoutePrefix("/msgP2P")
 */
class MsgP2PController extends ControllerBase {
    /**
     * @Route("/onePage", methods={"GET", "POST"})
     */
    public function onePageAction(){
        $user = $this->session->get('login_user');
        if(empty($user)){
            Logger::info('用户不存在!');
            return false;
        }
        $rec_id = $user->id;
        $page = $this->request->getQuery('page');
        $onePageMsg = $this->MsgP2PService->getOnePageMsg($rec_id, null, $page);
        $pageCount = $this->MsgP2PService->getPageCount($rec_id);

        $result = array(
            'onePageMsg' => $onePageMsg,
            'pageCount' => $pageCount
        );
        $view =  $this->view->pick('index/page/msgP2p');//页面

        $this->data['data'] =  $result;

        $view->setVar('data', $this->data);
        return $this->view;
    }
}