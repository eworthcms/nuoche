<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Cache_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'ad_model' );
	}
	
	/**
	 * 把广告主账户余额加载进redis
	 * 
	 * @param s $user_id
	 *        	int 广告主用户ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒
	 */
	public function load_user_money($user_id = 0, $time_out) {
		$key = 'YXB_user_' . $user_id;
		$campaign = $this->ad_model->get_user_by_id ( $user_id );
		$money = $campaign ['money'];
		$this->cache->redis->save ( $key, $money, $time_out );
	}
	
	/**
	 * 把推广计划数据加载进redis
	 * 
	 * @param s $campaign_id
	 *        	int 推广计划ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒
	 */
	public function load_campaign($campaign_id = 0, $time_out) {
		$key = 'YXB_campaign_' . $campaign_id;
		$campaign = $this->ad_model->get_campaign_by_id ( $campaign_id );
		$day_sum = $campaign ['day_sum'];
		if ($day_sum > 0) {
			$this->load->model ( 'ad_logs_model' );
			$used_sum = $this->ad_logs_model->get_used_sum ( array (
					'campaign_id' => $campaign_id 
			) );
			$day_sum = $day_sum - $used_sum;
		} else {
			// 无日限额，设一个较大数值来实现
			$day_sum = 9000000000;
		}
		$this->cache->redis->save ( $key, $day_sum, $time_out );
	}
	
	/**
	 * 把推广组数据加载进redis
	 * 
	 * @param s $adgroup_id
	 *        	int 推广组ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒
	 */
	public function load_adgroup($adgroup_id = 0, $time_out) {
		$key = 'YXB_adgroup_' . $adgroup_id;
		$adgroup = $this->ad_model->get_adgroup_by_id ( $adgroup_id );
		$day_sum = $adgroup ['day_sum'];
		if ($day_sum > 0) {
			$this->load->model ( 'ad_logs_model' );
			$used_sum = $this->ad_logs_model->get_used_sum ( array (
					'adgroup_id' => $adgroup_id 
			) );
			$day_sum = $day_sum - $used_sum;
		} else {
			// 无日限额，设一个较大数值来实现
			$day_sum = 9000000000;
		}
		$this->cache->redis->save ( $key, $day_sum, $time_out );
	}
	
	/**
	 * 把广告信息加载进redis
	 * 
	 * @param s $ad_id
	 *        	int 广告ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒 86400秒==一天
	 */
	public function load_adinfo($ad_id = 0, $time_out = 86400) {
		$key = 'YXB_adinfo_' . $ad_id;
		$adinfo = $this->ad_model->get_adinfo_by_id ( $ad_id );
		if ($adinfo ['size_id'] > 0) {
			$size_info = $this->ad_model->get_size_by_id ( $adinfo ['size_id'] );
			$adinfo ['width'] = $size_info ['width'];
			$adinfo ['height'] = $size_info ['height'];
		}
		$api_content = array ();
		$link = base_url () . 'ad/click/{area_id}/' . $adinfo ['id'];
		$api_content = array (
				'title' => $adinfo ['title'],
				'comment' => $adinfo ['comment'],
				'content' => $adinfo ['type'] == 1 ? $this->config->item ( 'resource_host' ) . $adinfo ['content'] : $adinfo ['content'],
				'link' => $link 
		);
		$adinfo ['api_content'] = json_encode ( $api_content );
		if ($adinfo ['type'] == 1) {
			$adinfo ['web_content'] = '<a href="' . $link . '" target="_blank"><img style="width:auto;height:auto;max-width:100%;max-height:100%;" id="img' . $adinfo ['id'] . '" src="' . $this->config->item ( 'resource_host' ) . $adinfo ['content'] . '"/></a>';
			$adinfo ['ms_content'] = '<a href="' . $link . '" target="_blank"><img style="width:100%;height:auto;max-width:100%;max-height:100%;" src="' . $this->config->item ( 'resource_host' ) . $adinfo ['content'] . '"/></a>';
		} else {
			$adinfo ['content'] = $adinfo ['web_content'] = '<a href="' . $link . '" target="_blank">' . $adinfo ['content'] . '</a>';
		}
		$this->cache->redis->save ( $key, json_encode ( $adinfo ), $time_out );
	}
	
	/**
	 * 把广告位合适的广告id加载进redis，加载100个，10分钟更新一次
	 * 
	 * @param s $area_id
	 *        	int 广告位ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒
	 */
	public function load_area_ads($area_id = 0, $time_out) {
		$key = 'YXB_area_ads_' . $area_id;
		$area_info = $this->ad_model->get_area_by_id ( $area_id );
		if ($area_info) {
			$result = array ();
			// 获取广告位的全部标签
			$area_scene_array = $this->ad_model->get_scene_by_area_id ( $area_id );
			// 随机获取100个广告
			$condition = array (
					'status' => 2,
					'type' => $area_info ['type'],
					'size_id' => $area_info ['size_id'],
					'orderby' => 'rand()',
					'num' => 100 
			);
			$ad_array = $this->ad_model->get_adinfo_list ( $condition );
			// 计算100个广告的匹配权重 权重：场景匹配度高[1-100]，点击金额高[1-100]，{随机权重，随机1到5}
			foreach ( $ad_array as $ad ) {
				$ad_scene_array = $this->ad_model->get_scene_by_ad_id ( $ad ['id'] );
				$scene_weight = count ( array_intersect ( $area_scene_array, $ad_scene_array ) ); // 场景匹配度
				$cost_weight = $ad ['price'] / 10; // 点击金额权重
				$result [] = $ad ['id'] . '|' . $scene_weight . '|' . $cost_weight;
			}
			$this->cache->redis->save ( $key, json_encode ( $result ), $time_out );
		}
	}
	
	/**
	 * 把广告位合适的广告第二高价加载进redis，实际扣款以第二高价+0.1元，10分钟更新一次
	 * 
	 * @param s $area_id
	 *        	int 广告位ID
	 * @param s $size_id
	 *        	int 规格ID
	 * @param s $time_out
	 *        	int 过期时间 单位：秒
	 */
	public function load_area_price($area_id = 0, $size_id = 0, $time_out) {
		$key = 'YXB_area_price_' . $area_id;
		$condition = array (
				'status' => 2,
				'size_id' => $size_id,
				'orderby' => 'price',
				'direction' => 'DESC',
				'num' => 2 
		);
		$adinfo_list = $this->ad_model->get_adinfo_list ( $condition );
		$price = 30; // 默认最低出价5毛
		if (is_array ( $adinfo_list ) && count ( $adinfo_list ) > 1) {
			if (isset ( $adinfo_list [1] ) && isset ( $adinfo_list [1] ['price'] )) {
				if ($adinfo_list [1] ['price'] > $price) {
					$price = $adinfo_list [1] ['price'];
				}
			}
		} else {
			if (isset ( $adinfo_list [0] ) && isset ( $adinfo_list [0] ['price'] )) {
				if ($adinfo_list [0] ['price'] > $price) {
					$price = $adinfo_list [0] ['price'];
				}
			}
		}
		$this->cache->redis->save ( $key, $price, $time_out );
	}
}
