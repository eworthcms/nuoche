<!DOCTYPE html>
<html lang="en" class="bgimg">
<head>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_global.css">

<style>
.loginTop{ height:60px;  position:fixed; top:43px; left:69px; width:100%;}
.footer{bottom:0; width:100%; position:fixed; height:60px; line-height:60px; color:#a5a8ae; text-align: center; font-size: 14px;}
.wAuto{ min-width:990px; max-width:1200px; margin:0 auto;}
.bgimg{ background:url(/img/loginbg.jpg) center center;  height:100%; background-size: cover;}
.left img{width: 170px;padding-top: 7px;}
.loginCt{ display:inline-block; height:100%; line-height:100%; vertical-align:middle; }
.loginBox{ position:fixed; top:18.8%; bottom:50px; line-height:100%; width:100%; }
.loginCon{width:315px; padding:30px 48px 45px 48px; background-color:#fff; border-radius:10px; display:inline-block; text-align:left; vertical-align:middle; min-height:300px; position: absolute; right:9%;}

.loginline{ padding-left: 38px; height:48px; background: #f7f7f7; margin-bottom: 26px; border-radius: 8px;}
.loginline input{ width: 230px; margin-top: 9px; line-height:28px; height:28px; border:0; background:none; outline: none; font-size: 16px;}
.loginline input::placeholder{ color: #a7aaad; }

.loginBtn{ width:100%; border:0; border-bottom:3px solid #008f4e; border-radius:4px; background-color:#14a965; color:#fff; height:48px; line-height:48px; font-size:16px;}
.loginCon li.last{ font-size: 16px; color: #c8cacc; padding-bottom:20px;}

.tc{ text-align: center; }
.login-text{ text-align: center; font-size:18px; color: #2e3e4e; padding-top:20px; padding-bottom: 35px; }
.icon-user{ float: right; width: 25px; height: 29px; background: url(/img/icon-user.png) center center no-repeat; margin: 14px 21px 0 0; }
.icon-psw{ float: right; width: 25px; height: 29px; background: url(/img/icon-psw.png) center center no-repeat; margin: 14px 21px 0 0; }
.checkbox{ float: left; margin: -4px 5px 0 0; cursor: pointer; border-radius: 3px; width: 18px; height: 18px; border:#a1e3f1 solid 1px; -webkit-appearance:none; appearance:none; }
.checkbox:checked{ background:#a1e3f1; }
.checkbox:checked:after{ content: "√"; position: absolute; color: #fff; font-size: 18px; }
.lbg-r{ width: 37px; height: 35px; background: url(/img/lbg-r.png) no-repeat; position: absolute; right: -37px; bottom: 46px; }
.lbg-l{ width: 54px; height: 51px; background: url(/img/lbg-l.png) no-repeat; position: absolute; left: -54px; top: 52px; }

.loginLogo{ display: none; }
.login-text{ display: none; }
@media screen and (max-height: 660px) {
   .tiptext {display: none; }
}
@media screen and (min-height: 800px) {
   .login-text{ display: block; }
}
@media screen and (min-height: 900px) {
   .loginLogo{ display: block; }
   .login-text{ display: block; }
}
</style>
</head>
<body>

<div class="loginTop"><img src="<?php echo base_url(); ?>img/logo-login.png"></div>
<div class="loginBox">
      <div class="loginCon">
      <div class="lbg-r"></div>
    <div class="lbg-l"></div>
    <div class="tc loginLogo"><img src="<?php echo base_url(); ?>img/login.png" /></div>
    <div class="login-text">拨云智拓广告投放系统</div>
  <form action="/login/user_login" method="post" id="user_login">
  <ul>
    <li>
        <div class="loginline">
            <i class="icon-user"></i>
            <input name="user_name" id="username" type="text" value="" placeholder="请输入用户名">
        </div>
    </li>
    <li><div class="loginline"><i class="icon-psw"></i> <input name="password" type="password" value="" placeholder="请输入密码" id="password"></div></li>
    <li>
        <div class="loginline" style="display:inline-block;">
            <input style="width:136px" name="code" id="code" type="text" placeholder="请输入验证码">
        </div>
        <div style="float:right;">
            <img id="captcha_img" src="/login/get_code" width="140" height="55">
        </div>
    </li>
    <li class="last">
        <label class="gray9">
            <input type="checkbox" name="record" class="vlm checkbox" id="record" checked="checked">记住密码</label>
        <p style=" line-height:24px; padding-top:10px; font-size:14px; color:#c8cacc;">
        <span class="tiptext"><b style="color:#24bddf;">注意事项：</b>请正确输入用户名、密码和验证码</span></p>
    </li>
  </ul>
  </form>
  <input type="button" value="登录" class="loginBtn" onclick="login()">
  
  </div> <i class="loginCt vlm"></i>
</div>
<div class="footer">
 Copyright © 2017 北京拨云科技有限公司
</div>

<script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>js/layer/layer.js"></script>
<script>
$("#captcha_img").click(function() {
    $("#captcha_img").attr("src", '/login/get_code?'+Math.random());
});

$('#record').click(function(){
    if($(this).attr('checked')){
        $(this).removeAttr('checked');
    }else{
        $(this).attr('checked', 'checked');
    }
});

function login() {
    var username = $('#username').val();
    var password = $('#password').val();
    var code = $('#code').val();
    if($('#record').attr('checked')){
        $('#record').val('1');
    }
    if(!username){
        layer.tips('用户名不能为空', '#username', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    if(!password){
        layer.tips('密码不能为空', '#password', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    if(!code){
        layer.tips('验证码不能为空', '#code', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/login/check_login/'+username+'/'+password+'/'+code,
        dataType: 'json',
        success: function (msg) {
            if(msg==1){
                layer.tips('该用户名不存在或已被停用', '#username', {
                    tips: [1, '#ff8400']
                });
            }else if(msg==2){
                layer.tips('密码输入不正确', '#password', {
                    tips: [1, '#ff8400']
                });
            }else if(msg==3){
                layer.tips('验证码不正确', '#code', {
                    tips: [1, '#ff8400']
                });
            }else{
                SimpleLock.lock();
                $('#user_login').submit();
            }
        }
    });
}
</script>
<script type="text/javascript" language=JavaScript charset="UTF-8">
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


document.onkeydown=function(event){
var e = event || window.event || arguments.callee.caller.arguments[0];

if(e && e.keyCode==13){ // enter 键
    var username = $('#username').val();
    var password = $('#password').val();
    var code = $('#code').val();
    if($('#record').attr('checked')){
        $('#record').val('1');
    }
    if(!username){
        layer.tips('用户名不能为空', '#username', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    if(!password){
        layer.tips('密码不能为空', '#password', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    if(!code){
        layer.tips('验证码不能为空', '#code', {
            tips: [1, '#ff8400']
        });
        return false;
    }
    
    SimpleLock.lock();
    $('#user_login').submit();
}
}; 
</script>
</body>
</html>
