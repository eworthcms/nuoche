<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Power_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * 查询多条方法
    */
    public function get_all($select,$where,$post='',$where_in='',$limit='',$per_page=''){
        $this->db->select("$select");

        if (!empty($post['search_name'])) {
	    	$this->db->like('name', $post['search_name'], 'both'); 
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
        $query = $this->db->get('power');
        return $query->result_array();
    }
}