<?php

/**
 * 自定义控制器类
 * @date	2016-06-21
 * @author	huangshiwei
 */

class MY_Controller extends CI_Controller {

	protected $layout = 'layout/main';
	public $layout_data = array('title'=>'意活传媒数据营销平台');
	#private $monUrl = "http://120.76.26.201:82";  //监控中心地址
	private $monUrl = "http://119.23.127.27:82";  //监控中心地址
	#private $monUrl = "http://localhost:82";  //监控中心地址
	private $monKey = "01e74548f913a093aadcaa4151770098";  //监控中心签名秘钥
	public $user_id;
	public $user_name;
	public $true_name;
	/**
	 * 获取用户信息
	 */
	public function __construct() 
	{
		parent::__construct();
	    $this->load->service('user_service');
	    $this->load->service('log_service');

		$code=$_GET['code'];

		//获取code
		if (empty ( $code )) {
            $redirect_uri = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'];
            $result = $this->get_authorize_url(config_item('appid'),$redirect_uri,$state);
            redirect( $result );
        }
        $request = $this->user_service->find_user($this->session->userdata('openid'));
        //将openid放入session
        if(!$this->session->userdata('openid')) {
        	 if($code){
	        	$this->load->service('token_service');
				$access_token = $this->token_service->find_token();

	            //仅仅获取openid
	            $openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".config_item('appid')."&secret=".config_item('secret')."&code=$code&grant_type=authorization_code";
	            $openid = json_decode(file_get_contents($openid_url),true);
	            $this->session->set_userdata('openid',$openid['openid']);
	        } else {
	        	echo "参数错误";die;
	        }
        }
       
	}
	 /**
	 * 微信授权
	 */
    public function get_authorize_url($app_id,$redirect_uri = '', $state = '')
    {
        $redirect_uri = urlencode($redirect_uri);
        
        $type='snsapi_base';
      
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$type}&state={$state}#wechat_redirect";
    }
	
	protected function render($file = NULL, &$view_data = array())
	{
		if( !is_null($file) ) {
			$data['content'] = $this->load->view($file, $view_data, TRUE);
			$data['layout'] = $this->layout_data;
			$this->load->view($this->layout, $data);
		} else {
			$this->load->view($this->layout, $view_data);
		}
		$view_data = array();
	}

	protected function parse($file = NULL, &$render_data = array())
	{
		if(!$render_data){
			$render_data = array();
		}
		if( !is_null($file) ) {
			$data['content'] = $this->parser->parse($file, $render_data, TRUE);
			$data['layout'] = $this->layout_data;
			$this->parser->parse($this->layout, $data);
		} else {
			$this->parser->parse($this->layout, $render_data);
		}
		$view_data = array();
	}


	public function set_page($page_config=array() )
	{

		//分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config = array_merge( $config,$page_config );
	    $config['total_rows'] = $this->db->get()->num_rows();

	    $config['offset'] = $config['per_page'] * ($config['cur_page'] - 1);
	    $config['total_page'] = ceil($config['total_rows']/$config['per_page']);

	    $this->pagination->initialize($config);
	    $config['page'] = $this->pagination->create_links();
	    return $config;
	}


	/**
	 * auth:awen
	 * 更新监控中心广告主信息
	 * @param $userId
	 * @return bool|mixed
	 */
	protected function getRenewMon($userId)
	{
		if($userId)
		{
			$param = [];
			$param['signtype'] = 1;
			$param['reqtime'] = time();
			$param['service'] = "monitor";
			$param['method'] = "check_user";
			$param['data'] = ['userId'=>$userId];
			//签名参数
			$signParam = $param['data'];
			$signParam['reqtime'] =$param['reqtime'];
			$param['sign'] = $this->createSign($signParam);

			$result =  json_decode($this->curlRequst($this->monUrl,json_encode($param)),true);
			print_r($result);exit;
			if($result['code'] == 200)
			{
				return $result['data'];
			}
		}
		return false;
	}

	/**
	 * auth:awen
	 * 监控中心签名函数
	 * @param $param
	 * @return string
	 */
	private function createSign($param)
	{
		if($param && is_array($param))
		{
			ksort($param);

			return md5(md5(base64_encode(http_build_query($param))).$this->monKey);
		}
	}
	/**
	 * auth:awen
	 * 发送curl请求
	 * @param $url
	 * @param bool|false $data
	 * @param string $type
	 * @param null $err_msg
	 * @param int $timeout
	 * @param array $cert_info
	 * @return mixed
	 */
	protected function curlRequst($url,$data = false, $type ="POST" , &$err_msg = null, $timeout = 20, $cert_info = array())
	{
		$type = strtoupper($type);
		if ($type == 'GET' && is_array($data)) {
			$data = http_build_query($data);
		}
		$option = array();
		if ( $type == 'POST' ) {
			$option[CURLOPT_POST] = 1;
		}
		if ($data) {
			if ($type == 'POST') {
				$option[CURLOPT_POSTFIELDS] = $data;
			} elseif ($type == 'GET') {
				$url = strpos($url, '?') !== false ? $url.'&'.$data :  $url.'?'.$data;
			}
		}
		//self::logs($url);
		$option[CURLOPT_URL]            = $url;
		$option[CURLOPT_FOLLOWLOCATION] = TRUE;
		$option[CURLOPT_MAXREDIRS]      = 4;
		$option[CURLOPT_RETURNTRANSFER] = TRUE;
		$option[CURLOPT_TIMEOUT]        = $timeout;

		//设置证书信息
		if(!empty($cert_info) && !empty($cert_info['cert_file'])) {
			$option[CURLOPT_SSLCERT]       = $cert_info['cert_file'];
			$option[CURLOPT_SSLCERTPASSWD] = $cert_info['cert_pass'];
			$option[CURLOPT_SSLCERTTYPE]   = $cert_info['cert_type'];
		}

		//设置CA
		if(!empty($cert_info['ca_file'])) {
			// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
			$option[CURLOPT_SSL_VERIFYPEER] = 1;
			$option[CURLOPT_CAINFO] = $cert_info['ca_file'];
		} else {
			// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
			$option[CURLOPT_SSL_VERIFYPEER] = 0;
		}

		$ch = curl_init();
		curl_setopt_array($ch, $option);
		$response = curl_exec($ch);
		$curl_no  = curl_errno($ch);
		$curl_err = curl_error($ch);
		curl_close($ch);

		// error_log
		if($curl_no > 0) {
			if($err_msg !== null) {
				$err_msg = '('.$curl_no.')'.$curl_err;
			}
		}
		return $response;
	}
	
	public function json_out($code=1001,$msg='未知错误',$data=array(),$isDebug=0){
		if (empty($data) && $data==null){
			$data=array();
		}
	
		$res=array(
				'code'=>$code,
				'msg'=>$msg,
				'data'=>$data
		);
		if ($isDebug==1){
			var_dump($res);
		}else{
			echo json_encode($res);exit;
		}
	}

}
