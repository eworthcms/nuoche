<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author Administrator
 * @desc 短信模板
 *
 */
class Sms_template_model extends MY_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	/**
    * 查询多条方法
    */
    public function get_all($select,$where,$post='',$where_in='',$limit='',$per_page=''){
        $this->db->select("$select");
        if (!empty($post['search_name'])) {
            $this->db->like('name', $post['search_name'], 'both'); 
        }
        if (isset($post['status']) && $post['status']!="") {
	    	$this->db->where('status',$post['status']); 
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
        $query = $this->db->get('sms_template');
        return $query->result_array();
    }
    /**
    * 查询单条方法
    */
    public function get_row($select,$where){
        $this->db->select($select);
        if(!empty($where)){
            $this->db->where($where);
        }
        $query = $this->db->get('sms_template');
        return $query->row_array();

    }
	/**
	* 添加方法
    */
    public function add($data){
    	return $this->db->insert('sms_template',$data);
    }
    /**
    * 修改方法
    */
    public function save($where,$data){
        if(!empty($where)){
            $this->db->where($where);
        }
        return $this->db->update('sms_template',$data);
    }
}