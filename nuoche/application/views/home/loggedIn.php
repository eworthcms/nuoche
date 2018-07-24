<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>领取挪车码</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<base href="<?php echo base_url(); ?>" />
		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/mycenter.css"/>
	</head>
	<body>
		<div class="bg-box">
			<!--个人中心-->
			<div class="my-center">
				<div class="mycenter-section">
					<img class="portrait-icon" src="<?php echo $user_info['headimgurl'] ?>"/>
					<h6 class="num-title"><?php echo $user_info['mobile'] ?></h6>
					<div class="bind-section">
						<p>您的号码已绑定</p>
						<img class="agree-icon" src="imgs/mycentre/agree-icon.png"/>
					</div>
				</div>
			</div>
			<!--二维码-->
			<div class="erweima-center">
				<img class="erweima" src="<?php echo $qrcode_info['qrcodeurl']; ?>"/>
				<p class="erweima-save">长按保存图片 打印即可使用</p>
			</div>
		</div>
		<script type="text/javascript" src="js/mobileScreen.js"></script>
		<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
	</body>
</html>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?42c9439fb7579fb6bd3e94939b30692e";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

