<?php

use Library\Log\Logger;

/**
 * @RoutePrefix("/carteam")
 */
class CarteamController extends ControllerBase
{

    /**
     * @Get("/")
     */
    public function indexAction()
    {
        try {

            $data = array();

            $this->data['data'] =  $data;

            Logger::info('data: '.var_export($this->data,true));

            $this->view->pick("carteam/page/index")->setVar('data', $this->data);

        } catch (\Exception $e) {
            Logger::error('exception: '.$e->getTraceAsString());
        }

        return ;
    }


}
