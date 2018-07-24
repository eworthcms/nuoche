<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Record extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->database ();
	}

	
	//挪车记录
	public function index(){
	    $params=$this->input->get();
		$page=intval($params['page']);
		if ($page<=0){
			$page=1;
		}
		//分页配置
		$this->load->library('pagination');
		$this->load->config('pagination');
		$config = $this->config->item('pagination_common_list');
		$offset = $config['per_page'] * ($page - 1);
		$condition=$params;
		if (is_array($condition) && count($condition)){
            $map='?';
			foreach ($condition as $key=>$con){
				if ($con!=null && $con!='' && $key!='page'){
					$map.="{$key}={$con}&";
				}else{
					unset($condition[$key]);
				}
			}
		}
		$this->load->service("record_service");
		if (empty($map)){
			$base_url=base_url()."/record/index";
		}else{
			$base_url=base_url()."/record/index".$map;
		}
		$config['base_url'] =$base_url;
		$config['total_rows'] =$this->record_service->record_count($condition);
		$this->pagination->initialize($config);
		$total_page=ceil($config['total_rows']/$config['per_page']);
		$condition['page_size']=$config['per_page'];
		$condition['offset']=$offset;
		$records=$this->record_service->record_list($condition);
		$this->pagination->initialize($config);
		$data['total_page']=$total_page;
		$data['page'] =$this->pagination->create_links();
		$data['page_info']=array(
			'zh_title'=>'挪车信息',
			'en_title'=>'Record&nbsp;&nbsp;Infomation'
		);
		$data['records']=$records;
		//城市列表
		$this->load->service("city_service");
		$city_list=$this->city_service->city_list();
		$data['city_list']=$city_list;
		$data['map']=$condition;
		$this->render('record/index',$data);
	}

	public function city_list(){
		$params=$this->input->get();
		$province_id=intval($params['province_id']);
		$this->load->service('city_service');
		$city_list=$this->city_service->city_list(array('province_id'=>$province_id));
		$city="<option value=''>全部</option>";
		if (is_array($city_list) && count($city_list)){
			foreach ($city_list as $key=>$cityOne){
				$city_id=intval($cityOne['region_id']);
				$city_name=strval($cityOne['region_name']);
				$city.="<option value='{$city_name}'>{$city_name}</option>";
			}
		}
		$this->json_out(200,'获取数据成功',$city);	
	}
	
	
}
