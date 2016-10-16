<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE >
<html>
<head>
<title>用户注册</title> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="msapplication-tap-highlight" content="no">
<base href="/mlbspa/weixin/Public/" />
<link rel="stylesheet" href="css/base.css">
<script>
	!function(a){function d(){var c=b.getBoundingClientRect().width;a.rem=c/6.4>100?100:50>c/6.4?50:c/6.4,b.style.fontSize=a.rem+"px"}var c,b=a.document.documentElement;a.addEventListener("resize",function(){clearTimeout(c),c=setTimeout(d,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(c),c=setTimeout(d,300))},!1),d()}(window);
</script>
<style>
.container{margin:0 auto;font-family:'STHeitiSC Light'; padding:.5rem .3rem 0;}
.tips{font-size: .2rem; color: #BABABA;}
.input-row.w6{width:60%}
.w100{width:100%}
.w6{width:60%}
.w35{width: 35%;}
.input-row{height: .8rem; padding: .125rem 0 .125rem .2rem;} 
.input-row .input{font-size: .32rem; color: #000000;}
.input-row.button{background-color: #ff9402;border: 0; color: #fff; padding: 0; line-height: .8rem; font-size: .32rem;}
.input-row.button.unable{background: #D7D7D7;color: #BABABA;}
.input-row.failed{background-color: #ff9402; border: 1px solid #ff9402; color: #fff; padding: 0; line-height: .8rem; font-size: .32rem;}
.input-row.button.unable{background-color: #d7d7d7;border: 1px solid #d7d7d7; color: #BABABA; font-size: .32rem;}
/*.input-row.button:enabled:active{color: #fff; border: 1px solid #DBDCDD; background-color: #DBDCDD;}*/
.button{margin-top: 0;}
</style>
</head>
<body>	
<div class="wrap" id="search_section">
<form action="" method="post">
	<!-- 
	<div class="container">
		<p class="tips mb10">为方便您购票乘车，请验证手机</p>
		<div class="input-row bg border mb10">
			<input name="mobile" id="mobile" class="input" type="text" placeholder="手机号"/>
			<button class="del show"></button>
		</div>
		<div class="mb10 clearfix">
			<div class="input-row bg border w6  fl">
				<input name="code" id="code" class="input" type="text" placeholder="验证号"/>
				<button class="del"></button>
			</div>
			<button class="input-row fr w35 tc button" id="code_btn">验证码</button>
		</div>
		<button class="input-row bg w100 button" id="submit_btn">登录</button>
	</div>
	 -->
	<div class="wrap" id="search_section">
		<div class="container">
			<p class="tips mb10">为方便您购票乘车，请验证手机</p>
			<div class="input-row bg border mb10">
				<input class="input" id="phone" name="phone" type="number" placeholder="手机号"/>
				<button class="del" id="del_phone"></button>
			</div>
			<div class="mb10 clearfix">
				<div class="input-row bg border w6  fl">
					<input class="input" type="number" id="iden_number" name="iden_number" disabled="disabled" placeholder="验证号"/>
					<button class="del " id="del_code"></button>
				</div>
				<button class="input-row unable fr w35 tc button" id="iden_code">获取验证码</button>
			</div>
			<button class="input-row bg w100 button unable" id="login">
				登录
			</button>
		</div>
	</div>
</form>	
</div>
</body>
<script src="js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
({
	init:function(){
		$("#phone").on('input',function(){
			if ($("#phone").val() != '') {
				$("#iden_code").removeClass('unable');
				$("#iden_number").removeAttr('disabled'); //验证号不可输入
				$("#del_phone").addClass('show');
			}else{
				$("#iden_code").addClass('unable');
				$("#iden_number").attr('disabled','disabled');
				$("#del_phone").removeClass('show');
			}


		});
		
		$("#iden_number").on('input',function(){
			if ($("#iden_number").val() != '' && $("#phone").val() != '') {
				$("#del_code").addClass('show');
				$("#login").removeClass('unable');
			}else{
				$("#login").addClass('unable');
				$("#del_code").removeClass('show');
			}
			$(this).on('blur',function(){
				$("#del_code").removeClass('show');
			});
		});
		
		//发送验证码
		$("#iden_code").on('click',function(){
			
			if ($(this).hasClass('unable')) {
				alert('发送失败');
			}else{
			 	// 发送代码
				mobile = $("#phone").val();
				$.ajax({
					  type: 'POST',
					  url: '<?php echo U("Public/getcode");?>',
					  data: { mobile: mobile },
					  dataType: 'json',
					  timeout: 500,
					  success: function(data){
						  console.log(data);
						  
					  },
					  error: function(xhr, type){
					    //alert('Ajax error!');
					  }
				}); 
				//alert('发送成功');
			    secondCount();
				
			}
			return false;
		});
		
		var second = 60,timer1;
		function secondCount(){
			if (second == 1) {
				clearTimeout(timer1);
				$("#submit_btn").addClass('disable');
				$("#iden_code").html('重新获取');
				$("#iden_code").removeClass('unable');
				second = 60;
		    } else {
		        second -= 1;
		        $("#iden_code").html(second+'s');
		        $("#iden_code").addClass('unable');
		        timer1 = setTimeout(function () {
		            secondCount(second);
		        }, 1000);
		    }
		}
		
		$("#del_phone").on('tap',function(){
			$("#phone").val('');
			$("#iden_number").attr('disabled','disabled');
			$("#login").addClass('unable');
			$("#iden_code").addClass('unable');
			$("#iden_number").val('');
		});
		$("#del_code").on('tap',function(){
			$("#iden_number").val('');
		});
		
	}
}).init();
</script>

</html>