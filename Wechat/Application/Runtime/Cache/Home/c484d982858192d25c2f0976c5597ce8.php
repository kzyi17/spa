<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>广告商品详情</title>
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
	<div class="mui-content">
	    <div id="addetail"></div>
	    <!--价格及预约按钮-->
		<div class="mui-table-view" style="padding: 10px; margin-top: 0;">
			<div class="mui-pull-right" >
				<button type="button" class="mui-btn mui-btn-warning" id="btnExchange" >
					兑换
				</button>
			</div>
			<div class="mui-clearfix"></div>
		</div>    
	</div>
<script id="addetailTpl" type="text/html">
	<div class="mui-slider">
		<%if(gallery.length>0){%>
			<div class="mui-slider-group mui-slider-loop"> 
				<!--支持循环，需要重复图片节点-->
				<div class="mui-slider-item mui-slider-item-duplicate"><img src="<%=_url + gallery[gallery.length-1]['folder']+gallery[gallery.length-1]['name']+'_m.'+gallery[gallery.length-1]['type']%>" /></div>
				<%for(i = 0; i < gallery.length; i ++) {%>
					<div class="mui-slider-item"><img src="<%=_url + gallery[i]['folder']+gallery[i]['name']+'_m.'+gallery[i]['type']%>" /></div>
			    <%}%>
				<!--支持循环，需要重复图片节点-->
				<div class="mui-slider-item mui-slider-item-duplicate"><img src="<%=_url + gallery[0]['folder']+gallery[0]['name']+'_m.'+gallery[0]['type']%>" /></div>
	 		</div>
		<%}else{%>
			<div class="mui-slider-group mui-slider-loop"> 
		    	<div class="mui-slider-item mui-slider-item-duplicate"><img src="images/nopic_b.jpg" /></div>
		  	</div>
		<%}%>	
	</div>
		
	<!--提供商家-->
	<div class="mui-table-view">
		<div class="title"><%=goods_name%></div>
		<div class="content">
			<%=intro%>
		</div>
	</div>
	
	<!--服务说明-->
	<div class="mui-table-view">
		<div class="title">商家介绍</div>
		<div class="content">
			<%=merchant_name%>
		</div>
	</div>
	
	<!--地址及联系电话-->
	<div class="mui-table-view" style="padding: 10px; margin-top: 0; font-size: 18px;">
		<div class="mui-pull-left" style="font-size: 12px;"><span class="mui-icon mui-icon-location" style="color: #FFFFFF;"></span>联系电话：<%=phone%></div>
		<div class="mui-pull-right"><a href="tel:<%=phone%>"><span class="mui-icon mui-icon-phone" style="font-size: 32px;"></span></a></div>
		<div class="mui-clearfix"></div>
	</div>
	 
	
</script>		
</body>
<script src="js/mui.min.js"></script>
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/api.js"></script>
<script src="js/app.js"></script>
<script>
	mui.init();
	var adGoodsId;
	var userId = "<?php echo ($user_id); ?>";
	template.config('escape', false);
	mui.ready(function(){
		adGoodsId = "<?php echo ($id); ?>";
		var param = {goods_id:adGoodsId};
		Api.getShareGoodsInfo(param,function(result){
	    	//console.log(JSON.stringify(result));   
	    	var html = template('addetailTpl',result);
			document.getElementById('addetail').innerHTML = html;
			//获得slider插件对象
			mui('.mui-slider').slider({
			  	interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
			}); 
	    });   
		
	});
	
	document.getElementById('btnExchange').addEventListener('tap',function(){
		var url = "<?php echo U('share/exchangeCommit');?>" + "?id=" + adGoodsId;
		window.location = url;
	});
</script>
</html>