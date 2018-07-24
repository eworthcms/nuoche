<?php

/**
 * 自定义控制器类
 * @date	2016-06-21
 * @author	huangshiwei
 */

class MY_Controller extends CI_Controller {

	protected $layout = 'layout/main';
	public $layout_data = array('title'=>'扫码挪车-管理后台');
	public $user_id;
	public $user_name;
	public $true_name;

	/**
	 * 构造函数，判断用户是否登录
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->layout_data['controller'] = substr(uri_string(),0,strpos(uri_string(),'/'));
		if(!$this->session->userdata('id'))
		{
			// 未登录
		    redirect(base_url('/login/user_login'));
		}
		else
		{
			$this->user_id = $this->session->userdata('id');
			$this->user_name = $this->session->userdata('user_name');
			$this->true_name = $this->session->userdata('true_name');
			$this->layout_data['user_id'] = $this->user_id;
			$this->layout_data['user_name'] = $this->user_name;
			$this->layout_data['true_name'] = $this->true_name;
		}
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
		$this->parser->parse($file, $render_data);
	}

	public function sql()
	{
		dd($this->db->last_query());
	}

	public function die_error($msg='',$is_ajax=false,$url='')
	{
		if($is_ajax)
		{
			die(json_encode(array(
				'error' => 1,
				'msg' => $msg,
				'url' => $url,
			)));
		}

		if($msg)
		{
			$msg = "alert('$msg');";
		}
		if($url)
		{
			$url = "location.href=$url;";
		}else{
			$url = "history.go(-1);";
		}
		echo "<script>$msg $url</script>";
		exit;
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
