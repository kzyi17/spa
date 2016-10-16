<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>文章分享首页</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
		.menu_icon img{width: 100%;}
		.mui-grid-view.mui-grid-9 .mui-table-view-cell{padding: 2px 10px;}
    	.pointInfo{background: #FF0000!important;}
    	.pointInfo p{color: #FFFFFF;}
    	.pointInfo li{padding: 16px 10px!important;}
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">文章分享</h1>
</header>
<div class="mui-content"> 
	<div class="mui-slider">
	  <div class="mui-slider-group mui-slider-loop"> 
	    <div class="mui-slider-item mui-slider-item-duplicate"><img src="images/demo/ad_demo.jpg" /></div>
	  </div>
	</div>
	
	<ul class="mui-table-view mui-grid-view mui-grid-9 pointInfo" id="pointInfo">
	</ul>	
	
	<ul class="mui-table-view mui-grid-view mui-grid-9">
		<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnGuide">
			<a href="<?php echo U('share/guide');?>">
				<div class="menu_icon">
					<img src="images/icon_menu_13.png"/>
				</div>
				<div class="mui-media-body">新手学堂</div>
			</a>
		</li>
		<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnNews">
			<a href="<?php echo U('share/newsList');?>">
				<div class="menu_icon">
					<img src="images/icon_menu_14.png"/>
				</div>
				<div class="mui-media-body">开始赚钱</div>
			</a>
		</li>
		<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnShare">
			<a href="<?php echo U('share/myShare');?>">
				<div class="menu_icon">
					<img src="images/icon_menu_15.png"/>
				</div>
				<div class="mui-media-body">邀请好友</div>
			</a>
		</li>
		<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnEarnDetail">
			<a href="<?php echo U('share/pointDetail');?>">
				<div class="menu_icon">
					<img src="images/icon_menu_16.png"/>
				</div>
				<div class="mui-media-body">收益明细</div>
			</a>
		</li>
		<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnPointShop">
			<a href="<?php echo U('share/shopList');?>">
				<div class="menu_icon">
					<img src="images/icon_menu_03.png"/>
				</div>
				<div class="mui-media-body">积分商城</div>
			</a>
		</li>
	</ul>
</div>
<script id="pointInfoTpl" type="text/html">
	<li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6 mui-text-left">
		<p class="mui-ellipsis">当前积分：<%=sharepoint%>积分</p>
	</li>
	<li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6 mui-text-left">
		<p class="mui-ellipsis">累计收入：<%=total_sharepoint%>积分</p>
	</li>
</script>	
</body>
<script src="js/mui.min.js"></script>
<script src="js/api.js"></script>
<script src="js/app.js"></script>
<script type="text/javascript" src="js/arttmpl.js" ></script>
<script type="text/javascript" charset="utf-8">
	mui.init();  
	mui.ready(function(){
		userId = '<?php echo ($user_id); ?>';
		Api.getShareUserInfo({user_id:userId},function(res){
			document.getElementById("pointInfo").innerHTML = template('pointInfoTpl', res);
		});
	});
	
	
//	document.getElementById("btnGuide").addEventListener('tap',function(){
//		mui.openWindow({
//		    url:'share_guide.html',
//		    id:'share_guide',
//		    waiting:{
//		      	autoShow:true,
//		      	title:'正在努力加载...',
//		    },
//		});
//	},false);
//	
//	document.getElementById("btnNews").addEventListener('tap',function(){
//		mui.openWindow({
//		    url:'share_news_list.html',
//		    id:'share_news_list',
//		    waiting:{
//		      	autoShow:true, 
//		      	title:'正在努力加载...',
//		    },
//		});
//	},false);
//	
//	document.getElementById("btnShare").addEventListener('tap',function(){
//		mui.openWindow({
//		    url:'share_myshare.html',
//		    id:'share_myshare',
//		    waiting:{
//		      	autoShow:true, 
//		      	title:'正在努力加载...',
//		    },
//		});
//	},false);
//	
//	document.getElementById("btnEarnDetail").addEventListener('tap',function(){
//		mui.openWindow({
//		    url:'share_earndetail.html',
//		    id:'share_earndetail',
//		    waiting:{
//		      	autoShow:true, 
//		      	title:'正在努力加载...',
//		    },
//		});
//	},false);
//	
//	document.getElementById("btnPointShop").addEventListener('tap',function(){
//		mui.openWindow({
//		    url:'share_exchangeList.html',
//		    id:'share_exchangeList',
//		    waiting:{
//		      	autoShow:true, 
//		      	title:'正在努力加载...',
//		    },
//		});
//	},false);
	
	
</script>
</html>