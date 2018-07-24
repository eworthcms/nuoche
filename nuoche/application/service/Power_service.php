<?php
/**
 * 权限控制器
 * @author luoyaya 2017/08/18
 *
 */
class Power_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
 		$this->load->model('power_model');
    }
    /**
    * 获取当前角色类型所有权限
    */
    public function get_power($type){
    	$select = "title,class,img,url";
    	$where = array('id' => $type );
    	return $this->power_model->get_all($select,$where);
    }
}