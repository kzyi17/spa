<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="HandheldFriendly" content="true"/>
	<meta name="MobileOptimized" content="320"/>
	<title>关于</title>
	<base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
	<link href="css/mui.min.css" rel="stylesheet"/>
	<link href="css/style.css" rel="stylesheet"/>
	<script src="js/mui.min.js"></script>
	<style type="text/css">
		li {
			padding: 2em;
			border-bottom: 1px solid #eaeaea;
			background: #fafafa;
			font-size: 14px;
		}
		
		li:active {
			background: #f4f4f4;
		}
		li span{ padding-right: .6em;}
		footer {
			width: 100%;
			text-align: center;
			color: #AAAAAA;
			font-size: 12px;
			padding-bottom: 2em;
		}
		.logo {
			width: 100%;
			text-align: center;
		}
	</style>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">关于</h1>
	</header>
	<div id="content" class="mui-content">
		<div class="logo">
			<br/>
			<img style="width:72px;height:72px;" src="images/logo_108.png"/>
			<br/><br/>
		</div>
	<p class="des" style="font-size:14px;line-height:30px;margin: 0px 2em;">美联帮是一款集美容项目在线预约、美容产品在线商城的APP。</p>
	<br/>
	<ul style="list-style:none;margin:0;padding:0;text-align:left;">
		<li class="">
			<span class="mui-icon mui-icon-phone" style="font-size: 2.5em;"></span>
			400-004-8368
		</li>
		<li>
			<span class="mui-icon mui-icon-chatbubble" style="font-size: 2.5em;"></span>
			http://app.szwzlm.com
		</li>
		<li style="border-bottom:0px;">
			<span class="mui-icon mui-icon-email" style="font-size: 2.5em;"></span>
			kefu@szwzlm.com
		</li>
	</ul>
	<br/>
	<footer>
		<span>Copyright 2015</span>
		<br/>
		<span>深圳市万众联盟网络科技有限公司</span>
		<br/>
		<!--<span id="ver">当前版本：0.2.4</span>-->
	</footer>
	</div>
</body>
<script>
(function($) {
	$.init();
}(mui));	
</script>	
</html>