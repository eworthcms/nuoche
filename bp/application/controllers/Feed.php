<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Feed extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
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
		$list_str='';
		$this->load->service("feed_service");
		$total_rows=$this->feed_service->feed_count($condition);
		if (empty($map)){
			$base_url=base_url()."/feed/index";
		}else{
			$base_url=base_url()."/feed/index".$map;
		}
		$config['base_url'] = $base_url;
		$condition['page_size']=$config['per_page'];
		$condition['offset']=$offset;
		$feeds=$this->feed_service->feed_list($condition);
		$total_page=ceil($total_rows/$config['per_page']);
		$this->pagination->initialize($config);
		$data['total_page']=$total_page;
		$data['page'] =$this->pagination->create_links();
		$data['page_info']=array(
			'zh_title'=>'意见反馈',
			'en_title'=>'Feed&nbsp;&nbsp;Back'	
		);
		$data['map']=$condition;
		$data['feeds']=$feeds;
		$this->render('feed/index',$data);
	}
	
}
