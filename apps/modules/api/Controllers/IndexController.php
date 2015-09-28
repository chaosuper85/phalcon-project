<?php


/**
 * @RoutePrefix("/index")
 */
class IndexController extends ControllerBase
{
    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        try {

            $ret = array("status" => "FAIL");


            $this->response->setJsonContent( $ret );
            $this->response->send();

            $this->view->disable();

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }
}