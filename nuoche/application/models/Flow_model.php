<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author Administrator
 * @desc 流水
 */
class Flow_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    /**
	 * 根据时间区间获取消费金额
	 */
    public function get_flow_num($select,$id,$start_time,$end_time)
    {
    		$this->db->select($select);

    		if(!empty($start_time)){//开始时间存在
                $this->db->where("create_time >",$start_time);
            }
            if(!empty($end_time)){//结束时间存在
                $this->db->where("create_time <",$end_time);
            } 
            if(!empty($id)){//广告主id存在
                $this->db->where("user_id",$id);
            }
            $this->db->where("type",1);//支出状态
            $this->db->where("status",1);//已经生效
          	$query=$this->db->get('flow');
            //echo $this->db->last_query();die;
          	return $query->result_array();
    }
}
