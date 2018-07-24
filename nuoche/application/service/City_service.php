<?php

class City_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('city_model');
        $this->load->service('log_service');
    }
    
    public function city_list($condition)
    {
        $this->make_condition($condition);
        $res=$this->db->get('region')->result_array();
        return $res;
    }
	//获取省份和城市名称
    public function city_info($cityId){
    	$this->db->select('region_id,region_name');
    	$this->db->where('region_id',$cityId);
    	$res=$this->db->get('region')->row_array();
    	return $res;
    }
    //根据城市名称
    public function city_name_info($cityName){
    	$this->db->select('region_id,region_name');
    	$this->db->like('region_name',$cityName);
    	$res=$this->db->get('region')->row_array();
    	return $res;
    } 
    private function make_condition($conditions)
    {
    	if(isset($conditions['region_id']) && $conditions['region_id']>0)
        {
            $this->db->where('region_id',$conditions['region_id']);
            $this->db->where('region_id >',0);
        }else{
        	$this->db->where('region_id>',0);
            if(isset($conditions['parent_id']))
            {
                $this->db->where('parent_id',$conditions['parent_id']);
            }else{
                $this->db->where('parent_id',1);//默认为省份
            }
        }
    }
}
