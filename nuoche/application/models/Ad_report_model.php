<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author Administrator
 * @desc 广告活动报表
 */
class Ad_report_model extends MY_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
    /**
	 * 查询方法
	 */
 	public function get_all($select,$where,$post,$where_in='',$limit='',$per_page='',$join=''){
            if(!empty($post)){
                if(!empty($post['search_name'])){
                     $this->db->like("ad.title", $post['search_name'], 'both');
                }
                if(!empty($post['start_time'])){//开始时间存在
                $this->db->where("ad_report.create_time >=",$post['start_time']);
                }
                if(!empty($post['end_time'])){//结束时间存在
                    $this->db->where("ad_report.create_time <=",$post['end_time']);
                }
                if( isset($post['is_called']) && $post['is_called']!=''){//是否拨打存在
                    $this->db->where("ad_report.is_called =",$post['is_called']);
                } 
                if(!empty($post['clicks'])){//点击次数
                    $this->db->where("ad_report.clicks =",$post['clicks']);
                }
                if(isset($post['province_id']) && $post['province_id']!=0){//省份
                    $this->db->where("ad_report.province_id =",$post['province_id']);
                }
                if(isset($post['city_id']) && $post['city_id']!=0){//城市
                    $this->db->where("ad_report.city_id =",$post['city_id']);
                }
            }
            if(!empty($where)){
                $this->db->where($where);
            }
            if(!empty($where_in)){
            $this->db->where($where_in);
            }
            if(!empty($per_page)){
            $this->db->limit($per_page,$limit);
            }
            $this->db->order_by('ad_report.create_time','DESC');
            $this->db->select($select);
            $this->db->from('ad_report');
            if(!empty($join)){
                $this->db->join($join['table'],$join['keys']);
            }
          	$query=$this->db->get();
            //echo $this->db->last_query();
          	return $query->result_array();
 	}
    /**
     * 修改方法
     */
    public function save($where='',$data=''){
        if(!empty($where)){
            $this->db->where($where);
        }
        return $this->db->update('ad_report',$data);
    }

    //测试网站并发
    //投放数据准确度
}
