<?php

class Telephone_record_service extends MY_Service {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->service('user_service');
        $this->load->service('log_service');
    }
    /**
	 * 记录挪车通话记录
	 */
    public function add_tele($from_user_id,$to_user_id,$ip='')
    {
        //电话记录
        $send = $this->user_service->get_user($from_user_id);//呼叫人
        $call_log=$this->find_record($send['id'],$to_user_id);
        $cars = $this->user_service->find_cars($to_user_id);//被呼叫人车辆信息
        $cars['id']=isset($cars['id'])?$cars['id']:0;
        if($send['lat']>0 && $send['lng']>0) {
            $region=local_place($send['lat'],$send['lng'],'');
        } else if($ip!=''){
            $ip=$this->input->ip_address();
            $region=local_place("","",$ip);
        }else{
            $region = array(
                'province' => '' ,
                'city' => '' ,
                'position' => '' 
             );
        }
        if(!empty($call_log)) {     //存在修改地理位置 拨打次数+1
            $data['province'] = $region['province'];
            $data['city'] = $region['city'];
            $data['position'] = $region['position'];
            $data['modify_time'] = time();
            $data['clicks'] = $call_log['clicks']+1;
            $this->db->where('from_user_id',$send['id']);
            $this->db->where('to_user_id',$to_user_id);
            return $this->db->update('record',$data);
        } else {    //不存在记录
            $data['from_user_id'] = $send['id'];
            $data['to_user_id'] =  $to_user_id;
            $data['car_id'] = $cars['id'];
            $data['province'] = $region['province'];
            $data['city'] = $region['city'];
            $data['lng'] = $send['lng'];
            $data['lat'] = $send['lat'];
            $data['position'] = $region['position'];
            $data['times'] = 0;
            $data['status'] = 0;
            $data['create_time'] = time();
            $data['modify_time'] = time();
            $data['clicks'] = 0;
            return $this->db->insert('record',$data);
        }
         //记录日志
        $content['openid'] = $send['open_id'];
        $content['service'] = 'telephone_record';
        $content['result'] = $result;
        $content['data'] = json_decode($data,true);
        $log_name = '--telephone_record.log';
        $this->log_service->put_log($data,$log_name);
    }
    /**
     * 获取通话记录
     */
    public function find_record($from_user_id,$to_user_id){
        $this->db->where('from_user_id',$from_user_id);
        $this->db->where('to_user_id',$to_user_id);
        return $this->db->get('record')->row_array();
    }
   
}
