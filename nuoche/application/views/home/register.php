<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>注册</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<base href="<?php echo base_url('/'); ?>" />
		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/register.css"/>
		<link rel="stylesheet" type="text/css" href="icon/iconfont.css"/>
	</head>
	<body>
		<div class="bg-box">
			<!--logo区域-->
			<div id="logo">
				<div class="logo-img">
					<img src="imgs/register/logo.png"/>
				</div>		
			</div>
			<!--表单内容区域-->
			<div id="section">
				<div class="section-con">
						<div class="tel-num">
							<input style="width: 2.65rem;" id="user-tel" type="text" maxlength="11" onblur='checkMobile()' name="" id="" value="" placeholder="请输入手机号"/>
							<div class="fisrt-num">
								+86
							</div>
							<div class="send-random-num">
								<p class="getcode">发送验证码</p>
							</div>
						</div>
						<!-----错误提示------>
						<h6 class="userTel">*请输入正确的手机号码</h6>
						<input type="text" name="" id="code" onblur="checkCode()" value="" placeholder="请输入验证码"/>
						<!-----错误提示------>
						<h6 class="randomNumTip">*请输入正确验证码</h6>
						<div class="car-num" style="margin-bottom: 0.095rem;">
							<div style="display: flex;height: 0.45rem;border-radius: 0.225rem;width: 1rem;overflow: hidden;">
								<select name="" id="top">
									<?php foreach($request as $k=>$v){ ?>
					   			<option value="<?php echo $v['code'] ?>"><?php echo $v['code'] ?></option>
									<?php } ?>
								</select>
							</div>
							<input class="write-car-num"  type="text" name="" id="license_plate" value="" placeholder="牌号(选填)"/>
						</div>												
						<button id="submit">提交</button>
						<h6>＊为防止恶意注册需要您提供手机号和车牌号，请放心
我们不会泄漏您的手机号车牌号信息给其他第三发。</h6>					
				</div>
			</div>
		</div>
		
			
	</body>
</html>
<script type="text/javascript" src="js/mobileScreen.js"></script>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
function checkMobile() {
    var mobile=$("#user-tel").val();
    var mobilez=/^1[3|4|5|7|8][0-9]{9}$/;
    var status;
    if(mobile==''){
    	$(".userTel").show();
        $(".userTel").html("*手机号不能为空");
          status=false;
    }else if(mobilez.test(mobile)){
    	$(".userTel").hide();
        $(".userTel").html("");
        status=true;
    }else{
        $(".userTel").show();
        $(".userTel").html("*请输入正确的手机号码");
      status=false;
    }
    return status;
}
$('.send-random-num').click(function() {
	var mobile = $("#user-tel").val();

	if($(".getcode").hasClass('aaa'))
	{
		return false;
	}
	if(!(/^1[34578]\d{9}$/.test(mobile))){
	    $(".userTel").show();
	    $(".userTel").html('*请输入正确的手机号码');
	    return false;
	} else {
			$.get('/user/get_sms_code',{mobile:mobile},function(msg){
				if (msg == 1) {
	            	$(".getcode").addClass('aaa');
		            for (var i=0; i <= 60; i++) {
		                setTimeout("update_p(" + i + ","+60+")", i * 1000);
		            }
		        }else if(msg==2){
                    $("#user-tel").val('');
                    $(".userTel").show();
                    $(".userTel").html('*该手机号已经存在');
                }else {
		            $("#user-tel").val('');
                    $(".userTel").show();
                    $(".userTel").html('*请稍候再发送验证码');
//		            $("#user-tel").addClass('invalid');
//		            $("#user-tel").attr('placeholder', '您手机号操作太频繁');
		        }
			});
	}
});
function update_p(num,t) { 
    if(num == t) { 
    	$(".getcode").removeClass("aaa");
        $(".getcode").html('重新发送');
    } else {
        var left = parseInt(60-num);
        $(".getcode").html("重新发送" + left);

    }
}
function checkCode(){
	var code=$("#code").val();
	var mobile=$("#user-tel").val();
	
	var status;
	if (code==""){
		$(".randomNumTip").show();
		$(".randomNumTip").html("*验证码不能为空");
		status=false;
	}else {
		$.ajax({ 
		    type : "get", 
		    url : "/user/check_code", 
		    data : {code:code,mobile:mobile}, 
		    async : false,
		    success : function(msg){
			    if(msg=='false'){
					status=false;
				$(".randomNumTip").show();
				$(".randomNumTip").html("*请输入正确验证码");
				}else{
					$(".randomNumTip").hide();
					$(".randomNumTip").html("");
					status=true;
				}
		  	}
        });
	}
	return status;
}
$("#submit").click(function(){
	var mobile = $("#user-tel").val();
	var top = $("#top").val();
	var license_plate = $("#license_plate").val();
	if (checkMobile() && checkCode()) {
		SimpleLock.lock();
		$.post('/user/upd_mobile',{mobile:mobile,top:top,license_plate:license_plate},function(msg){
			if(msg){
				location.href="<?php echo base_url() ?>";
			}
		});
	}
})
//防止表单重复提交
//SimpleLock.lock();
var SimpleLock = function () {};
 SimpleLock.locked = false;
 SimpleLock.lock = function() {
    if(!SimpleLock.locked) {
        console.info('锁成功');
        SimpleLock.locked = true;
    }else {
        throw '已经上锁';
    }
 };
 SimpleLock.unlock = function() {
    console.info('解锁');
    SimpleLock.locked = false;
 };
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?42c9439fb7579fb6bd3e94939b30692e";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
