<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>支付订单</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css" />
    <style>
    	.mt15{margin-top: 15px;}
    	.mt20{margin-top: 20px;}
    	.mt30{margin-top: 30px;}
    	.mui-input-group .mui-input-row {
		  	height: 64px;
		}
		.mui-radio label {
		  padding-right: 52px;
		}
		.paytype .payicon{ width: 42px;height: 42px;}
		.paytype .paylabel{ padding-left: 8px;}
		.paylabel .payname{color: #000000;font-size: 18px; padding: 0;margin: 0;}
		.paylabel .paydesc{padding: 0; margin: 6px 0 0 0;font-size: 12px;}
		.mui-checkbox input[type=checkbox], .mui-radio input[type=radio] {
		  position: absolute;
		  top: 18px;
		  right: 20px;
		  display: inline-block;
		  width: 28px;
		  height: 26px;
		}
    </style>
    
    
</head>
<body>
	<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">支付订单</h1>
	</header>
	<div class="mui-content">
		<div id="orderInfo">
			
      	</div>
      	
		<form class="mui-input-group mt15" id="payment">
			<div class="mui-input-row mui-radio paytype">
				<label>
				    <img src="images/icon_wxpay.png" class="mui-pull-left payicon"/>
				    <div class="mui-pull-left paylabel">
						<p class="payname">微信支付</p>
						<p class="paydesc">推荐安装微信5.0版本及以上版本的使用</p>
					</div>
				</label>
				<input name="paymentId" type="radio" checked>
			</div>
		</form>
		
		<div class="mt30">
			<button type="button" class="mui-btn mui-btn-warning mui-btn-block" id="btn_pay" style="min-width: 10em; max-width:88%;margin: 0 auto;">确认支付</button>
		</div>
			
	</div>
<script id="orderInfoTpl" type="text/html">	
	<ul class="mui-table-view mt15">
		<li class="mui-table-view-cell mui-media mui-ellipsis">
			<span>订单号：</span>
			<span class="">{{order_sn}}</span>
		</li>
		<li class="mui-table-view-cell mui-media mui-ellipsis">
			<span>订单名称：</span>
			<span class="">{{spa_name}}</span>
		</li>
		<li class="mui-table-view-cell mui-media ">
			<span>订单金额：</span>
			<span>{{order_amount}}元</span>
		</li>
	</ul>
  	<ul class="mui-table-view mt15">
		<li class="mui-table-view-cell mui-media mui-ellipsis">
			<span>还需支付：</span>
			<span style="color: #FF0000;">{{pay_amount}}元</span>
		</li>
	</ul>
</script>	
<script src="js/mui.min.js"></script>
<script type="text/javascript" src="js/template.js" ></script>
<script src="js/api.js"></script>
<script type="text/javascript" charset="utf-8">
	mui.init();
	var order_sn;
	
	mui.ready(function(){
		order_sn = "<?php echo ($order_sn); ?>";//获取订单号
		var param = {order_sn:order_sn};
		
		//获取订单信息
		Api.getSpaOrderInfo(param,function(result){
			document.getElementById('orderInfo').innerHTML = template('orderInfoTpl',result); 
			//console.log(JSON.stringify(result));
	    }); 
		
	});
	
	//监听支付按钮
	document.getElementById("btn_pay").addEventListener('tap',function(){
		var chosed = getRadioValue('paymentId');
		if(chosed){
			if(w){return;}//检查是否请求订单中
			
			w=plus.nativeUI.showWaiting();
			console.log('你选择的支付方式是：'+chosed);
			
			//获取预支付信息
			var param = {order_sn:order_sn,pay_type:chosed};
			Api.getSpaOrderPrePay(param,function(result){
				w.close();w=null;
				console.log(JSON.stringify(result));
				plus.payment.request(pays[chosed],JSON.stringify(result),function(payresult){
					plus.nativeUI.alert("支付成功",function(){
						//TODO
						//back();
						
					},"支付结果");
				},function(error){
					console.log(JSON.stringify(error));
					plus.nativeUI.alert("支付失败",null,"支付失败："+error.code);
				});
				
		    },function(result){
		    	w.close();w=null;
		    	plus.nativeUI.alert("获取订单信息失败！",null,"支付结果");
		    });
			
		}else{
			mui.toast('请选择支付方式');
		}
		
	});

</script>
</body>
</html>