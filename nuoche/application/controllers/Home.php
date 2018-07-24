<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MY_Controller {
	/**
	 * @author luoya0828
	 * @desc   首页数据统计
	 */
	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->load->service('user_service');
		$this->load->service('qrcode_service');
        $this->load->service('token_service');

	}
	/**
	 * 当前用户是否绑定手机号
	 */
	public function index()
	{
		$openid = $this->session->userdata('openid');
		$user_info = $this->user_service->find_user($openid);
		//$qrcode = $this->qrcode_service->find_qrcode($user_info['id']);
		if(empty($user_info['mobile'])) {
			redirect(base_url('home/register'));
		} else {
			$qrcode = $this->user_service->get_qrcode_info($user_info['id']);
			$this->qrcode($user_info,$qrcode);
		}
	}
	/**
	 * 注册页面绑定手机号
	 */
	public function register()
	{
		$this->load->service('license_plate_service');
		$data['request']=$this->license_plate_service->get_license_plate();
	    $this->load->view('home/register',$data);
	}
	/**
	 * 生成带参数的二维码
	 */ 
	public function qrcode($user_info,$qrcode='')
	{
	    if(empty($qrcode)){
	    	$linsqrcode = $this->qrcode_service->find_linsqrcode($user_info['id']);
	    	if(empty($linsqrcode)) {
	    		$rand = rand(1,9999999);
		    	$access_token = $this->token_service->find_token();
				
				$ticket_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
				$ticket_val = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": ' . $rand . '}}}';
				$ticket = $this->post2($ticket_url,$ticket_val);
				$ticket = json_decode($ticket,true);
				$qrcodeurl ='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket['ticket']);
				$qrcodeval = array (
								'user_id'=>$user_info['id'],
								'source'=>0,
								'no'=>$rand,
								'qrcode'=>$qrcodeurl,
								'status'=>0,
								'create_time'=>time(),
								'modify_time'=>time(),
							);
				$this->user_service->add_qrcode($qrcodeval);
	    	} else {
	    		$qrcodeval = array (
								'user_id'=>$user_info['id'],
								'source'=>2,
								'status'=>0,
								'modify_time'=>time(),
							);
	    	    $this->db->where('no',$linsqrcode['no']);
                $request = $this->db->update('qrcode',$qrcodeval);
                if($request){
               		$this->qrcode_service->upd_linsqecode($linsqrcode['no']);
                }
	    	}
			
		}
		$data['user_info'] = $user_info;
		$data['qrcode_info'] = $this->user_service->get_qrcode_info($user_info['id']);
		$file_name = config_item('img_url').'/uploads/qrcode/'.$data['qrcode_info']['no'].'_qrcode_focus.jpg';
		$data['qrcode_info']['qrcodeurl'] = $file_name;

		if(!file_exists($file_name)){ //图片不存在则生成图片
			gen_qrcode($data['qrcode_info']['qrcode'],$data['qrcode_info']['no']);
		}
		$this->load->view('home/loggedIn',$data);
	}
    public static function post2($url, $post_data = '', $timeout = 5){//curl
        if(is_array($post_data))
            $post_data = http_build_query($post_data);

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);

        if($post_data != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
            'X-ptype: like me'
        ) );

        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);

        return $file_contents;

    }
}
