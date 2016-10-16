<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>广告商品列表</title>
	<base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
	<link href="css/mui.min.css" rel="stylesheet"/>
	<link href="css/style.css" rel="stylesheet"/>
	
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	    <h1 class="mui-title">广告兑换区</h1>
	</header>
	<div class="mui-content">
	    <ul class="mui-table-view" id="adlist">
			
		</ul>
	</div>
<script id="adlistTpl" type="text/html">
    {{each data as value i}}
    <li class="mui-table-view-cell mui-media" id="{{value.adgoods_id}}">
		<a href="javascript:;" class="mui-navigate-right">
			<img class="mui-media-object mui-pull-left" src="{{value.adgoods_cover}}">
			<div class="mui-media-body">
				{{value.adgoods_name}}
				<p class='mui-ellipsis'>{{value.intro}}</p>
				<p class='mui-ellipsis'>需要{{value.exchange_point}}积分可兑换</p>
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
	mui.ready(function(){
		var param = {};
		param.city_id = 1;
		
		Api.getExchangeGoodsList(param,function(result){
	    	var html = template('adlistTpl', {data:result});
			document.getElementById('adlist').innerHTML = html;
	    }); 
	});
	
	//添加列表项的点击事件
	mui("#adlist").on("tap","li",toDetail);
	
	/**  
	 * 打开项目详情页面
	 */
	function toDetail(){
		var adgoods_id = this.getAttribute('id');
		if(!adgoods_id){
			console.log('无法获取项目ID');
			return false;
		}
		
		var url = "<?php echo U('adv/exchangeDetail');?>" + "?id="+adgoods_id;
		window.location = url;	 
		
//	  	//打开详情页面          
//		mui.openWindow({
//  		id:'ad_exchangeDetail',
//  		url:"ad_exchangeDetail.html",
//  		show:{
//		      autoShow:false
//		    },
//			waiting:{
//				autoShow:true,//自动显示等待框，默认为true
//				title:'正在努力加载...',//等待对话框上显示的提示内容
//  		},
//  		extras:{
//  			adgoods_id:adgoods_id
//  		}
//		});
//	  	
	  	
	  	
	}
	
</script>
</html>