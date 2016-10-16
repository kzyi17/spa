<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>确认订单</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css" />
    <style>
		.mt10{margin-top: 10px;}
    	.mt15{margin-top: 15px;}
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">预约下单</h1>
	</header>
	<div class="mui-content" >
		<div id="spadetail">
			<ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">
					<span class="mui-pull-left">{{itemTitle}}</span>
					<span class="mui-pull-right">{{itemPrice}}元</span>
				</li>
			</ul>
		</div>
		
		<!--<ul class="mui-table-view mt15">
			<li class="mui-table-view-cell mui-media">
				<a class="mui-navigate-right">
					<span class="mui-icon mui-icon-home mui-pull-left" ></span>
					<div class="mui-media-body">
						<div class="mui-ellipsis"><span>李先生</span> <span>13005568888</span></div>
						<p class="mui-ellipsis"><span>广州市白云区广州大道北117号305室</span></p>
					</div>	
				</a>
			</li>
			<li class="mui-table-view-cell mui-media">
				<a class="mui-navigate-right">
					<span class="mui-icon mui-icon-loop mui-pull-left" ></span>
					<div class="mui-media-body">
						<span class="mui-ellipsis">2015-09-18 09:00</span>
					</div>	
				</a>
			</li>
		</ul>-->
		<!--<ul class="mui-table-view mt15">
			<li class="mui-table-view-cell mui-media">
				<a class="mui-navigate-right">
					美容师：默认指派
				</a>
			</li>
		</ul>-->
		<div class="mui-input-row mt15" style="padding: 5px 5px; background-color: #FFFFFF">
			<input type="text" id="remark" value="" class="mui-input-speech mui-input-clear" placeholder="请填写附加要求" style="background-color: #EAEAEA; margin-bottom: 0;">
		</div>
		
		<div id="order"></div>
		
	</div>
	
	<nav class="mui-bar mui-bar-footer">
		<button type="button" class="mui-btn mui-btn-warning mui-btn-block" id="btn_commit" style="min-width: 10em; max-width:80%;margin: 0 auto;">确认订单</button>
	</nav>
<script id="spadetailTpl" type="text/html">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-media">
			<span class="mui-pull-left">{{spa_name}}</span>
			<span class="mui-pull-right">{{sale_price}}元</span>
		</li>
	</ul>	
</script>	
<script id="orderTpl" type="text/html">
	<ul class="mui-table-view mt15">
		<!--<li class="mui-table-view-cell mui-media">
			<a class="mui-navigate-right">
				<span>抵用券：</span>
				<span class="mui-pull-right" style="padding-right: 26px; color: #2AC845;">请选择优惠券</span>
			</a>
		</li>-->
		<li class="mui-table-view-cell mui-media">
			<span>订单总价：</span>
			<div class="mui-media-body mui-pull-right" style="text-align: right;">
				<span style="color: #FF0000;">{{sale_price}}元</span>
				<!--<p>(已优惠70元)</p>-->
			</div>
		</li>
	</ul>	
</script>
<script src="js/mui.min.js"></script>
<script type="text/javascript" src="js/template.js" ></script>
<script src="js/api.js"></script>
<script src="js/app.js"></script>
<script type="text/javascript" charset="utf-8">
  	mui.init();
  	mui.ready(function(){
		var spa_id = "<?php echo ($id); ?>";//获取美容项目ID
		var param = {spa_id:spa_id};
		
		Api.getSpaInfo(param,function(result){
			document.getElementById('spadetail').innerHTML = template('spadetailTpl',result);
			document.getElementById('order').innerHTML = template('orderTpl',result);
	    }); 
		
		
		
		document.getElementById("btn_commit").addEventListener('tap',function(){
			var remark = document.getElementById("remark").value;
			var userId = <?php echo ($user_id); ?>;
			
			var param = {spa_id:spa_id,user_id:userId,order_type:1,remark:remark};
			console.log(JSON.stringify(param));
			
			
			Api.spaOrderCommit(param,function(result){
//				console.log(JSON.stringify(result));
				var order_sn = result.order_sn;
				url = "<?php echo U('spa/pay');?>?ordersn="+order_sn;
				window.location = url;
			},function(res){
				mui.toast(result.errmsg);
			});
			
		});
  	});
  	
   
	
	
</script>
</body>
</html>