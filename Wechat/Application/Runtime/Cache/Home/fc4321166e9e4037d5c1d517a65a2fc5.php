<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <style>
    	.myheader{background-color: #f61d67;}
   		.myheader .mui-title,.mui-bar .mui-btn-link,.myheader a{color: #FFFFFF;}
    
    	.mui-btn-block {margin-bottom: 0;padding: 6px 0; font-size: 18px;}
    	.mui-table-view{margin-top: 12px;}
    	.mui-table-view .title{
    		font-size: 16px;
    		position: relative;
  			overflow: hidden;
  			padding: 8px 12px;
  			font-weight: bold;
  		}
    	.mui-table-view .title:after{
    	  position: absolute;
		  right: 0;
		  bottom: 0;
		  left: 10px;
		  height: 1px;
		  content: '';
		  -webkit-transform: scaleY(.5);
		  transform: scaleY(.5);
		  background-color: #c8c7cc;
		}
		.mui-table-view .content{
			padding: 10px 12px;
			font-size: 14px;
		}
		.price{font-size: 16px;color: #2AC845;}
		.cost{font-size: 16px;color: #C0C0C0;text-decoration: line-through;}
		.mui-icon-star{color: #B5B5B5;font-size: 20px;}
    	.mui-icon-star-filled{color: #FFB400;font-size: 20px;}
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav myheader">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">商家详情</h1>
	</header>
	<div class="mui-content" >
		<div class="mui-slider" id="gallery">
			
		</div>
		
		<div id="mechantDetail"></div>
		<div class="mui-table-view">
			<div class="title">美容项目</div>
			<div class="content" id="spa">
				<ul class="mui-table-view merchantSpa">
					
				</ul>
			</div>
		</div>
	</div>
	<div><br/></div>
<!--商家相册-->	
<script id="merchantGalleryTpl" type="text/html">
	<div class="mui-slider-group mui-slider-loop">
		<div class="mui-slider-item mui-slider-item-duplicate"><img src="{{last.img_m}}"></div>
	{{each list as value i}}
		<div class="mui-slider-item"><img src="{{value.img_m}}"></div>
	{{/each}}
		<div class="mui-slider-item mui-slider-item-duplicate"><img src="{{first.img_m}}"></div>
	</div>
	<div class="mui-slider-indicator mui-text-right">
	{{each list as value i}}
		{{if i==0}}
		    <div class="mui-indicator mui-active"></div>
		{{else}}
		    <div class="mui-indicator"></div>
		{{/if}}
	{{/each}}
	</div>
</script>	
<!--商家详情-->
<script id="merchantDetailTpl" type="text/html">
	<div class="mui-table-view">
		<div class="title">{{merchant_name}}</div>
	</div>
	
	<div class="mui-table-view" style="padding: 10px; margin-top: 0; font-size: 18px;">
		<div>
			<div class="mui-pull-left" style="font-size: 12px;">商家地址：{{address}}</div>
			<div class="mui-pull-right"><a href="javascript:;" onclick="toMap({{map_id}})"><span class="mui-icon mui-icon-map" style="font-size: 30px;"></span></a></div>
			<div class="mui-clearfix"></div>
		</div>
		<div>
			<div class="mui-pull-left" style="font-size: 12px; line-height: 34px;">商家电话： {{phone}}</div>
			<div class="mui-pull-right"><a href="tel:{{phone}}"><span class="mui-icon mui-icon-phone" style="font-size: 32px;"></span></a></div>
			<div class="mui-clearfix"></div>
		</div>
	</div>
	
	<!--商家简介-->
	<div class="mui-table-view">
		<div class="title">商家评分</div>
		<div class="icons content">
			<i data-index="1" class="mui-icon mui-icon-star-filled"></i>
			<i data-index="2" class="mui-icon mui-icon-star-filled"></i>
			<i data-index="3" class="mui-icon mui-icon-star-filled"></i>
			<i data-index="4" class="mui-icon mui-icon-star-filled"></i>
			<i data-index="5" class="mui-icon mui-icon-star"></i>
		</div>
	</div>
	
	
	<!--商家简介-->
	<div class="mui-table-view">
		<div class="title">商家简介</div>
		<div class="content">
			{{intro}}
		</div>
	</div>
	
</script>	
<!--商家美容项目-->
<script id="merchantSpaTpl" type="text/html">
	{{each data as value i}}
	<li class="mui-table-view-cell mui-media" id="{{value.spa_id}}">
		<a href="javascript:;" onclick="toSpaDetail({{value.spa_id}})">
			<img class="mui-media-object mui-pull-left" src="{{if value.cover_s}}value.cover_s{{else}}images/nopic.jpg{{/if}}">
			<div class="mui-media-body">
				{{value.spa_name}}
				<p class='mui-ellipsis'><span class="price">￥{{value.sale_price}}</span> <span class="cost">￥{{value.market_price}}</span></p>
			</div>
		</a>
	</li>
	{{/each}}
</script>
</body>
<script src="js/mui.min.js"></script>
<script src="js/template.js"></script>
<script src="js/api.js"></script>
<script type="text/javascript" charset="utf-8">
	mui.init(); 
	
	
  //从服务器获取数据
  //业务数据获取完毕，并已插入当前页面DOM；
  //注意：若为ajax请求，则需将如下代码放在处理完ajax响应数据之后；
  mui.ready(function(){
//	self = plus.webview.currentWebview();
  	merchantId = "<?php echo ($id); ?>";
  	param = {'merchant_id':merchantId};
  	template.config('escape', false);
  	
  	Api.getMerchantInfo(param,function(result){
    	//console.log(JSON.stringify(result)); 
    	var html = template('merchantDetailTpl', result);
		document.getElementById('mechantDetail').innerHTML = html;
    }); 
  	
  	
  	Api.getMerchantGallery(param,function(result){
    	var piclen = result.length;
    	var data = {};
    	if(piclen>=1){
    		data.first=result[0];
    		data.last=result[piclen-1];
    		data.list = result;
    	}
		document.getElementById('gallery').innerHTML = template('merchantGalleryTpl', data);
		mui('#gallery').slider();
    },function(result){
   		document.getElementById('gallery').innerHTML = '<img src="images/nopic_b.jpg" width="100%">';
    });  
   
   
    var spaParam = {order_type:1,merchant_id:merchantId,limit:5};
    Api.getSpaList(spaParam,function(res){
   		if(res.length>0){
   			document.getElementById('spa').innerHTML = template('merchantSpaTpl', {data:res}); 
   		}else{
   			document.getElementById('spa').innerHTML = '暂无提供美容项目';
   		}
   		
    });
    
    
	
  });
  function toSpaDetail (id) {
    	if(!id){
			console.log('无法获取项目信息');
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
    			spa_id:id
    		}
  		});
    }
	
	function toMap(mapId){
		if(!mapId){
			alert('无法获取商家地图信息');
			return false;
		}
		
		var url = "<?php echo U('merchant/map');?>" + "?id="+mapId;
		window.location = url;
		
//		//打开详情页面          
//		mui.openWindow({
//  		id:'map_merchant',
//  		url:"map_merchant.html",
//  		show:{
//		      autoShow:false
//		    },
//			waiting:{
//				autoShow:true,//自动显示等待框，默认为true
//				title:'正在努力加载...',//等待对话框上显示的提示内容
//  		},
//  		extras:{
//  			map_id:mapId
//  		} 
//		});
		
	} 
	

</script>
</html>