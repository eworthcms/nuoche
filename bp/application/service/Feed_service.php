<?php
class Feed_service extends MY_Service {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	// 用户列表
	public function feed_list($condition) {
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		return $result;
	}
	
	public function feed_count() {
		$this->make_condition($condition);
		$result=$this->db->get()->result_array();
		return count($result);
	}
	
	private function make_condition($conditions) {
		$this->db->select ( 'user.open_id,user.user_name,user.nick_name,feed.title,feed.content,feed.create_time,feed.status,feed.user_id' );
		$this->db->from ( 'feed' );
		$this->db->join ( 'user', 'user.id=feed.user_id' );
		if (isset ( $conditions ['open_id'] ) && $conditions ['open_id']) {
			$this->db->like ( 'user.open_id', $conditions ['open_id'] );
		}
		if (isset ( $conditions ['content'] ) && $conditions ['content']) {
			$this->db->like ( 'feed.content', $conditions ['content'] );
		}
		if (isset ( $conditions ['begin_time'] ) && $conditions ['begin_time'] && isset ( $conditions ['end_time'] ) && $conditions ['end_time']) {
			$begin_time = strtotime ( $conditions ['begin_time'] );
			$end_time = strtotime ( $conditions ['end_time'] . '23:59:59' );
			$this->db->where ( "feed.create_time between {$begin_time} and  {$end_time}" );
		}
		$this->db->order_by ( "feed.create_time", 'desc' );
		if (isset ( $conditions ['page_size'] ) && isset ( $conditions ['offset'] )) {
			$this->db->limit ( $conditions ['page_size'], $conditions ['offset'] );
		}
	}
}
