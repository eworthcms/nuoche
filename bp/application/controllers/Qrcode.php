<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Qrcode extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->database ();
	}
	
	public function index(){
		echo date('H:i',1512057600);exit;
		echo '404 页面已丢';
	}
	
	
	/**
	 *  生成二维码
	 */
	public function gen(){
		$params=$this->input->get();
		$source=intval($params['source']);
		$nums=intval($params['nums'])>0?intval($params['nums']):2;
		$source=2;
		$this->load->service('token_service');
		$token=$this->token_service->find_token();
		$this->load->helper('common');
		$data=array();
		if($nums>0){
			for ($i=1;$i<=$nums;$i++){
				$rand =gen_secret(7);
				$ticket_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
				$ticket_val = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": ' . $rand . '}}}';
				$ticket=$this->post2($ticket_url,$ticket_val);
				$ticket=json_decode($ticket,true);
				$qrcodeurl='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket['ticket']);
				if (!empty($rand) && !empty($qrcodeurl)){
					$local_qrcode=ROOT_PATH."uploads/qrcode/".$rand.'.jpg';
					if (!file_exists($local_qrcode)){
						gen_qrcode($qrcodeurl, $rand);
					}
						
				}
				$data[] = array (
						'user_id'=>0,
						'source'=>$source,
						'no'=>$rand,
						'qrcode'=>$qrcodeurl,
						'status'=>0,
						'create_time'=>time(),
						'modify_time'=>time(),
				);
				
			}
		}
		$this->load->service('user_service');
		$res=$this->user_service->add_qrcodes($data);
		if ($res){
			echo '成功生成地推二维码'.$nums.'个';
		}else{
			echo '生成地推二维码失败';
		}
		
	}
	
	/**
	 * 二维码下载
	 */
	public function down(){
		$this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $resultPHPExcel = new PHPExcel();
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '二维码地址');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '二维码编码');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '来源');
        $resultPHPExcel->getActiveSheet()->setCellValue('D1', '创建时间');
        $i = 2;
		$this->load->service('qrcode_service');
		$condition=array(
			'user_id'=>0,
			'source'=>2,
			'status'=>0	
		);
		$data = $this->qrcode_service->qrcode_list($condition);
		$this->load->helper('common');
		if(is_array($data) && count($data))
		{
			foreach ($data as $key=>$val)
			{
				$no=$val['no'];
				$qrcode=$val['qrcode'];
				$orgin_qrcode_path="uploads/qrcode/".$no.'.jpg';
				$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,base_url().$orgin_qrcode_path);
				$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $no);
				$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, '地推');
				$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, date('Y-m-d H:i:s',$val['create_time']));
			    $i ++;
			}
		}
		$outputFileName="ditui_qrcode_".date('Y-m-d H:i:s').".xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
	    header('Content-Disposition:inline;filename="'.$outputFileName.'"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
	}
	
	public static function post2($url, $post_data = '', $timeout = 5){//curl
		if(is_array($post_data))
			$post_data = http_build_query($post_data);
	
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_POST, 1);
	
			if($post_data != ''){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			}
	
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
					'X-ptype: like me'
			) );
	
			curl_setopt($ch, CURLOPT_HEADER, false);
			$file_contents = curl_exec($ch);
			curl_close($ch);
	
			return $file_contents;
	
	}
	
	
}
