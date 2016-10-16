<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class="ui-page-login">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title></title>
	<base href="/mlbspa/weixin/Public/" />
	<link href="css/mui.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" />
<style>
   	.ui-register-from{margin-top: 30px;}
   	#mobile{text-align: left;}
   	#code{width: 60%;text-align: left;}
   	#btn_code{width: 36%;}
   	@media (min-width: 400px){
   		#code{width: auto;}
   		#btn_code{width: auto;}
   	}
   	.remark{text-align: center; color: #AAAAAA;}
   	.remark span{color: #abc047;}
	.mask{
   		position: fixed;
   		left: 0;
   		top: 0;
   		right: 0;
   		bottom: 0;
   		background: rgba(0,0,0,0.96);
   		z-index: 999;
		color:#FFF;
		text-align: center;
		padding:100px 0 0 0;
		display: none;
	}
</style>
</head>
<body>
<div class="mui-content">
	<div class="" style=" text-align: center;"><img alt="" src="images/logo.png" style="width:100px;margin: 20px auto 0 auto;"></div>
	<div class="mui-content-padded ui-register-from">
		<div class="mui-input-row">
			<input id='account' type="number" class="mui-input-clear" placeholder="手机号码" >
		</div>
		<div class="mui-input-row">
			<input id='code' type="number" class="mui-input-clear" placeholder="验证码" >
			<button id="btn_code" type="button" class="mui-btn mui-btn-grey">获取验证码</button>
		</div>
		<div class="mui-input-row">
			<input id='password' type="password" class="mui-input-clear" placeholder="登录密码" >
		</div>
		<div class="mui-input-row">
			<input id='password_confirm' type="password" class="mui-input-clear" placeholder="确认密码" >
		</div>
		<input type="hidden" name="userIdBox" id="userIdBox" value="<?php echo ($user_id); ?>">
	</div>
	<div class="mui-content-padded">
		<button id='reg' class="mui-btn mui-btn-block mui-btn-danger mui-btn-primary">注册</button>
	</div>
</div>

<div class="mask" id="mask">恭喜您注册成功<br/>页面正在跳转请稍候...</div>

<script src="js/mui.min.js"></script>
<script>
(function($, doc) {
	$.init();
	var smsInterval=60,second = smsInterval,timer;
	var regButton = doc.getElementById('reg');
	var accountBox = doc.getElementById('account');
	var codeBox = doc.getElementById('code');
	var passwordBox = doc.getElementById('password');
	var passwordConfirmBox = doc.getElementById('password_confirm');
	var userIdBox = doc.getElementById('userIdBox');
	var codeButton = doc.getElementById('btn_code');
	var sucsec = 4,suctimer;	
	var sucTxt = "恭喜您注册成功<br/>页面正在跳转请稍候";
	
	//账号输入框输入触发事件
	accountBox.addEventListener('input', function() {
		if(/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/i.test(this.value))
		{
			changCodeBtn(1);
		}else{
			changCodeBtn(0);
		}
	}, false);
	
	//登录成功延迟跳转
	function sucref(){
		doc.getElementById('mask').style.display="block";
		if (sucsec == 0) {
			location.href = "http://mlb.mywork99.com/weixin/index/download";
		}else{
			sucsec -= 1;
			doc.getElementById('mask').innerHTML = sucTxt;
			sucTxt += "."; 
			timer = setTimeout(function () {
				sucref(sucsec);
	        }, 1000);
		}
	}
		
	//注册按钮
	regButton.addEventListener('tap', function(event) {
		if(accountBox.value=='' || passwordBox.value=='' || passwordConfirmBox.value=='' || codeBox.value==''){
			$.toast('用户、验证码、密码不能为空！');
		} else {
			var passwordConfirm = passwordConfirmBox.value;
			if (passwordConfirm != passwordBox.value) {
				$.toast('密码两次输入不一致');
				return;
			}
			//注册用户APi
			var regInfo = {mobile: accountBox.value,password: passwordBox.value,code:codeBox.value,parent:userIdBox.value};
			
			$.ajax('http://mlb.mywork99.com/api/User/register?token=asdqwe',{
				data:JSON.stringify(regInfo),
				dataType:'json',//服务器返回json格式数据
				type:'post',//HTTP请求类型
				timeout:10000,//超时时间设置为10秒；
				success:function(data){
					//服务器返回响应，根据响应结果，分析是否登录成功；
					sucref();
				},
				error:function(xhr,type,errorThrown){
					//异常处理；
					$.toast(result.errmsg);
				}
			});
			
		}
		
	});
		
		//获取验证码
		function getCode(){
			var mobile = accountBox.value;
			if(!/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/i.test(mobile)){
				mui.toast('请输入正确的手机号码');
				return;
			}
			var param = {'mobile':mobile};
			$.ajax('http://mlb.mywork99.com/api/public/getsmscode?token=asdqwe',{
				data:JSON.stringify(param),
				dataType:'json',//服务器返回json格式数据
				type:'post',//HTTP请求类型
				timeout:10000,//超时时间设置为10秒；
				success:function(data){
					//服务器返回响应，根据响应结果，分析是否登录成功；
					$.toast("验证码已成功发送");
					secondCount();
				},
				error:function(xhr,type,errorThrown){
					//异常处理；
					$.toast(result.errmsg);
					secondCount();
				}
			});
		}
		
		//验证码按钮定时器
		function secondCount(){
			if (second == 1) {
				clearTimeout(timer);
				codeButton.innerText = "获取验证码";
				changCodeBtn(1);
				second = smsInterval;
		    } else {
		        second -= 1;
		        changCodeBtn(0);
				codeButton.innerText = second + "秒后获取";
		        timer = setTimeout(function () {
		            secondCount(second);
		        }, 1000);
		    }
		}
		
		//更改按钮【获取验证码】状态
		function changCodeBtn(enable){
			if(enable){
				codeButton.className = "mui-btn mui-btn-danger";
				codeButton.addEventListener('tap',getCode);
			}else{
				codeButton.className = "mui-btn mui-btn-grey";
				codeButton.removeEventListener('tap',getCode);
			}
		}
	
	
}(mui, document));

</script>
</body>
</html>