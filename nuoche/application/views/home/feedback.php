<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>意见反馈</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<base href="<?php echo base_url(); ?>" />
		<link rel="stylesheet" type="text/css" href="../css/common.css"/>
		<link rel="stylesheet" type="text/css" href="../css/feedback.css"/>
	</head>
	<body>
		<div class="bg-box">
			<div class="mark">
				
			</div>
			<input type="hidden" id="user_id" value="<?php echo $user_id; ?>" />
			<div class="feedback-section">
				<h6 class="feedback-title">您有什么意见或建议想对我们说？</h6>
				<textarea class="your-idea" id="content" placeholder="您宝贵的意见是我们进步的源泉！"></textarea>
				<p class="feedback-tip">请详细描述您遇见的问题，有助于我们快速定位并解决问题，或留下您宝贵的意见或建议，我们会认真进行评估！</p>
				<button id="submit">提交</button>
			<!--留言成功-->
			<div class="success-tip">
				<p>提交成功！</p>
			</div>
			</div>
		</div>
		<script type="text/javascript" src="../js/mobileScreen.js"></script>
	</body>
</html>

<script type="text/javascript" src="js/mobileScreen.js"></script>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script>
	$(function(){
		$("#submit").click(function(){
		var content=$("#content").val();
		var user_id=$("#user_id").val();
			if(content!=""){

				$.post('/user/add_feed',{content:content,user_id:user_id},function(msg){
					if(msg){
						$('#content').val('');
						$(".mark").show();
						$(".success-tip").show();
						 //利用setTimeout延迟执行匿名函数
				        setTimeout(function(){
			                $(".mark").hide();
							$(".success-tip").hide();
				            },2000);
						}
				});
			}
		});
	})
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "https://hm.baidu.com/hm.js?42c9439fb7579fb6bd3e94939b30692e";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	
</script>