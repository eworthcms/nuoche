<?php
/**
 * 
 * @author luoya
 * 广告主注册获取验证码
 */
class Qrcode_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->service('user_service');
        $this->load->service('token_service');
        $this->load->service('log_service');
    }
    /**
     * 检测是否地推二维码 判断是否绑定当前用户
     */
    public function isset_binding($no,$FromUserName){

        $this->db->where('open_id',$FromUserName);
        $user=$this->db->get('user')->row_array();
        //当前用户信息不存在记录用户信息
        if(empty($user)) { 
            $access_token = $this->token_service->find_token();
            $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
            //获取用户基本信息
            $user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$FromUserName."&lang=zh_CN";
            $user_info = json_decode(file_get_contents($user_info_url),true);
            $this->user_service->add_user($user_info);
        }
        
        $this->db->where('no',$no);
        $qrcode=$this->db->get('qrcode')->row_array();
        if($qrcode['user_id'] == 0) { //当前二维码未被绑定

            $this->db->where('open_id',$FromUserName);
            $user = $this->db->get('user')->row_array();//获取用户主键
            $this->db->where('user_id',$user['id']);
            $user_qrcode = $this->db->get('qrcode')->row_array();//用户是否绑定过二维码
            if(empty($user_qrcode)) { //当前用户没有绑定过 扫描的二维码也未绑定
                $linsqrcode['no'] = $no;
                $linsqrcode['user_id'] = $user['id'];
                $linsqrcode['create_time'] = time();
                $linsqrcode['modify_time'] = time();
                $linsqrcode['status'] = 0;
                $this->db->insert('temporary_qrcode',$linsqrcode);
                return 'binding';
            } else {
                return 'no_binding';
            }

        } else {
            return 'nuoche';
        }
    }
    /**
     * 查询该用户是否扫描未绑定二维码
     * 如果有则绑定该二维码，针对地推用户
     */
    public function find_linsqrcode($user_id){
        $this->db->where('user_id',$user_id);
        $this->db->where('status',0);
        $this->db->order_by('id','desc');
        $this->db->limit(1,0);
        return  $this->db->get('temporary_qrcode')->row_array();
    }
    /**
     * 修改地推二维码临时表
     * 如果有用户绑定该临时二维码则全部临时绑定信息失效
     */
    public function upd_linsqecode($no){
        $this->db->where('no',$no);
        $linsqrcode['status'] = 1;
        $this->db->update('temporary_qrcode',$linsqrcode);
    }
    
}