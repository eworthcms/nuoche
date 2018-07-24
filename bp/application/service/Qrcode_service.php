<?php
class Qrcode_service extends MY_Service {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}

	
	public function qrcode_list($condition){
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
	
	
	private function make_condition($conditions) {
		$this->db->select ( 'qrcode.id,qrcode.user_id,qrcode.source,qrcode.no,qrcode.qrcode,qrcode.status,qrcode.create_time' );
		$this->db->from ( 'qrcode' );
		if (isset ( $conditions ['user_id'] ) && $conditions ['user_id']) {
			$this->db->where( 'qrcode.user_id', $conditions ['user_id'] );
		}
	
		if (isset ( $conditions ['source'] ) && $conditions ['source']) {
			$this->db->like ( 'qrcode.source', $conditions ['source'] );
		}
		
		$this->db->order_by ( "qrcode.create_time", 'desc' );
		if (isset ( $conditions ['page_size'] ) && isset ( $conditions ['offset'] )) {
			$this->db->limit ( $conditions ['page_size'], $conditions ['offset'] );
		}
	}
}
