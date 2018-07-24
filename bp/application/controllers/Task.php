<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!is_cli())
{
    exit('无权访问');
}

/**
 * 定时任务脚本，初始化及维护缓存层数据
 */
class Task extends CI_Controller {
	
	
	/**
	 * 统计数据，每小时执行一次
	 */
	public function create_report() {
		print date ( 'Y-m-d H:i:s' ) . " nuoche create_report start\n";
		$this->load->model ( 'report_model' );
		$this->report_model->gen_report();
		print date ( 'Y-m-d H:i:s' ) . " nuoche create_report end\n";
		
	}
}
