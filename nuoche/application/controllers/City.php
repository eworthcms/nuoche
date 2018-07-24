<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @author liuweitao0819
 * @desc 城市列表
 */

class City extends MY_Controller {
    
	public function __construct(){
		parent::__construct();
	}
	
	public function city_list(){
		$params=$this->input->post();
		$this->load->service('city_service');
		$res=$this->city_service->city_list($params);
		$data="<option data-id='0' value='0'>全部</option>";
		if (is_array($res) && count($res)){
			foreach ($res as $key=>$resOne){
				$data.="<option data-id='{$resOne['region_id']}' value='{$resOne['region_id']}'>{$resOne['region_name']}</option>";
			}
		}
		$this->json_out(200,'获取数据成功',$data);
	}
}