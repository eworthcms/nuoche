<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author Administrator
 * @desc 工单处理
 */
class Work_order_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
	* 添加方法
    */
    public function add($data){
	    return $this->db->insert('work_order',$data);
    }
    /**
    * 查询多条方法
    */
    public function get_all($select,$where,$post='',$where_in='',$limit='',$per_page='',$join=''){
        if(!empty($post)){//如果筛选条件存在
            if(!empty($post['sms_name'])){//模糊名称存在
                $this->db->like('sms_template.name', $post['sms_name'], 'both'); 
            }
        }
        if(!empty($join)){
            $this->db->join($join['table'],$join['on']);
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($where_in)){
            $this->db->where($where_in);
        }
        if(!empty($per_page)){
            $this->db->limit($per_page,$limit);
        }
        $this->db->order_by('work_order.create_time','DESC');
        $this->db->select("$select");
        $query = $this->db->get('work_order');
        return $query->result_array();
    }
    /**
    * 查询单条方法
    */
    public function work_info($id){
    	$this->db->where('id',$id);
    	$result=$this->db->get('work_order')->row_array();
    	return $result;
    	
    }
}
