<?php
class Record_service extends MY_Service {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	//挪车记录
	public function record_list($condition) {
		$this->load->helper('common');
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		$res=array();
		if (is_array($result) && count($result)){
			foreach ($result as $key =>$resultOne){
				$from_user_id=$resultOne['from_user_id'];
				if ($from_user_id>0){
				    $from_user=$this->get_user($from_user_id);
                    $resultOne['from_user_name']=$from_user['nick_name'];
                    $resultOne['from_user_mobile']=$from_user['mobile'];
                }
                $res[]=$resultOne;
			}

		}
		return $res;
	}
	
	public function record_count($condition) {
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		return count($result);
	}
	
	private function make_condition($conditions) {
		$this->db->select ( 'record.id,record.create_time,record.clicks,record.position,user.user_name,user.nick_name,user.open_id,user.is_subscribe,user.mobile,user.status,cars.car_no,record.to_user_id,record.from_user_id' );
		$this->db->from ( 'record' );
		$this->db->join ( 'cars', 'record.car_id=cars.id','left');
		$this->db->join ( 'user', 'record.to_user_id=user.id','left' );
		if (isset ( $conditions ['user_id'] ) && $conditions ['user_id']) {
			$this->db->like ( 'user.id', $conditions ['user_id'] );
			$this->db->or_like ( 'user.nick_name', $conditions ['user_id'],'both');
            $this->db->or_like ( 'user.mobile', $conditions ['user_id'],'both');
            $this->db->or_like ( 'cars.car_no', $conditions ['user_id'],'both');
		}

		if (isset($conditions['province']) && $conditions['province'] && isset($conditions['city']) && $conditions['city']){
			$this->db->like('user.province',$conditions['province'],'both');
			$this->db->like('user.city',$conditions['city'],'both');
		}
		if (isset ( $conditions ['from'] ) && $conditions ['from'] && isset ( $conditions ['to'] ) && $conditions ['to']) {
			$begin_time = strtotime ( $conditions ['from'] );
			$end_time = strtotime ( $conditions ['to'] . '23:59:59' );
			$this->db->where ( "record.create_time between {$begin_time} and  {$end_time}" );
		}
		$this->db->order_by ( "record.id", 'desc' );
		if (isset ( $conditions ['page_size'] ) && isset ( $conditions ['offset'] )) {
			$this->db->limit ( $conditions ['page_size'], $conditions ['offset'] );
		}
	}
	public function get_user($user_id){
		$this->db->select ( 'user.id,user.user_name,user.nick_name,user.open_id,user.is_subscribe,user.mobile' );
		$this->db->from ( 'user' );
		$this->db->where('user.id',$user_id);
		return $this->db->get()->row_array();
	}
}
