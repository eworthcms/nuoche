<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	 public function __construct(){
        parent::__construct();
		$this->load->helper('common');
		$this->load->library('session');
		$this->load->service('user_service');
        $this->load->service('sms_verification_servie');
        $this->load->service('log_servie');
        $this->load->service('telephone_record_service');
    }
	/**
	*	获取验证码
	*/
	public function get_sms_code(){
		$mobile=$this->input->get('mobile');
        $this->load->model('user_model');
        $userInfo=$this->user_model->get_row('*','',array('mobile'=>$mobile));
        if (is_array($userInfo) && count($userInfo)){
            echo 2;exit;
        }
		return $this->sms_verification_servie->get_sms_code($mobile);
	}
	/**
	* 验证验证码正确性
	*/
	public function check_code(){
		$code=$this->input->get('code');
		$mobile=$this->input->get('mobile');
		$session=$this->session->userdata('str');
		if($code!=$session){
			echo 'false';
		}
	}
	/**
	*	上报用户手机号
	*/
	public function upd_mobile(){
		$data=$this->input->post();
		$openid=$this->session->userdata('openid');
		//修改用户手机号
		$request = $this->user_service->find_user($openid);
		$request['mobile']=$data['mobile'];
		$this->db->where('id',$request['id']);
		$query=$this->db->update('user',$request);
		if(!empty($data['license_plate'])){
			$cars= array(
				'user_id' => $request['id'], 
				'car_no' => strtoupper($data['top'].$data['license_plate'])
			);
			$this->db->insert('cars',$cars);
		}
		echo $query;
	}
	/**
	*	意见反馈页面
	*/
	public function feedback()
	{
		$request=$this->auth();
		$data['user_id']=$request['id'];
		$this->load->view('home/feedback',$data);
	}
	/**
	*	扫描后挪车页面
	*/
	public function movethecar()
	{

		$no=$this->input->get('no');
		$openid=$this->input->get('openid');
		/*$re=$this->telephone_record_service->add_tele($no,$openid);*/
		$request['user'] = $this->user_service->get_user_info($no);
		$request['report'] = $this->db->get('report')->row_array();
		$request['from_user_id'] = $this->user_service->find_user($openid);
		$this->load->view('home/concat',$request);
	}
	/**
	*	记录挪车记录
	*/
	public function add_record()
	{
		$from_user_id=$this->input->get('from_user_id');
		$to_user_id=$this->input->get('to_user_id');
		$ip=$this->input->get('ip');
		$re=$this->telephone_record_service->add_tele($from_user_id,$to_user_id,$ip);
		echo $re;
	}
	/**
	*	记录意见反馈
	*/
	public function add_feed()
	{
		$data=$this->input->post();
		$request = $this->user_service->add_feed($data);
		echo $request;
	}
	private function auth()
	{
		$code=$_GET['code'];
		//获取code并返回当前方法
		if (empty ( $code )) {
            $redirect_uri = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'];
            $result = $this->get_authorize_url(config_item('appid'),$redirect_uri,$state);
            redirect( $result );
        }
        if($code){
        	//通过code获取access_token
        	$api_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".config_item('appid')."&secret=".config_item('secret')."&code=$code&grant_type=authorization_code";
            $get_info =json_decode(file_get_contents($api_url),true);
            return $this->user_service->find_user($get_info['openid']);
		}
	}
	 /**
	 * 微信授权
	 */
    public function get_authorize_url($app_id,$redirect_uri = '', $state = '')
    {
        $redirect_uri = urlencode($redirect_uri);
        
        $type='snsapi_userinfo';
      
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$type}&state={$state}#wechat_redirect";
    }

}