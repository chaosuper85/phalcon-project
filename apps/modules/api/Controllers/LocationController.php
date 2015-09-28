<?php
/**
 *
 * 位置相关的controller    堆场信息上报   位置上报
 *
 * @RoutePrefix("/location")
 */
class LocationController extends ControllerBase
{
    const REQUEST_DATA_ERROR    = '请求数据错误';
    const INVALID_REQUEST       = '无效请求';
    const IS_OVERWRITE_DATA     = '信息已经存在，是否覆盖';

    const ANDROID_KEY   = 'android_56xdd';


    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        try {
            $ret = array(
                'error_code'    => 0,
                'error_msg'     => '',
                'data'          => array()
            );

            $data = $this->YardInfoService->getYardInfo();
            $ret['data'] = empty($data) ? array() : $data;

            $this->response->setJsonContent( $ret );
            $this->response->send();

            $this->view->disable();

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * @Route("/create", methods={"GET", "POST"})
     */
    public function createAction()
    {
        try {
            $ret = array(
                'error_code'    => 0,
                'error_msg'     => '',
                'data'          => array()
            );

            $data = $this->_filterData();
            if (!is_array($data)) {
                $ret['error_code']  = "1";
                $ret['error_msg']   = $data;
            }
            else {
                # check yard_id
                if (!empty($data['yl']['yard_id'])) {
                    $item = $this->YardLocationService->checkLocationInfo(
                        $data['yl']['yard_id'],
                        $data['yl']['location_type'],
                        $data['yl']['location_car_type'],
                        $data['yl']['location_degree_type']
                    );
                    if ($item) {
                        $ret['error_code']  = "1";
                        $ret['error_msg']   = self::IS_OVERWRITE_DATA;
                    }
		            else {
			            # add yard location info
                        $yard_location_model = new YardLocation();
                        $data['yl']['yard_address'] = $this->di->get('AppLocateService')->getAddressByLocation(
                            $data['yl']['latitude'],
                            $data['yl']['longitude']
                        );
                        $isok = $yard_location_model->save($data['yl']);
                        $ret['data']['yid'] = $data['yl']['yard_id'];
			            $ret['data']['isok'] = 1;
		            }
                }
		        else {
                    # add yard info
                    $yard_info_model = new YardInfo();
                    $yi_isok = $yard_info_model->save($data['yi']);
                    $yard_id = $yard_info_model->id;

                    if ($yi_isok) {
                        # add yard location info
                        $yard_location_model = new YardLocation();
                        $data['yl']['yard_id']  = $yard_id;
                        $data['yl']['yard_address'] = $this->di->get('AppLocateService')->getAddressByLocation(
                            $data['yl']['latitude'],
                            $data['yl']['longitude']
                        );
                        $yl_isok = $yard_location_model->save($data['yl']);
                    }

                    if ($yi_isok && $yl_isok) {
                        $ret['data']['yid'] = $yard_id;
                        $ret['data']['isok'] = 1;
                    }

                }
            }

            $this->response->setJsonContent( $ret );
            $this->response->send();

            $this->view->disable();

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }


    /**
     * @Route("/edit", methods={"GET", "POST"})
     */
    public function editAction()
    {
        try {
            $ret = array(
                'error_code'    => 0,
                'error_msg'     => '',
                'data'          => array()
            );

            $data = $this->_filterData();
            if (!is_array($data)) {
                $ret['error_code']  = "1";
                $ret['error_msg']   = $data;
            }
            else {
                # check yard id
                $item = $this->YardLocationService->checkLocationInfo(
                    $data['yl']['yard_id'],
                    $data['yl']['location_type'],
                    $data['yl']['location_car_type'],
                    $data['yl']['location_degree_type']
                );

                if ($item['id']) {
                    # update yard location
                    $yard_location = YardLocation::findFirst($item['id']);
                    unset($data['yl']['create_time']);
                    $data['yl']['yard_address'] = $this->di->get('AppLocateService')->getAddressByLocation(
                        $data['yl']['latitude'],
                        $data['yl']['longitude']
                    );
                    $yl_isok = $yard_location->save($data['yl']);

                    #update yard info
                    $yard_info = YardInfo::findFirst($data['yl']['yard_id']);
                    unset($data['yi']['create_time']);
                    $yi_isok = $yard_info->save($data['yi']);
                }

                if ($yi_isok && $yl_isok) {
                    $ret['data']['isok'] = 1;
                }
            }

            $this->response->setJsonContent( $ret );
            $this->response->send();

            $this->view->disable();

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * @Route("/select", methods={"GET", "POST"})
     */
    public function selectAction()
    {
        try {
            $ret = array(
                'error_code'    => 0,
                'error_msg'     => '',
                'data'          => array()
            );

            $yid = $this->request->getPost('yid');
            #$yid = 8;
            if (!empty($yid)) {
                $list = array();

                $yard_locations = YardLocation::find("yard_id=".$yid);
                if (count($yard_locations)) {
                    foreach ($yard_locations as $i => $location) {
                        $list[$i] = $location->location_type . $location->location_car_type . $location->location_degree_type;
                    }

                    $ret['data']['yid']     = $yid;
                    $ret['data']['list']    = $list;
                    $this->response->setJsonContent( $ret );
                    $this->response->send();

                    $this->view->disable();
                }
            }
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * params filter
     */
    private function _filterData() {
        $data = array();

        if ($this->request->isPost()) {
            $data['yl']['yard_id']          = $this->request->getPost('yid');
            $data['yl']['latitude']         = $this->request->getPost('latitude');
            $data['yl']['longitude']        = $this->request->getPost('longitude');
            $data['yi']['cock_city_code']   = $this->request->getPost('cock_city_code');
            $data['yi']['yard_name']        = $this->request->getPost('yard_name');

            $location_type        = $this->request->getPost('location_type');
            $location_car_type    = $this->request->getPost('location_car_type');
            $location_degree_type = $this->request->getPost('location_degree_type');
            $t                    = $this->request->getPost('t');
            $st                   = $this->request->getPost('st');

            if (!$data['yl']['latitude'] || !$data['yl']['longitude'] || !$data['yi']['yard_name'] || !$data['yi']['cock_city_code']) {
                return self::REQUEST_DATA_ERROR;
            }

            if (md5(self::ANDROID_KEY . $st) != $t) {
                return self::INVALID_REQUEST;
            }

            $data['yl']['location_type']        = in_array($location_type, array_keys((array)$this->constant->yard_location_type)) ? $this->constant->yard_location_type->$location_type : 0;
            $data['yl']['location_car_type']    = in_array($location_car_type, array_keys((array)$this->constant->yard_car_type)) ? $this->constant->yard_car_type->$location_car_type : 0;
            $data['yl']['location_degree_type'] = in_array($location_degree_type, array_keys((array)$this->constant->yard_degree_type)) ? $this->constant->yard_degree_type->$location_degree_type : 0;

            $data['yi']['create_time'] = $data['yi']['update_time'] = $data['yl']['create_time'] = $data['yl']['update_time'] = date('Y-m-d H:i:s');
        }

        if (empty($data)) {
            return self::INVALID_REQUEST;
        }

        return $data;
    }

}
