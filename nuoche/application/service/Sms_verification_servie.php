<?php
/**
 * 
 * @author luoya
 * 广告主注册获取验证码
 */
class Sms_verification_servie extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /**
 	*	短信接口配置文件
 	*/
 	private function config(){
 		return $config=array(
 			'username'=>'clybykj',
 			'password'=>'m5bpy23u'
 			);
 	}
    /**
 	*	获取短信验证码
 	*/
    public function get_sms_code($mobile){
    	$str=rand(100000,600000);//六位随机数
    	$content="【北京拨云科技】您的验证码为".$str."。如非本人操作，请忽略本短信,5分钟内输入有效。";//短信内容

    	$config=$this->config();//获取配置
    	$username=$config['username'];
    	$password=md5($username.md5($config['password']));
    	$url="http://sms-cly.cn/smsSend.do?username=$username&password=$password&mobile=$mobile&content=$content";
    	
        $this->load->library('session');//实例化session
        $this->session->set_tempdata('str', $str, 300);//设置session存储时间为五分钟
        return $this->curl_get($url);

    	
    }
    public function curl_get($url){
    	$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HEADER, 1);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $data = curl_exec($curl);
	    curl_close($curl);
	    echo 1;
    }
    
}