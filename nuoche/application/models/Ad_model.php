<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author Administrator
 * @desc 广告营销活动
 */
class Ad_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    /**
	* 添加方法
    */
    public function add($data){
    	return $this->db->insert('ad',$data);//新添加管理员
    }
    /**
    * 查询多条方法
    */
    public function get_all($select,$where,$post='',$where_in='',$limit='',$per_page=''){
        if(!empty($post)){//如果筛选条件存在
            if(!empty($post['start_time'])){//开始时间存在
                $start_time=strtotime($post['start_time']);
                $this->db->where("create_time >",$start_time);
            }
            if(!empty($post['end_time'])){//结束时间存在
                $end_time=strtotime($post['end_time']);
                $this->db->where("create_time <",$end_time);
            }
            if(!empty($post['status'])){//状态存在
                $this->db->where('status',$post['status']);
            }
            if(!empty($post['title'])){//模糊名称存在
                $this->db->like('title', $post['title'], 'both'); 
            }
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
        $this->db->order_by('id','DESC');
        $this->db->select("$select");
        $query = $this->db->get('ad');
       // echo $this->db->last_query();
        return $query->result_array();
    }
    /**
    * 查询单条方法
    */
     public function get_row($select,$where,$post){
         if(!empty($where)){
            $this->db->where($where);
            $this->db->select("$select");
            $query = $this->db->get('ad');
            return $query->row_array();
        }
     }
    /**
    * 修改方法
    */
    public function save($where,$data){
        $this->db->where($where);
        return $this->db->update('ad',$data);
    }
}
