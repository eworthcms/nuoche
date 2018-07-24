<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wechat extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->service('user_service');
        $this->load->service('qrcode_service');
        $this->load->service('token_service');
        $this->load->service('log_service');
        $this->load->service('telephone_record_service');
    }
    /**
     ** 微信通讯地址
     *
     * LOCATION 经纬度
     * SCAN 扫描事件
     * unsubscribe 取消关注事件
     * TEMPLATESENDJOBFINISH 发送模板消息
     */
    public function wechat_service(){

        $xml = file_get_contents("php://input");//接受微信转发消息
        $data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);//转换成数组
        $log_name = '---return_xml.log';
        $this->log_service->put_log($data,$log_name);

        $access_token = $this->token_service->find_token();
        
        if($data && is_array($data)) {
            //判断事件
            if($data['MsgType'] == 'event')
            {   
                $FromUserName = $data['FromUserName'];//关注用户的openid
                $action = '';
                //判断是关注事件
                if($data['Event'] == 'subscribe')
                {
                    if($data['EventKey']) { //判断是通过扫描关注的
                        $lenth = strpos($data['EventKey'], '_');
                        $no = substr($data['EventKey'], $lenth+1);
                        $action = $this->qrcode_service->isset_binding($no,$FromUserName);//检测是否地推二维码 判断是否进行临时绑定
                        $content['openid'] = $FromUserName;
                        $content['service'] = 'subscribe';
                        $content['action'] = $action;
                        $log_name = '--subscribe.log';
                        if($action=='nuoche') {
                            $this->nuoche($no,$access_token,$FromUserName);
                        }else{
                            $this->guanzhu($access_token,$FromUserName);
                        }
                    } else {
                        $this->guanzhu($access_token,$FromUserName);
                    }
                } else if($data['Event'] == 'SCAN') { //扫描事件
                    $no = $data['EventKey'];
                    $ToUserName = $data['ToUserName'];
                    $action = $this->qrcode_service->isset_binding($no,$FromUserName);//检测是否地推二维码 判断是否绑定当前用户
                    $content['openid'] = $FromUserName;
                    $content['service'] = 'SCAN';
                    $content['action'] = $action;
                    $log_name = '--SCAN.log';
                    if($action=='nuoche'){
                        $this->nuoche($no,$access_token,$FromUserName);
                    } else if($action=='binding'){
                        $this->binding($no,$access_token,$FromUserName);
                    } else if($action=='no_binding'){
                        $this->no_binding($ToUserName,$FromUserName);
                    }
                } else if($data['Event'] == 'LOCATION') {
                    $user_info = $this->user_service->find_user($FromUserName);
                    //if($user_info['lng']==0 && $user_info['lat']==0){
                    $location['openid'] = $FromUserName;
                    $location['Latitude'] = $data['Latitude'];//纬度
                    $location['Longitude'] = $data['Longitude'];//经度
                    $this->user_service->upd_location($location);
                    //}
                    
                }
                $this->log_service->put_log($content,$log_name); //记录日志
            }
                
        }
    }
    /**
     * 关注提醒消息
     */
    private function guanzhu($access_token,$FromUserName){
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
        //获取用户基本信息
        $user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$FromUserName."&lang=zh_CN";
        $user_info = json_decode(file_get_contents($user_info_url),true);

        $reuser=$this->user_service->find_user($FromUserName);
        $ip = $this->input->ip_address();
        //当前用户信息不存在记录用户信息
        if(empty($reuser)) { 
            $this->user_service->add_user($user_info,$ip);
        }
        $post['touser'] = $FromUserName;
        $post['template_id'] = config_item('guanzhu_template_id');
        $post['url'] = base_url();
        $post['data']['first'] = array(
            'value'=>'恭喜关注成功，点击消息领取属于你的专属挪车二维码。',
            'color'=>'#000'
        );
        $post['data']['keyword1'] = array(
            'value'=>$user_info['nickname'],
            'color'=>'#000'
        );
        $post['data']['keyword2'] = array(
            'value'=>date('Y-m-d H:i:s',time()),
            'color'=>'#000'
        );
        $post['data']['remark'] = array();
        $post = json_encode($post);
        $result = $this->post2($url,$post);
        $content['openid'] = $FromUserName;
        $content['service'] = 'message_template';
        $content['result'] = $result;
        $content['post'] = json_decode($post,true);
        $log_name = '--message_template.log';
        $this->log_service->put_log($content,$log_name);
    }
    /**
     * 挪车提醒消息
     */
    private function nuoche($no,$access_token,$FromUserName){
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;

        $post['touser'] = $FromUserName;
        $post['template_id'] = config_item('nuoche_template_id');
        $post['url'] = base_url().'/user/movethecar?no='.$no.'&openid='.$FromUserName;
        $post['data']['first'] = array();
        $post['data']['keyword1'] = array(
            'value'=>'挪车码码',
            'color'=>'#000'
        );
        $post['data']['keyword2'] = array(
            'value'=>'临时停靠，请多关照。点击详情，联系车主尽快挪车。',
            'color'=>'#000'
        );
        $post['data']['remark'] = array();
        $post = json_encode($post);
        $result = $this->post2($url,$post);

        $to_user = $this->user_service->get_user_info($no);
        $from_user = $this->user_service->find_user($FromUserName);
        $this->telephone_record_service->add_tele($from_user['id'],$to_user['id']);
        //记录日志
        $content['openid'] = $FromUserName;
        $content['service'] = 'message_template';
        $content['result'] = $result;
        $content['post'] = json_decode($post,true);
        $log_name = '--message_template.log';
        $this->log_service->put_log($content,$log_name);
    }
    /**
     * 绑定提醒消息
     */
    private function binding($no,$access_token,$FromUserName){
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;

        $post['touser'] = $FromUserName;
        $post['template_id'] = config_item('binding_template_id');
        $post['url'] = base_url();
        $post['data']['first'] = array(
            'value'=>'您好，正在绑定二维码',
            'color'=>'#000'
        );
        $post['data']['keyword1'] = array(
            'value'=>'二维码编号'.$no,
            'color'=>'#000'
        );
        $post['data']['keyword2'] = array(
            'value'=>date("Y年m月d日 H点i分",time()),
            'color'=>'#000'
        );
        $post['data']['remark'] = array(
            'value'=>'点击消息进入绑定页面输入手机号完成绑定。',
            'color'=>'#000'
        );
        $post = json_encode($post);
        $result = $this->post2($url,$post);
        $this->telephone_record_service->add_tele($no,$FromUserName);
        //记录日志
        $content['openid'] = $FromUserName;
        $content['service'] = 'message_template';
        $content['result'] = $result;
        $content['post'] = json_decode($post,true);
        $log_name = '--message_template.log';
        $this->log_service->put_log($content,$log_name);
    }
    /**
     * 重复绑定提示
     */
    public function no_binding($ToUserName,$FromUserName) {
            $Content = '您已经拥有二维码，请不要重复绑定。';
            //回复文本信息
            $textTpl ='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content></xml>';
            $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, time(), 'text', $Content);  
            $log_name = '--xmlceshi.log';
            $this->log_service->put_log($resultStr,$log_name);
            echo $resultStr;
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