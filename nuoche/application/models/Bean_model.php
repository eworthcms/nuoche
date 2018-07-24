<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Bean_model extends CI_Model{
	
	//初始化类库
	public function __construct(){
		parent::__construct();
		$this->load->library('Bean_init',$this->config->config['bean'],'lib_bean');
			
	}
	
    //广告曝光量队列
	public  function impressionAds($body){
		//实例化beanstalk
		$beanstalk = $this->lib_bean;
		if (!$beanstalk->connect()) {
			exit(current($beanstalk->errors()));
		}
		//选择使用的tube
		$beanstalk->useTube('ead_impressions_ads');
		//往tube中增加数据
		$put = $beanstalk->put(
				23, // 任务的优先级.
				0, // 不等待直接放到ready队列中.
				60, // 处理任务的时间.
				$body // 任务内容
				);
		if (!$put) {
			return false;
		}
		$message=" impressionAds : result {$body}";
		log_message('info', $message);
		$beanstalk->disconnect();
	}
	
	//处理广告曝光的消息队列
	public  function handleImpression(){
		//实例化beanstalk
		$beanstalk =$this->lib_bean;
		if (!$beanstalk->connect()) {
			exit(current($beanstalk->errors()));
		}
		$beanstalk->useTube('ead_impressions_ads');
		//设置要监听的tube
		$beanstalk->watch('ead_impressions_ads');
		//取消对默认tube的监听，可以省略
		$beanstalk->ignore('default');
		while($job = $beanstalk->reserve(2)){//这里我写了单个任务只执行2秒。防止超时。本地测试的时候 没写超时会一直执行下去，哪怕队列里面已经没有任务
			//处理任务
			$jobId=$job['id'];
			$result=$job['body'];
			if ($jobId>0){
				if ($result) {
					//曝光的逻辑
					$this->load->model('ad_logs_model');
					$data=json_decode($result,true);
					$this->ad_logs_model->insert('epr_logs',$data);
					//删除任务
					$beanstalk->delete($jobId);
					$message="deal impression  success : jobId {$jobId} result {$result}";
				}else{
					//休眠任务
					$beanstalk->bury($jobId,'');
					$message="deal impression  sleep : jobId {$jobId} result {$result}";
				}		
			}
			if (!empty($message)){
				log_message('info', $message);
			}
		
		}
		$beanstalk->disconnect ();
	}
	
	//广告点击统计消息队列
	public  function clickAds($body){
		//实例化beanstalk
		$beanstalk = $this->lib_bean;
		if (!$beanstalk->connect()) {
			exit(current($beanstalk->errors()));
		}
		//选择使用的tube
		$beanstalk->useTube('ead_click_ads');
		//往tube中增加数据
		$put = $beanstalk->put(
				23, // 任务的优先级.
				0, // 不等待直接放到ready队列中.
				60, // 处理任务的时间.
				$body // 任务内容
				);
		if (!$put) {
			return false;
		}
		$message=" click : result {$body}";
		log_message('info', $message);
		$beanstalk->disconnect();
	}
	//处理消息
	public  function handleClick(){
		//实例化beanstalk
		$beanstalk =$this->lib_bean;
		if (!$beanstalk->connect()) {
			exit(current($beanstalk->errors()));
		}
		$beanstalk->useTube('ead_click_ads');
		//设置要监听的tube
		$beanstalk->watch('ead_click_ads');
		//取消对默认tube的监听，可以省略
		$beanstalk->ignore('default');
		while($job = $beanstalk->reserve(2)){//这里我写了单个任务只执行2秒。防止超时。本地测试的时候 没写超时会一直执行下去，哪怕队列里面已经没有任务
			//处理任务
			$jobId=$job['id'];
			$result=$job['body'];
			if ($jobId>0){
				if ($result) {
					//处理点击日志的逻辑
					$data=json_decode($result,true);
					$this->load->model('ad_logs_model');
					$this->ad_logs_model->insert('click_logs',$data);
					//删除任务
					$beanstalk->delete($job['id']);
					$message="deal click success:jobId {$jobId} result {$result}";
				}else{
					//休眠任务
					$beanstalk->bury($job['id'],'');
					$message="deal click sleep:jobId {$jobId} result {$result}";
				}	
			}
			if (!empty($message)){
				log_message('info', $message);
			}
			
			
		}
		$beanstalk->disconnect ();
	}
}