<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>美联帮</title>
    <base href="/mlbspa/weixin/Public/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/iconfont.css"/>
    <style>
    	.mui-bar .mui-title{left: 60px;}
    	.mui-search .mui-placeholder{color: #FFFFFF; text-align: left; font-size: 14px; padding-left: 10px; line-height:inherit;right: 2px;}
   		.mui-search .mui-icon{color: #FFFFFF;}
   		.mui-search .mui-placeholder .mui-icon{color: #FFFFFF;}
   		.mui-input-row .mui-input-clear~.mui-icon-clear, .mui-input-row .mui-input-speech~.mui-icon-speech{color: #FFFFFF;line-height:inherit;}
    	.mui-bar .mui-input-row .mui-input-speech~.mui-icon-speech {right: 0px;}
		.mui-search.mui-active:before{height: initial;line-height: initial;}
		.myheader .mui-btn-link{color: #FFFFFF;font-size: 14px;}
		.myheader .mui-icon{font-size: 14px;}
		.menu_icon{}
		.menu_icon img{width: 100%;}
		.mui-grid-view.mui-grid-9 .mui-table-view-cell{padding: 2px 14px;}
    	.mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {
    		font-size: 14px;
    	}
    	.menu-bar-tab{
    		bottom: 0;
		    display: table;
		    width: 100%;
		    height: 50px;
		    padding: 0;
		    table-layout: fixed;
		    border-top: 0;
		    border-bottom: 0;
		    -webkit-touch-callout: none;
    	}
    	.menu-bar-tab .menu-tab-item {
		    display: table-cell;
		    overflow: hidden;
		    width: 1%;
		    height: 50px;
		    text-align: center;
		    vertical-align: middle;
		    white-space: nowrap;
		    text-overflow: ellipsis;
		    color: #929292;
		}
		.menu-bar-tab .menu-tab-item .mui-icon {
		    top: 3px;
		    width: 24px;
		    height: 24px;
		    padding-top: 0;
		    padding-bottom: 0;
		}
		.menu-bar-tab .menu-tab-item .mui-icon~.mui-tab-label {
		    font-size: 11px;
		    display: block;
		    overflow: hidden;
		    text-overflow: ellipsis;
		}
		.menu-bar-tab .menu-tab-item.mui-active {
		    color: #007aff;
		}
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav myheader">
		<button class="mui-btn mui-btn-blue mui-btn-link mui-btn-nav mui-pull-left" id="btn_city">
			<span class="mui-icon mui-icon-arrowdown"></span>
			<span id="citytxt">广州</span>
		</button> 
		<div class="mui-title">
		<div class="mui-input-row mui-search">
			<input id="search" type="search" class="mui-input-speech mui-input-clear" placeholder="搜索">
		</div>
		</div>	
		<a id="spamap" class="mui-icon mui-icon-location mui-pull-right" style="color: #FFFFFF; font-size: 24px;"></a>
	</header>
	<nav class="mui-bar menu-bar-tab">
		<a class="menu-tab-item" id="menu_merchant" href="<?php echo U('merchantList');?>">
			<span class="mui-icon iconfont icon-shangcheng"></span>
			<span class="mui-tab-label">商家</span>
		</a>
		<a class="menu-tab-item" id="menu_shop">
			<span class="mui-icon iconfont icon-iconwoshangcheng"></span>
			<span class="mui-tab-label">厂家</span>
		</a>
		<a class="menu-tab-item mui-active" id="menu_home" >
			<span class="mui-icon iconfont icon-shouyeshouye"></span>
			<span class="mui-tab-label">首页</span>
		</a>
		<a class="menu-tab-item" id="menu_spa" href="<?php echo U('spa/lists');?>">
			<span class="mui-icon iconfont icon-meironghufu"></span>
			<span class="mui-tab-label">预约项目</span>
		</a>
		<a class="menu-tab-item" id="menu_user" href="<?php echo U('merchantList');?>">
			<span class="mui-icon iconfont icon-wode"></span>
			<span class="mui-tab-label">我的</span>
		</a>
	</nav>
	<div class="mui-content"> 
		<div class="mui-slider">
		  <div class="mui-slider-group mui-slider-loop"> 
		    <!--支持循环，需要重复图片节点-->
		    <div class="mui-slider-item mui-slider-item-duplicate"><img src="images/demo/runpic4.jpg" /></div>
		    <div class="mui-slider-item"><img src="images/demo/runpic1.jpg" /></div>
		    <div class="mui-slider-item"><img src="images/demo/runpic2.jpg" /></div>
		    <div class="mui-slider-item"><img src="images/demo/runpic3.jpg" /></div>
		    <div class="mui-slider-item"><img src="images/demo/runpic4.jpg" /></div>
		    <!--支持循环，需要重复图片节点-->
		    <div class="mui-slider-item mui-slider-item-duplicate"><img src="images/demo/runpic1.jpg" /></div>
		  </div>
		</div>
		<ul class="mui-table-view mui-grid-view mui-grid-9">
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnOrderToShop">
				<a >
					<div class="menu_icon">
						<img src="images/icon_menu_01.png"/>
					</div>
					<div class="mui-media-body">预约到店</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnOrderToHome">
				<a >
					<div class="menu_icon">
						<img src="images/icon_menu_02.png"/>
					</div>
					<div class="mui-media-body">预约上门</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnShare">
				<a>
					<div class="menu_icon">
						<img src="images/icon_menu_12.png"/>
					</div>
					<div class="mui-media-body">文章分享</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnAdShop">
				<a>
					<div class="menu_icon">
						<img src="images/icon_menu_09.png"/>
					</div>
					<div class="mui-media-body">广告体验</div>
				</a>
			</li>
		</ul>
		<div>
			<img src="images/index_adm.jpg" width="100%" />
		</div>
		<ul class="mui-table-view " id="spalist">
			
		</ul>
		
	</div>
<script id="spalistTpl" type="text/html">
    {{each data as value i}}
    <li class="mui-table-view-cell mui-media" id="{{value.spa_id}}">
		<a href="javascript:;" class="mui-navigate-right">
			<img class="mui-media-object mui-pull-left" src="{{value.cover_s}}">
			<div class="mui-media-body">
				{{value.spa_name}}
				<p class='mui-ellipsis'>{{value.intro}}</p>
				<p><span class="price">￥{{value.sale_price}}</span></p>
			</div>
		</a>
	</li>
    {{/each}}
</script>	
</body>
<script src="js/mui.min.js"></script>
<script src="js/app.js" type="text/javascript" charset="utf-8"></script>
<script src="js/api.js"></script>
<script src="js/template.js"></script>
<script type="text/javascript" charset="utf-8">
  	mui.init();
  	mui.ready(function(){
  		//获取菜单按钮
  		var menubtn_user = document.getElementById("menu_user");
  		var menubtn_home = document.getElementById("menu_home");
  		var menubtn_merchant = document.getElementById("menu_merchant");
  		var menubtn_spa = document.getElementById("menu_spa");
  		var menubtn_shop = document.getElementById("menu_shop");
  		
  		var param = {};
		param.order_type = 1;
	    Api.getSpaList(param,function(result){
	    	var html = template('spalistTpl', {data:result});
			document.getElementById('spalist').innerHTML = html;
	    }); 
  		
  		
  	});
  	
  	
  	mui.plusReady(function(){
  		
  		var city = app.getCity();
	    if(typeof city.name !== 'undefined'){
			cityname = city.name;
		}else{
			cityname = "全国";
		} 
	    document.getElementById("citytxt").innerText = cityname;
	    //console.log(JSON.stringify(city));
//		mui.currentWebview.show();
  		
//		plus.navigator.setStatusBarStyle( "UIStatusBarStyleBlackOpaque" );
//		plus.navigator.setStatusBarBackground("#f61d67");
		
  		//获取菜单按钮
  		var menubtn_user = document.getElementById("menu_user");
  		var menubtn_home = document.getElementById("menu_home");
  		var menubtn_merchant = document.getElementById("menu_merchant");
  		var menubtn_spa = document.getElementById("menu_spa");
  		var menubtn_shop = document.getElementById("menu_shop");
  		
  		
	    
	    
	    //监听退出
		mui.oldBack = mui.back;
		var backButtonPress = 0;
		mui.back = function(event) {
			backButtonPress++;
			if (backButtonPress > 1) {
				plus.runtime.quit();
			} else {
				plus.nativeUI.toast('再按一次退出应用');
			}
			setTimeout(function() {
				backButtonPress = 0;
			}, 1000);
			return false;
		};
  		
  		
//		//菜单按钮【我的】事件
//		menubtn_user.addEventListener("tap",function () {
//			mui.openWindow({
//			    url:'userCenter.html',
//			    id:'userCenter',
//			    waiting:{
//			      	autoShow:true,//自动显示等待框，默认为true
//			      	title:'正在努力加载...',//等待对话框上显示的提示内容
//			    },
//			});
//		});
		
		//菜单按钮【商家】事件
  		menubtn_merchant.addEventListener("tap",function () {
			mui.openWindow({
				url:"merchantList.html",
			    id:"merchantList",
			    extras:{
	//		    	city_id:2,
			    	//order_type:1,
	//		    	cate_id:1,
			    },
//			    show: {
//					autoShow:false
//				},
//			    waiting:{
//			      autoShow:true,//自动显示等待框，默认为true
//			      title:'正在加载...',//等待对话框上显示的提示内容
//			    }
			});
		});
		
		//菜单按钮【美容项目】事件
  		menubtn_spa.addEventListener("tap",function () {
			mui.openWindow({
				url:"spaList.html",
			    id:"spaList",
			    extras:{
	//		    	city_id:2,
			    	order_type:1,
	//		    	cate_id:1,
			    },
			    waiting:{
			      autoShow:true,//自动显示等待框，默认为true
			      title:'正在加载...',//等待对话框上显示的提示内容
			    }
			});
		});
		
		//菜单按钮【商城】事件
  		menubtn_shop.addEventListener("tap",function () {
			mui.toast('正在开发中，敬请期待...');
		});		
  	});
  	
  	//添加列表项的点击事件
	mui("#spalist").on("tap","li",toDetail);
  	/**
	 * 打开项目详情页面
	 */
	function toDetail(){
		var spa_id = this.getAttribute('id');
		if(!spa_id){
			console.log('无法获取项目ID');
			return false;
		}
		
	  	//打开详情页面          
  		mui.openWindow({
    		id:'spaDetail',
    		url:"spaDetail.html",
    		show:{
		      autoShow:false
		    },
			waiting:{
  				autoShow:true,//自动显示等待框，默认为true
  				title:'正在努力加载...',//等待对话框上显示的提示内容
    		},
    		extras:{
    			spa_id:spa_id
    		}
  		});
	}
  	
  	//获得slider插件对象
	mui('.mui-slider').slider({
	  	interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
	}); 
	
    
     
    
    
    //监听按钮
	document.getElementById("btnOrderToShop").addEventListener("tap",function () {
		mui.openWindow({
			url:"spaList.html",
		    id:"spaList",
		    extras:{
//		    	city_id:2,
		    	order_type:1,
//		    	cate_id:1,
		    },
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...',//等待对话框上显示的提示内容
		    }
		});
		
	});
	
	//监听按钮【积分商城】
	document.getElementById("btnAdShop").addEventListener("tap",function () {
		mui.openWindow({
			url:"ad_home.html",
		    id:"ad_home",
		    extras:{
//		    	city_id:2,
		    },
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...',//等待对话框上显示的提示内容
		    }
		}); 
		
	});
	
	
	//监听按钮【文章分享】
	document.getElementById("btnShare").addEventListener("tap",function () {
		mui.openWindow({
			url:"share_home.html",
		    id:"share_home",
		    show: {
				autoShow:false,
			},
		    waiting:{
		      autoShow:true,
		      title:'正在加载...',
		    }
		}); 
	});
	
	//监听按钮【预约上门】
	document.getElementById("btnOrderToHome").addEventListener("tap",function () {
		mui.toast('正在开发中，敬请期待...');
	});
	
	//监听按钮【更改所在城市】
	var btnCity = document.getElementById("btn_city");
	
	btnCity.addEventListener("tap",function () {
		mui.openWindow({
			url:"changecity.html",
		    id:"changecity",
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...',//等待对话框上显示的提示内容
		    }
		});
	});
	
	//监听地图按钮
	document.getElementById("spamap").addEventListener("tap",function () {
		mui.openWindow({
			url:"map_merchant.html",
		    id:"map_merchant",
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...',//等待对话框上显示的提示内容
		    }
		});
	});
	
	
	
</script>
</html>