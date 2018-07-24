<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public $file_name;

	public function test(){
		$this->load->helper('common');
		$file_name=gen_secret(7);
		$qrcode_img="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQEs8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyUmdIZ1VJM0FhNV8xMDAwME0wN1oAAgR61uZZAwQAAAAA";
		local_qrcode($qrcode_img, $file_name);
		sleep(1);
		gen_text_img($file_name);
		sleep(1);
		merge_qrcode($file_name);
		
		
	}
	
	public function index()
	{
		$this->user_login();
	}
	public function user_login()
	{
	    $this->load->library('session');
	    $data['title'] = "扫码挪车管理系统";
	    $this->load->service('user_service');
	    $this->load->helper('cookie');

	    if($this->input->post()){
	        $post = $this->input->post();
	        $user = $this->user_service->find_user($post);

	        if($user)
	        {
	            $this->session->set_userdata($user);

	            //设置cookie
	            if(isset($post['record']) && $post['record']==1)
	            {
	                $time = 30*24*60*60;
	                $cookie['user_name'] = array(
	                    'name'   => 'user_name',
	                    'value'  => $post['user_name'],
	                    'expire' => $time,
	                );
	                $cookie['password'] = array(
	                    'name'   => 'password',
	                    'value'  => $post['password'],
	                    'expire' => $time,
	                );
	                $this->input->set_cookie($cookie['user_name']);
	                $this->input->set_cookie($cookie['password']);
	            }else{
	                $cookie['user_name'] = array(
	                    'name'   => 'user_name',
	                    'value'  => $post['user_name'],
	                    'expire' => '',
	                );
	                $cookie['password'] = array(
	                    'name'   => 'password',
	                    'value'  => $post['password'],
	                    'expire' => '',
	                );
	                $this->input->set_cookie($cookie['user_name']);
	                $this->input->set_cookie($cookie['password']);
	            }
	            redirect(base_url());
	        }
	    }
	    
	    $this->load->view('login/user_login', $data);
	}
	
	public function user_logout()
	{
	    $this->load->library('session');
	    //$user = array('id','user_name','true_name');
	    //$this->session->unset_userdata($user);
	    $this->session->sess_destroy();
	    
	    redirect(base_url('/login/user_login'));
	}
	
	/**
	 * 获取验证码图片
	 */
    public function get_code() {
        $this->load->library('Captcha',null,"captcha");
        $code = $this->captcha->getCaptcha();
        $this->session->set_userdata('reg_code', $code);
        $this->captcha->showImg();
    }

    //登录验证
    public function check_login($username=0, $password=0, $code=0)
    {
        $username = urldecode($username);
        $this->load->service('user_service');
        $res = $this->user_service->check_login($username, $password, $code);
        echo json_encode($res);
        exit;
    }
    //登录验证
    public function auth($user_id=0,$agent_id=0, $timestamp='')
    {
    	// $this->session->sess_destroy();
    	$this->load->database();
        $this->load->model('rel_agent_user_model');
        $auth_code = $this->rel_agent_user_model->generate_authcode($agent_id,$user_id,$timestamp);
        if( !$auth_code){
        	echo '<script>alert("未授权");window.close();</script>';
        	exit;
        }

        $where = array(
        	'auth_code' => $auth_code,
        	'user_id' => $user_id,
        	'agent_user_id' => $agent_id,
        );
        $this->db->where($where);
        $res = $this->db->get('rel_agent_user')->row_array();
        //查询不到，未授权
        if(!$res){
			echo '<script>alert("代理未授权");window.close();</script>';
        	exit;
        }

        //已授权
        $where = array(
        	'id' => $user_id,
        );
        $this->db->where($where);
        $user_mes = $this->db->get('user')->row_array();

        if(!$res){
			echo '<script>alert("广告主不存在");window.close();</script>';
        	exit;
        }
// dd($user_mes);
        //构造session数据
        $user_mes['is_agent_auth'] = true;
        $user_mes['agent_id'] = $agent_id;
        $this->session->set_userdata($user_mes);
		redirect(base_url());
    }
}
