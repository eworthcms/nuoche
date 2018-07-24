<?php

class User_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('common');
        $this->load->service('city_service');
    }
    /********************************************************************************************/
    /********************************************************************************************/
    /****************************************用户资料*********************************************/
    /**
	 * 根据openid获取用户信息
	 */
    public function find_user($openid)
    {
        $where = array('open_id' =>$openid);
       	$query = $this->db->get_where('user',$where)->row_array();
       	return $query;
    }
    /**
     * 根据id获取用户信息
     */
    public function get_user($id)
    {
        $where = array('id' =>$id);
        $query = $this->db->get_where('user',$where)->row_array();
        return $query;
    }
	/**
	 * 添加用户资料
	 */
	public function add_user($user_info)
	{
		$data = array(
	        'open_id' => $user_info['openid'],
	        'user_name' => '',
            'nick_name'=>$user_info['nickname'],
            'province'=>$user_info['province'],
            'city'=>$user_info['city'],
            'is_subscribe'=>$user_info['subscribe'],
	    	'subscribe_time'=>$user_info['subscribe_time'],
	    	'sex'=>$user_info['sex'],
	    	'country'=>$user_info['country'],
	    	'headimgurl' => $user_info['headimgurl'],
	        'create_time' => time(),
	    	'modify_time' => time(),
	    );
	    $this->db->insert('user',$data);
	}
    public function upd_user($user_info)
    {
        $this->input->ip_address();
        $data = array(
            'open_id' => $user_info['openid'],
            'user_name' => '',
            'nick_name'=>$user_info['nickname'],
            'province'=>$user_info['province'],
            'city'=>$user_info['city'],
            'is_subscribe'=>$user_info['subscribe'],
            'subscribe_time'=>$user_info['subscribe_time'],
            'sex'=>$user_info['sex'],
            'country'=>$user_info['country'],
            'headimgurl' => $user_info['headimgurl'],
            'modify_time' => time()
        );
        $this->db->where('open_id',$user_info['openid']);
        return $this->db->update('user',$data);
    }
    /**
    *   修改用户地理位置
    */
    public function upd_location($data)
    {
        $region = local_place($data['Latitude'],$data['Longitude']);
        $log_name='region.log';
        $this->log_service->put_log($data,$log_name); //记录日志
        $user_info = array(
            'lat' => $data['Latitude'], 
            'lng' => $data['Longitude'],
            'province' => $region['province'],
            'city' => $region['city']
        );
        $this->db->where('open_id',$data['openid']);
        return $this->db->update('user',$user_info);
    }

    /********************************************************************************************/
    /********************************************************************************************/
    /***************************************用户二维码********************************************/
    /**
    *   上报用户二维码
    */
    public function add_qrcode($qrcodeval)
    {
        return $this->db->insert('qrcode',$qrcodeval);
    }
    /**
     * 用户获取二维码信息
     */
	public function get_qrcode_info($user_id)
    {
        $this->db->where('user_id',$user_id);
        return $this->db->get('qrcode')->row_array();
    }
   
    /**
     * 根据二维码随机数获取手机号
     */
    public function get_user_info($no)
    {
        $this->db->where('no',$no);
        $qrinfo = $this->db->get('qrcode')->row_array();
        $this->db->where('id',$qrinfo['user_id']);
        return $this->db->get('user')->row_array();
    }

    /********************************************************************************************/
    /********************************************************************************************/
    /****************************************用户留言*********************************************/
    /**
     * 上报留言
     */
    public function add_feed($data){
        $feed = array(
            'user_id' => $data['user_id'],
            'content' => $data['content'],
            'create_time' => time(),
            'modify_time' => time(),
        );
        return $this->db->insert('feed',$feed);
    }
    /*********************************************************************************************/
    /*********************************************************************************************/
    /****************************************用户车牌照********************************************/
    /**
     * 获取车牌照
     */
    public function find_cars($id){
       $this->db->where('user_id',$id);
       return $this->db->get('cars')->row_array();
    }
}
