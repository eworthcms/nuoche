<?php

class City_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
    	if(isset($conditions['province_id']) && $conditions['province_id']>0)
        {
            $province_id=intval($conditions['province_id']);
            if ($province_id==2){
                $province_id=52;
            }elseif($province_id==27){
                $province_id=343;
            }elseif($province_id==25){
                $province_id=321;
            }elseif($province_id==32){
                $province_id=394;
            }
            $this->db->where('parent_id',$province_id);
        }else{
        	$this->db->where('parent_id',1);
        }
    }
}
