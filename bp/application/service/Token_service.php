<?php

class Token_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->service('log_service');
    }
    /**
     * 获取access_token
     * 40001
     */
    public function find_token()
    {
        $info = $this->db->get('wx_app')->row_array();
        $isseturl = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$info['access_token'];
        $request = json_decode(file_get_contents($isseturl),true);
       
        if($info['expire_time'] > time() && !isset($request['errcode'])) {
                return $info['access_token'];
        } 
        $access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config_item('appid')."&secret=".config_item('secret');
        $access_token_val = json_decode(file_get_contents($access_token_url),true);
        $data = array(
                'access_token' => $access_token_val['access_token'],
                'expire_in' => 7200,
                'modify_time' => time(),
                'expire_time' => time()+7200
        );
        if(empty($info)) {
            $this->db->insert('wx_app',$data);
        } else {
            $this->db->where('id',1);
            $this->db->update('wx_app',$data);
        }
        return $access_token_val['access_token'];
    }
}
