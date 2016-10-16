<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class="ui-page-login">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
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
		</style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">注册</h1>
		</header>
		<div class="mui-content">
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
			</div>
			<div class="mui-content-padded">
				<button id='reg' class="mui-btn mui-btn-block mui-btn-danger mui-btn-primary">注册</button>
			</div>
			
		</div>
<script src="js/mui.min.js"></script>
<script src="js/api.js" type="text/javascript" charset="utf-8"></script>
<script src="js/app.js"></script>
<script>
(function($, doc) {
	$.init();
	var smsInterval=60,second = smsInterval,timer;
	var userID = <?php echo ($userid); ?>;
	$.ready(function() {
		var settings = app.getSettings();
		var regButton = doc.getElementById('reg');
		var accountBox = doc.getElementById('account');
		var codeBox = doc.getElementById('code');
		var passwordBox = doc.getElementById('password');
		var passwordConfirmBox = doc.getElementById('password_confirm');
		var codeButton = doc.getElementById('btn_code');
		
		//账号输入框输入触发事件
		accountBox.addEventListener('input', function() {
			if(/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/i.test(this.value))
			{
				changCodeBtn(1);
			}else{
				changCodeBtn(0);
			}
		}, false);
		
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
				var regInfo = {mobile: accountBox.value,password: passwordBox.value,code:codeBox.value,pid:userID};
				//console.log(JSON.stringify(regInfo));
				Api.register(regInfo,function(result){
					$.alert('注册成功，请登录', '美联帮', function() {
						window.location = "<?php echo U('user/login');?>";
					});
				},function(result){
					console.log(JSON.stringify(result));
					$.toast(result.errmsg);
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
			
			Api.getSmsCode(param,function(result){
				mui.toast("验证码已成功发送");
				secondCount();
			},function(result){
				mui.toast(result.errmsg);
				secondCount();
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
	});
	
	
}(mui, document));

</script>
</body>

</html>