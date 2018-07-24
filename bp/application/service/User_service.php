<?php
class User_service extends MY_Service {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	public function find_user($post) {
		$this->db->select ( 'id, user_name, true_name' );
		$where = array (
				'user_name' => $post ['user_name'],
				'password' => md5 ( $post ['password'] ),
				'status' => 1 
		);
		
		$query = $this->db->get_where ( 'admin_user', $where )->row_array ();
		
		return $query;
	}
	// 登录验证
	public function check_login($username, $password, $code) {
		$num = '';
		$user_name = array ();
		$query = array ();
		if ($username) {
			$where = array (
					'user_name' => $username,
					'status' => 1 
			);
			$user_name = $this->db->get_where ( 'admin_user', $where )->row_array ();
		}
		if ($username && $password) {
			$where = array (
					'user_name' => $username,
					'password' => md5 ( $password ),
					'status' => 1 
			);
			$query = $this->db->get_where ( 'admin_user', $where )->row_array ();
		}
		// 用户不存在或已被停用为1 密码不正确为2 验证码不正确为3
		if (! $user_name) {
			$num = 1;
		}
		if (! $query && ! $num) {
			$num = 2;
		}
		if ($user_name && $query && strtolower ( $code ) != strtolower ( $this->session->userdata ( 'reg_code' ) )) {
			$num = 3;
		}
		
		return $num;
	}
	
	// 用户列表
	public function user_list($condition) {
		$this->load->helper('common');
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		$res=array();
		if (is_array($result) && count($result)){
			foreach ($result as $key =>$resultOne){
				$no=$resultOne['no'];
				$qrcode=$resultOne['qrcode'];
				if (!empty($no) && !empty($qrcode)){
					$local_qrcode=ROOT_PATH."uploads/qrcode/".$no.'.jpg';
					if (!file_exists($local_qrcode)){
						gen_qrcode($qrcode, $no);
					}
					
				}
			}
		}
		return $result;
	}
	
	public function user_count($condition) {
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		return count($result);
	}
	
	private function make_condition($conditions) {
		$this->db->select ( 'user.id,user.user_name,user.nick_name,user.open_id,user.is_subscribe,user.mobile,qrcode.source,qrcode.no,qrcode.qrcode,user.status,cars.car_no,user.subscribe_time,user.create_time' );
		$this->db->from ( 'user' );
		$this->db->join ( 'qrcode', 'user.id=qrcode.user_id','left');
		$this->db->join ( 'cars', 'user.id=cars.user_id','left' );
		if (isset ( $conditions ['user_id'] ) && $conditions ['user_id']) {
			$this->db->like ( 'user.id', $conditions ['user_id']);
			$this->db->or_like ( 'user.nick_name', $conditions ['user_id'],'both');
			$this->db->or_like ( 'user.mobile', $conditions ['user_id'],'both');
		}
		
		if (isset ( $conditions ['source'] ) && $conditions ['source']) {
			$this->db->like ( 'qrcode.source', $conditions ['source'] );
		}
		
		if (isset($conditions['province']) && $conditions['province'] && isset($conditions['city']) && $conditions['city']){
			$this->db->where('user.province',$conditions['province']);
			$this->db->where('user.city',$conditions['city']);
		}
		if (isset ( $conditions ['from'] ) && $conditions ['from'] && isset ( $conditions ['to'] ) && $conditions ['to']) {
			$begin_time = strtotime ( $conditions ['from'] );
			$end_time = strtotime ( $conditions ['to'] . '23:59:59' );
			$this->db->where ( "user.create_time between {$begin_time} and  {$end_time}" );
		}
		$this->db->order_by ( "user.id", 'desc' );
		if (isset ( $conditions ['page_size'] ) && isset ( $conditions ['offset'] )) {
			$this->db->limit ( $conditions ['page_size'], $conditions ['offset'] );
		}
	}
	public function get_user($user_id){
		$this->db->select ( 'user.id,user.user_name,user.nick_name,user.open_id,user.is_subscribe,user.mobile,qrcode.source,qrcode.no,qrcode.qrcode,qrcode.status,cars.car_no,user.subscribe_time,user.create_time' );
		$this->db->from ( 'user' );
		$this->db->join ( 'qrcode', 'user.id=qrcode.user_id','left');
		$this->db->join ( 'cars', 'user.id=cars.id','left' );
		$this->db->where('user.id',$user_id);
		return $this->db->get()->row_array();
	}
	
	/**
	 *   上报用户二维码
	 */
	public function add_qrcodes($qrcodeval)
	{
		return $this->db->insert_batch('qrcode',$qrcodeval);
	}
	
	/**
	 *   上报用户二维码
	 */
	public function add_qrcode($qrcodeval)
	{
		return $this->db->insert('qrcode',$qrcodeval);
	}
}
