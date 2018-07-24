<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo $layout['title'];?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>icon/iconfont.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/common.css"/>
		<link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
		<script src="http://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
	    <script src="<?php echo base_url();?>js/jquery-ui.js"></script>
	    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jqUimenology.css"/>
	    
	</head>
	<body>
		<div id="mark">
			
		</div>
		<!-------------------------左侧导航栏------------------------------->
		<div id="terraceLeft-nav">
			<div id="logoBox">
				<h1>
					<a href=""><img src="<?php echo base_url();?>img/logo.png"/></a>
				</h1>
			</div>
			<ul class="navList">
                <li <?php  if($layout['controller']=='user'  || $layout['controller']==false){echo "class='home-box'";}?>  >
                    <img src="<?php echo base_url();?>img/index.png">
                    <a href="/user/index">用户信息</a>
                </li>
                <li <?php  if($layout['controller']=='record'){echo "class='user-box'";}?>  >
                    <img src="<?php echo base_url();?>img/advertisement.png">
                    <a href="/record/index">挪车记录</a>
                </li>

				<li <?php if($layout['controller']=='feed'){echo "class='advertising-box'";}?> >
					<img src="<?php echo base_url();?>img/mess.png">
					<a href="/feed/index">意见反馈</a>
				</li>
<!--				<li>-->
<!--					<img src="--><?php //echo base_url();?><!--img/data.png">-->
<!--					<a href="/qrcode/gen">生成地推码</a>-->
<!--					-->
<!--				</li>-->
				<li>
					<img src="<?php echo base_url();?>img/tel2.png">
					<a href="/qrcode/down">下载地推码</a>
				</li>
				<li style="display: none;">
					<img src="<?php echo base_url();?>img/data.png">
					<a href="">数据统计</a>
				</li>
				<li style="display: none;">
					<img src="<?php echo base_url();?>img/data.png">
					<a href="">返回信息</a>
				</li>
				<li style="display: none;">
					<img src="<?php echo base_url();?>img/data.png">
					<a href="">账号管理</a>
				</li>
			</ul>
		</div>
		<!---------------------------右侧部分内容--------------------------------->
		<div id="terraceRight-session">
			<div class="terraceRight-title">
				<h5>挪车管理后台</h5>
				<p>您好，欢迎<font color='green'> <?php echo $this->session->userdata('user_name');?></font> 您使用挪车管理后台！</p>
				<div class="exit">
					<img src="<?php echo base_url();?>img/exit.png"/>
					<a href="javascript:logout()">退出</a>
				</div>
			 </div>
			<div class="terraceRight-page">
				<div class="terraceRight-page-top">
					<p><span>.</span><?php echo $page_info['zh_title'];?><span>&nbsp;&nbsp;<?php echo $page_info['en_title'];?></span></p>
				</div>
				 <?php  echo  $content;?>
			<!--地址-->
			<div class="address-box">
				<a href="">Copyright © 2017 北京拔云科技有限公司</a>
			</div>
		</div>
	  <script src="<?php echo base_url(); ?>js/layer/layer.js"></script>
	  <!--分页插件--> 
	  <script src="<?php echo base_url(); ?>js/laypage/laypage.js"></script> 
	  <script src="<?php echo base_url(); ?>js/giaf.select.js"></script> 
		<script>
	  function logout(){
		  layer.msg("你确定要退出系统么？", {
			  time: 0 //不自动关闭
			  ,btn: ['确定', '取消']
			  ,yes: function(index){
				  location.href = '/login/user_logout';
			  }
		  });
	  }
  </script>
	</body>
</html>