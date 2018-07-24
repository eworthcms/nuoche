<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['pagination_common_list'] = array(
	'per_page' => 9,
	'phone_per_page' => 50,
	'page_query_string' => false,
	'use_page_numbers' => true,
	'query_string_segment' => 'page',
	'num_links' => 3,
	#'first_tag_open' => '<a href="javascript:;" class="laypage_prev">', //第一个链接的起始标签
	#'first_tag_close' => '</a>',
	#'full_tag_open' => '<div class="pageLink mgT20 mgAuto">',  //包围分页的标签
	#'full_tag_close' => '</div>',
	'prev_link' => '上页',
	'next_link' => '下页',
	'cur_tag_open'=> "<span class='num first-num'>",
	'cur_tag_close'=>"</span>",
	'num_tag_open'=> "<span class='num'>",
	'num_tag_close'=> "</span>",
	'last_tag_open'=>"<span class='num lastPage'>",
	'last_tag_close'=>"</span>",
	'first_tag_open'=>"<span class='num previousPage'>",
	'first_tag_close'=>"</span>",
	'next_tag_open'=>"<span class='num previousPage'>",
	'next_tag_close'=>"</span>",
	'prev_tag_open'=>"<span class='num previousPage'>",
	'prev_tag_close'=>"</span>",
	'last_link' => '末页',
	'first_link'=> '首页'
);

