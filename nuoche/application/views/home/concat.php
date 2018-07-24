<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>联系车主</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<base href="<?php echo base_url(); ?>" />
		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/concat.css"/>
		<link rel="stylesheet" type="text/css" href="icon/iconfont.css"/>
	</head>
	<body>
		<div class="bg-box">
			<!--挪车总数量部分-->
			<div class="sum-num">
				<div class="sum-num-outside">
					<div class="sumnum-section">
						<img class="sum-icon" src="imgs/concat/sum-icon.png"/>
						<p class="sumnum-title">总共挪车数：<span><?php echo $report['totals']; ?></span></p>
					</div>
					<div class="todaynum-section">
						<img class="today-icon" src="imgs/concat/today-icon.png"/>
						<p class="todaynum-title">今日挪车数：<span><?php echo $report['today_totals']; ?></span></p>
					</div>
				</div>
			</div>
			
			<!--联系车主部分-->
			<div class="concat-master">
				<div class="concatmaster-section">
					<!--<button class="concatmaster-button"></button>-->
					<!--<img class="concat-icon" src="imgs/concat/concat-icon.png"/>-->
					<div class="border">
						<img class="tel-icon" src="imgs/concat/tel-icon.png"/>
					</div>
					<input type="hidden" class="hidden"  value="<?php echo $user['mobile']; ?>" />
					<input type="hidden" id='to_user_id'  value="<?php echo $user['id']; ?>" />
					<input type="hidden" id='from_user_id' value="<?php echo $from_user_id['id']; ?>" />
					
					<p class="concatmaster-title">点击呼叫车主，速来挪车</p>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/mobileScreen.js"></script>
	</body>
</html>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
	$(function(){
		function move(){
			$('.border').removeClass('to-down').addClass('animation');
			setTimeout(function(){
				wait();
			}, 800);
		}		
		function wait() {
			$('.border').removeClass('animation').addClass('to-down');
			setTimeout(function(){
				move();
			}, 800);
		}
		move();
		var mobile = $(".hidden").val();
		var to_user_id = $("#to_user_id").val();
		var from_user_id = $("#from_user_id").val();
		var ip = 1;
		$(".border").click(function() {
			$.get('/user/add_record',{to_user_id:to_user_id,from_user_id:from_user_id,ip:ip},function(){
				location.href = 'tel://' + mobile;
			})
		});
	});
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "https://hm.baidu.com/hm.js?42c9439fb7579fb6bd3e94939b30692e";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
</script>


