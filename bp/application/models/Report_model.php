<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function gen_report(){
    	$this->db->select("totals,today_totals");
    	$report=$this->db->get('report')->row_array();
    	$this->load->helper('common');
    	$secret=mt_rand(1,3);
    	$data=array(
    		'totals'=>intval($secret),
    		'today_totals'=>$secret	
    	);
    	if (is_array($report) && count($report)){
    		$totals=intval($report['totals']+$secret);
    		$today_totals=$report['today_totals'];
    		if (date('H:i')=='00:01'){
    			$today_totals=0;
    		}
    		$today_totals=intval($today_totals+$secret);
    		$data['totals']=intval($totals);
    		$data['today_totals']=$today_totals;
    		$res = $this->update('report', $data, array());
    	}
    }
}
