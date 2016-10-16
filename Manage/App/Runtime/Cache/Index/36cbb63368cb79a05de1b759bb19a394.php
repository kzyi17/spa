<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>登录-美联帮管理系统</title>
        <base href="<?php echo C('WEB_ROOT');?>"/>
        <link rel="stylesheet" href='/mlbspa/manage/Public/css/base.css' />
        <link rel="stylesheet" type="text/css" href="<?php echo C('WEB_STATICS');?>/js/asyncbox/skins/default.css" />
		<script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/jquery-1.9.0.min.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/jquery.form.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/functions.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/asyncbox/asyncbox.js"></script> 
  
</head>

<body class="loginWeb">
    <div class="loginBox">
        <div class="innerBox">
            <div class="logo"> <img src="<?php echo C('WEB_STATICS');?>/public/logo.png" /></div>
            <form id="form1" action="" method="post">
                <div class="loginInfo">
                    <ul>
                        <li class="row1">登录账号：</li>
                        <li class="row2"><input class="input" name="UserCode" id="UserCode" size="30" type="text" /></li>
                    </ul>
                    <ul>
                        <li class="row1">登录密码：</li>
                        <li class="row2"><input class="input" name="UserPassword" id="UserPassword" size="30" type="password" /></li>
                    </ul>
                    <ul>
                        <li class="row1">验证码：</li>
                        <li class="row2"><input class="input" id="verify_code" name="verify_code" type="text" maxlength="4" style="width:100px;" /> <img src="<?php echo C('WEB_ADMIN');?>/Public/verify_code"  title="看不清？单击此处刷新" onClick="this.src+='?rand='+Math.random();"  style="cursor: pointer; vertical-align: middle;"/></li>
                    </ul>
                </div>
                <input type="hidden" name="op_type" id="op_type" value="1"/>
            </form>
            <div class="clear"></div>
            <div class="operation"><button class="btn submit">登 录</button></div>
        </div>
    </div>

    <script type="text/javascript">
    
        $(function(){
            $(".submit").click(function(){
                $("#op_type").val("1");
                if($("#UserCode").val()==''||$("#UserPassword").val()==''||$("#verify_code").val()==''){
                    popup.alert("填写完整方可登录");
                    return false;
                }
                commonAjaxSubmit();
            });

			/**
				添加回车登陆事件*/
            document.onkeydown = function(e){
            	 var ev = document.all ? window.event : e;
            	    if(ev.keyCode==13){
	   	            	 $("#op_type").val("1");
	   	                 if($("#UserCode").val()==''||$("#UserPassword").val()==''||$("#verify_code").val()==''){
	   	                     popup.alert("填写完整方可登录");
	   	                     return false;
	   	                 }
	   	                 commonAjaxSubmit();
            	  }             
                }

        });
    </script>
</body>
</html>