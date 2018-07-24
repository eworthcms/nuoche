<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cars_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function save_car($data,$where){
    	$res = $this->update('cars', $data, $where);
        return $res;
    }
}
