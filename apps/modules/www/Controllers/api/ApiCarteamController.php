<?php

use Library\Log\Logger;

/**
 * @RoutePrefix("/api/carteam")
 */
class ApiCarteamController extends ApiControllerBase
{

    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        try {


        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }


}
