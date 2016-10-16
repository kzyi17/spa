<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>美联帮</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
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
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav myheader">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">产品详情</h1>
	</header>
	<div class="mui-content" >
		<div class="mui-slider" id="spadetail_gallery"></div>
		<!--价格及预约按钮-->
		<div class="mui-table-view" style="padding: 10px; margin-top: 0;">
			<div class="mui-pull-left" style="font-size: 14px; line-height: 34px; padding-left: 10px; color: #f61d67;" id="spadetail_price">
				
			</div>
			<div class="mui-pull-right" style="width: 35%;">
				<button type="button" class="mui-btn mui-btn-warning mui-btn-block" id="btn_order" >
					预约下单
				</button>
			</div>
			<div class="mui-clearfix"></div>
		</div>
		
		<div id="spadetail"></div>
	</div>
	<div><br/></div>
	
<script id="spadetailTpl_gallery" type="text/html">
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
	
<script id="spadetailTpl_price" type="text/html">
	￥<span style="font-size: 32px; margin-right: 2px;">{{sale_price}}</span><span style="text-decoration: line-through; color: #C7C7CC;">￥{{market_price}}</span>
</script>	

<script id="spadetailTpl" type="text/html">
	
	<!--提供商家-->
	<div class="mui-table-view">
		<div class="title">商家信息</div>
		<div class="content">
			{{merchant_name}}
		</div>
	</div>
	
	<!--地址及联系电话-->
	<div class="mui-table-view" style="padding: 10px; margin-top: 0; font-size: 18px;">
		<div class="mui-pull-left" style="font-size: 12px;"><a href="javascript:;" onclick="toMap({{map_id}})"><span class="mui-icon mui-icon-location" ></span>{{address}}</a></div>
		<div class="mui-pull-right"><a href="tel:{{phone}}"><span class="mui-icon mui-icon-phone" style="font-size: 32px;"></span></a></div>
		<div class="mui-clearfix"></div>
	</div>
	
	<!--服务说明-->
	<div class="mui-table-view">
		<div class="title">服务说明</div>
		<div class="content">
			{{introduce}}
		</div>
		
	</div>
	<!--购买须知-->
	<div class="mui-table-view">
		<div class="title">购买须知</div>
		<div class="content">
			{{buynotes}}
		</div>
	</div>
	
</script>	
</body>
<script src="js/mui.min.js"></script>
<script type="text/javascript" src="js/template.js" ></script>
<script src="js/api.js"></script>
<script>
	mui.init();
	mui.ready(function(){
		var spa_id = "<?php echo ($id); ?>";
		
		var param = {spa_id:spa_id};
		template.config('escape', false);
		
		Api.getSpaInfo(param,function(result){
	    	//console.log(JSON.stringify(result));   
	    	var html = template('spadetailTpl',result);
	    	document.getElementById('spadetail_price').innerHTML = template('spadetailTpl_price',result);
			document.getElementById('spadetail').innerHTML = html;
			//document.getElementById('spadetail_gallery').innerHTML = template('spadetailTpl_gallery',result);;
	    });   
	    
	    Api.getSpaGallery(param,function(result){
	    	var piclen = result.length;
	    	var data = {};
	    	if(piclen>0){
	    		data.first=result[0];
	    		data.last=result[piclen-1];
	    		data.list = result;
	    		document.getElementById('spadetail_gallery').innerHTML = template('spadetailTpl_gallery', data);
				mui('#spadetail_gallery').slider();
	    	}else{
	    		document.getElementById('spadetail_gallery').innerHTML = '<p>fff</p>';
	    	}
	   },function(result){
	   		document.getElementById('spadetail_gallery').innerHTML = '<img src="images/nopic_b.jpg" width="100%">';
	   		
	   });
	    
		
		document.getElementById('btn_order').addEventListener('tap',function(){
			var url = "<?php echo U('spa/order');?>" + "?id="+spa_id;
			window.location = url;
		});
	});
	function toMap(mapId){
		if(!mapId){
			alert('无法获取商家地图信息');
			return false;
		}
		
		//打开详情页面          
  		mui.openWindow({
    		id:'map_merchant',
    		url:"map_merchant.html",
    		show:{
		      autoShow:false
		    },
			waiting:{
  				autoShow:true,//自动显示等待框，默认为true
  				title:'正在努力加载...',//等待对话框上显示的提示内容
    		},
    		extras:{
    			map_id:mapId
    		} 
  		});
		
	} 
	
	
</script>
</html>