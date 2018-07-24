<?php
/**
 * 
 * @author luoya
 * 获取车牌照
 */
class License_plate_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //获取车牌开头
    public function get_license_plate(){
        $this->db->select('code');
        $this->db->distinct('code');
        $this->db->order_by('sort','desc');
        return $this->db->get('plate_num')->result_array();
    }
   
    
}