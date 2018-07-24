
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php echo $layout['title'];?></title>
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/common.css"/>
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>js/jqcalendar.js"></script>
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jqUimenology.css"/>
   
  </head>
  <body>
  	<div id="mark">
  	</div>
    <div id="terraceLeft-nav">
      <div id="logoBox">
		    <h1>
		     <a href="<?php echo base_url();?>"><img src="<?php echo base_url(); ?>img/logo.png"/></a >
		    </h1>
  	 </div>
      <ul class="navList">
         <?php foreach ($power as $value) { ?>
                 <li class="<?php echo $value['class']; ?>">
                  <img src="<?php echo base_url($value['img']); ?>"/>
                  <a href="/<?php echo $value['url']; ?>"><?php echo $value['title']; ?></a>
                </li>
          <?php  }  ?>
      </ul>
    </div>
    <div id="terraceRight-session">
      <div class="terraceRight-title">
        <h5>数据营销平台</h5>
        <p>您好，<font color="green"><?php echo $this->session->userdata('user_name'); ?></font>欢迎您使用营销整合平台！</p>
        <div class="exit">
          <img src="<?php echo base_url(); ?>img/exit.png"/>
          <a href="javascript:logout()">退出</a>
        </div>
      </div>
      <?php  echo  $content;?>
    </div> 
     <input type="hidden" id="go" value="">
    <script type="text/javascript" src="<?php echo base_url();?>js/layer/layer.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/common.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/page.js"></script>
  </body>
</html>
<script>
  function logout(){
     if(confirm("确定要关闭系统吗？"))
     {
        location.href = '/login/admin_logout';
     }
  }
</script>