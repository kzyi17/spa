<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>美容列表</title>
    <base href="/mlbspa/weixin/Public/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
    	.mui-search .mui-placeholder{color: #FFFFFF; text-align: left; font-size: 14px; padding-left: 10px; line-height:inherit;right: 2px;}
   		.mui-search .mui-icon{color: #FFFFFF;}
   		.mui-search .mui-placeholder .mui-icon{color: #FFFFFF;}
   		.mui-input-row .mui-input-clear~.mui-icon-clear, .mui-input-row .mui-input-speech~.mui-icon-speech{color: #FFFFFF;line-height:inherit;}
    	.mui-bar .mui-input-row .mui-input-speech~.mui-icon-speech {right: 0px;}
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav myheader">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<!--<button class="mui-action-back mui-btn mui-btn-blue mui-btn-link mui-btn-nav mui-pull-left"><span class="mui-icon mui-icon-arrowdown"></span>广州</button>-->
		<div class="mui-title">
		<div class="mui-input-row mui-search">
			<input id="search" type="search" class="mui-input-speech mui-input-clear" placeholder="美容项目搜索">
		</div>
		</div>	
		<!--<a id="spamap" class="mui-icon mui-icon-location mui-pull-right" style="color: #FFFFFF; font-size: 24px;"></a>-->
	</header>
	<div class="mui-content">
		<ul class="mui-table-view mui-grid-view grid-view-border mt0">
			<li class="mui-table-view-cell mui-col-xs-4 "><a id="order_hot">
				<div>按人气<span class="mui-icon mui-icon-arrowdown"></span></div>
			</a></li>
			<li class="mui-table-view-cell mui-col-xs-4"><a id="order_price">
				<div>按价格<span class="mui-icon mui-icon-arrowdown"></span></div>
			</a></li>
			<li class="mui-table-view-cell mui-col-xs-4"><a id="order_sale">
				<div>按热门<span class="mui-icon mui-icon-arrowdown"></span></div>
			</a></li>
		</ul> 
	</div>

</body>
<script src="js/mui.min.js"></script>
<script type="text/javascript">

	//启用双击监听
	mui.init({
		gestureConfig:{
			doubletap:true
		},
		subpages:[{
			url:'Spa/list/list_data.html',
			id:'list_data',
			styles:{
				top: '88px',
				bottom: '0px',
			}
		}]
	});
	
	//监听搜索事件
	var contentWebview = null;
	document.getElementById('search').addEventListener('search',function(){
		if(this.value!=""){
			if(contentWebview==null){
				contentWebview = plus.webview.currentWebview().children()[0];
			} 
			contentWebview.evalJS("search('"+ this.value +"')");
		}
	});
	
	//监听筛选框
	document.getElementById('order_hot').addEventListener('tap',function(){
		if(contentWebview==null){
			contentWebview = plus.webview.currentWebview().children()[0];
		}
		contentWebview.evalJS("orderby('hits')");
	});
	document.getElementById('order_price').addEventListener('tap',function(){
		if(contentWebview==null){
			contentWebview = plus.webview.currentWebview().children()[0];
		}
		contentWebview.evalJS("orderby('price')");
	});
	document.getElementById('order_sale').addEventListener('tap',function(){
		if(contentWebview==null){
			contentWebview = plus.webview.currentWebview().children()[0];
		}
		contentWebview.evalJS("orderby('hot')");
	});


</script>
</html>