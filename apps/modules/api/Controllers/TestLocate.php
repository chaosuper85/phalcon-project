<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/28
 * Time: 下午5:26
 */
/**
 * @RoutePrefix("/testlocate")
 */
class TestLocateController extends ControllerBase {

    /**
     * @Post("/add")
     */
    public function addAction() {
        $res = array(
            'error_code'    => '0',
            'error_msg'     => '',
            'data'          => array()
        );

        try{
            $latitude       = $this->request->getPost('latitude');
            $longitude      = $this->request->getPost('longitude');
            $locate_info    = $this->request->getPost('locate_info');
            $locate_type    = $this->request->getPost('locate_type');

            $data = array();
            $data['latitude'] = !empty($latitude) ? $latitude : 0;
            $data['longitude'] = !empty($longitude) ? $longitude : 0;
            $data['locate_info'] = !empty($locate_info) ? $locate_info : '';
            $data['locate_type'] = !empty($locate_type) ? $locate_type : 0;
            $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

            $test_locate_model = new TestLocate();
            $test_locate_model->save($data);
            if ($test_locate_model->id) {
                $res['data']['isok'] = '1';
            }
            else {
                $res['error_code'] = '1';
                $res['error_msg'] = '数据插入失败';
            }

            $this->response->setJsonContent($res)->send();

        } catch (\Exception $e) {
            Logger::warn("TEST LOCATE WARNING:" . $e->getMessage());
        }

    }
}