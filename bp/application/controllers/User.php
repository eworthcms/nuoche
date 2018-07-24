<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class User extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->database ();
	}

	
	//用户列表
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
		$list_type=$params['type'];
		$list_str='';
		$this->load->service("user_service");
		if (empty($map)){
			$base_url=base_url()."/user/index";
		}else{
			$base_url=base_url()."/user/index".$map;
		}
		$config['base_url'] =$base_url;
		$config['total_rows'] =$this->user_service->user_count($condition);
		$this->pagination->initialize($config);
		$total_page=ceil($config['total_rows']/$config['per_page']);
		$condition['page_size']=$config['per_page'];
		$condition['offset']=$offset;
		$users=$this->user_service->user_list($condition);
		$this->pagination->initialize($config);
		$data['total_page']=$total_page;
		$data['page'] =$this->pagination->create_links();
		$data['page_info']=array(
			'zh_title'=>'用户信息',
			'en_title'=>'User&nbsp;&nbsp;Infomation'	
		);
		$data['users']=$users;
		//用户注册数  二维码绑定数
		
		$user_list=$this->db->where(array())->get('user')->result_array();
		$all_user=count($user_list);
		
		$qrcode_list=$this->db->where(array('user_id >'=>0,'status'=>0))->get('qrcode')->result_array();
		$all_qrcode=count($qrcode_list);
		$data['user_total']=$all_user;
		$data['qrcode_total']=$all_qrcode;
		//城市列表
		$this->load->service("city_service");
		$city_list=$this->city_service->city_list();
		$data['city_list']=$city_list;
		$data['map']=$condition;
		////var_dump($data);
		$this->render('user/index',$data);
	}
	
	public function user_info(){
		$params=$this->input->get();
		$user_id=$params['user_id'];
		$this->load->service('user_service');
		$user=$this->user_service->get_user($user_id);
		$this->json_out(200,'获取用户信息成功',$user);
	}
	public function save_user(){
		$params=$this->input->post();
		$mobile=$params['mobile'];
		$car_no=$params['car_no'];
		$user_id=$params['user_id'];
		if (!empty($mobile) && !empty($car_no)){
			$this->load->model('user_model');
			$this->load->model('cars_model');
			$this->user_model->save_user(array('mobile'=>$mobile,'modify_time'=>time()),array('id'=>$user_id));
			$this->cars_model->save_car(array('car_no'=>$car_no,'modify_time'=>time()),array('user_id'=>$user_id));
		}
		$this->json_out(200,'修改数据成功');
	}
	
	public function do_status(){
		$params=$this->input->post();
		$status=intval($params['status']);
		$user_id=$params['user_id'];
		if ($status==0){
			$do_status=1;
		}else{
			$do_status=0;
		}
		$this->load->model('user_model');
		$this->user_model->save_user(array('status'=>$do_status,'modify_time'=>time()),array('id'=>$user_id));
		$this->json_out(200,'修改数据成功');
	}
	
	
	public function city_list(){
		$params=$this->input->get();
		$province_id=intval($params['province_id']);
		$this->load->service('city_service');
		$city_list=$this->city_service->city_list(array('province_id'=>$province_id));
		$city="<option value=''>全部</option>";
		if (is_array($city_list) && count($city_list)){
			foreach ($city_list as $key=>$cityOne){
				//$city_id=intval($cityOne['region_id']);
				$city_name=strval($cityOne['region_name']);
				$city.="<option value='{$city_name}'>{$city_name}</option>";
			}
		}
		$this->json_out(200,'获取数据成功',$city);	
	}
	
	
}
