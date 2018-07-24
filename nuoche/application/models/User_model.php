<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * 查询单条数据
    */
    public function get_row($select,$where='',$post=''){
        $this->db->select("$select");
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($post)){
            if(!empty($post['user_name'])){
                $this->db->where('user_name',$post['user_name']);
            }
            if(!empty($post['password'])){
                $this->db->where('password',md5($post['password']));
            }
            if (!empty($post['mobile'])){
                $this->db->where('mobile',$post['mobile']);
            }
        }
        $query = $this->db->get('user');
        //echo $this->db->last_query();die;
        return $query->row_array();
    }
    /**
	* 添加方法
    */
    public function add($user){
    	return $this->db->insert('user',$user);//新添加管理员
    }
    
    
    /**
     * 用户账户扣费
     * @param $user_id int 用户ID
     * @param $cost int 金额（单位：元）
     */
    public function charge($user_id, $cost)
    {
    	$this->db->where(array('id'=>$user_id));
    	$this->db->set('money',"money - $cost",FALSE);
    	$this->db->set('consume_money',"consume_money + $cost",FALSE);
    	$this->db->update('user');
    }
   
}
