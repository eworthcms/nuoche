<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Administrator
 * @desc 短信验证码
 */
class Sms_code_model extends MY_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	
}