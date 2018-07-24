<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author Administrator
 * @desc 短信发送记录
 *
 */
class Sms_send_records_model extends MY_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	/**
	* 查询方法
	*/
    public function get_all($select,$str,$start_time,$end_time)
    {
		$this->db->select($select);

		if(!empty($start_time)){//开始时间存在
            $this->db->where("create_time >",$start_time);
        }
        if(!empty($end_time)){//结束时间存在
            $this->db->where("create_time <",$end_time);
        } 
        if(!empty($str)){//广告id存在
            $this->db->where("ad_id in ($str)");
        }
      	$query=$this->db->get('sms_send_records');
      	//echo $this->db->last_query();die;
      	return $query->result_array();
      	//print_r($query->result_array());die;
    }
	
}