<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>用户中心</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css" />
    <style>
    	.mui-table-view {
			margin-top: 20px;
		}
		.mui-table-view:after {
			height: 0;
		}
		
		.mlbbg{ background-color: #f61d67; color: #FFFFFF;}
    	.mlbbg p{color: #FFFFFF;}
    	.mlbbg .mui-table-view-cell,.mlbbg .mui-table-view-cell a{height: 100px;}
    	.mlbbg .mui-media-object {
		    line-height: 80px;
		    max-width: 80px;
		    height: 80px;
		}
		.userinfo_text{ line-height: 40px;}
		.menu_icon img{width: 100%;}
		.mui-grid-view.mui-grid-9 .mui-table-view-cell{padding: 4px 8px;}
		.head-img{width: 80px!important;}
		
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo U('home/index');?>"></a>
    <h1 class="mui-title">用户中心</h1>
</header>	

<div class="mui-content">
	<div class="mui-scroll">
		<ul class="mui-table-view mui-table-view-chevron mlbbg" id="userinfo" style="margin-top: 0;">
			<li class="mui-table-view-cell mui-media" id="accountbtn">
			    <a class="mui-navigate-right">
					<img class="mui-media-object mui-pull-left head-img" id="head-img" src="<?php echo C('WEB_STATICS');?>/uploads/<?php echo ($userInfo["user_icon"]); ?>">
					<div class="mui-media-body userinfo_text">
						<p><?php echo ($userInfo["nickname"]); ?></p>
						<p><?php echo ($userInfo["phone"]); ?></p>
					</div>
				</a>
			</li>	
		</ul>
		<ul class="mui-table-view mui-grid-view mui-grid-9" id="info">
			
		</ul>
		<ul class="mui-table-view mui-table-view-chevron" >
			<!--<li class="mui-table-view-cell">
				<a id="order" class="mui-navigate-right" href="<?php echo U('home/about');?>">我的预约</a>
			</li>-->
			<!--<li class="mui-table-view-cell" >
				<a id="ticket" class="mui-navigate-right" href="<?php echo U('home/about');?>">劵包</a>
			</li>-->
			<li class="mui-table-view-cell" >
				<a id="exchange" class="mui-navigate-right" href="<?php echo U('user/exchange');?>">我的兑换</a>
			</li>
			<li class="mui-table-view-cell" >
				<a id="address" class="mui-navigate-right" href="<?php echo U('user/address');?>">用户地址</a>
			</li>
			<li class="mui-table-view-cell mui-collapse">
				<a id="" class="mui-navigate-right">我的好友</a>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a id="" class="mui-navigate-right">
							一级好友
							<span class="mui-pull-right" id="fclv1">0人</span>
						</a>
					</li>
					<li class="mui-table-view-cell">
						<a id="" class="mui-navigate-right">
							二级好友
							<span class="mui-pull-right" id="fclv2">0人</span>
						</a>
					</li>
					<li class="mui-table-view-cell">
						<a id="" class="mui-navigate-right">
							三级好友
							<span class="mui-pull-right" id="fclv3">0人</span>
						</a>
					</li>
				</ul>	
			</li>
			<!--<li class="mui-table-view-cell">
				<a id="share" class="mui-navigate-right">推荐好友</a>
			</li>-->
			<li class="mui-table-view-cell">
				<a id="withdraw" class="mui-navigate-right" href="<?php echo U('home/about');?>">提现</a>
			</li>
			<li class="mui-table-view-cell">
				<a id="about" class="mui-navigate-right" href="<?php echo U('home/about');?>">联系我们</a>
			</li>
		</ul>
		<ul class="mui-table-view">
			<li class="mui-table-view-cell" style="text-align: center;">
				<a id='exit'>退出</a>
			</li>
		</ul>
	</div>
</div>
<!--JSTPL-->
<script id="infoTpl" type="text/html">
	<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnOrderToShop">
		<a >
			<div class="menu_icon">
				{{info.account}}元
			</div>
			<div class="mui-media-body">我的余额</div>
		</a>
	</li>
	<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnOrderToHome">
		<a >
			<div class="menu_icon">
				0张
			</div>
			<div class="mui-media-body">我的优惠劵</div>
		</a>
	</li>
	<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnAdShop">
		<a>
			<div class="menu_icon">
				{{info.point}}分
			</div>
			<div class="mui-media-body">我的积分</div>
		</a>
	</li>
	<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" id="btnAdShop">
		<a>
			<div class="menu_icon">
				{{info.freeze_account}}元
			</div>
			<div class="mui-media-body">我的佣金</div>
		</a>
	</li>
</script>	
<script src="js/mui.min.js"></script>
<script src="js/api.js"></script>
<script src="js/app.js"></script>
<script src="js/template.js" ></script>
<script>
(function($, doc) {
	$.init();
	$.ready(function(){
		var param = {};
		param.user_id = <?php echo ($userInfo["user_id"]); ?>;
		Api.getMyInfo(param,function(result){
			var infodata = {};
			infodata.info = result;
			document.getElementById('info').innerHTML = template('infoTpl',infodata);
		});
		setTimeout(function () {
			Api.getUserfxc(param,function(result){
				console.log(JSON.stringify(result));
				document.getElementById('fclv1').innerText = result.lv1 + '人';
				document.getElementById('fclv2').innerText = result.lv2 + '人';
				document.getElementById('fclv3').innerText = result.lv3 + '人';
			});
		},600);
	});
	
	//退出操作******************
	document.getElementById('exit').addEventListener('tap', function() {
		$.confirm('确定退出帐号吗？','美联帮',['确定','取消'],function(e){
			if(e.index==0){
				$.ajax({
					type:"post",
					url:"<?php echo U('user/logout');?>",
					success:function(data){
						$.toast(data.info);
						setTimeout(function(){
							window.location = data.url;
						},2000);
					},
				});
			}
		});
	}, false);
	
	
	//推荐好友
	document.getElementById('share').addEventListener('tap', function(){
		//shareMessage(share, ids[i - 1].ex);
	}, false);
	
	function shareMessage(share, ex) {
//		var msg = {
//			extra: {
//				scene: ex
//			}
//		};
//		var state = app.getState();
//		var user_id = state.account.user_id;
//		
//		msg.href = "http://mlb.mywork99.com/weixin/index/register??userid="+user_id;
//		msg.title = "分享推荐好友";
//		msg.content = "美联帮会员推荐";
//		/*if (~share.id.indexOf('weibo')) {
//			msg.content += "；体验地址：http://www.dcloud.io/hellomui/";
//		}*/
//		msg.thumbs = ["images/80x80.png"];
//		share.send(msg, function() {
//			console.log("分享到\"" + share.description + "\"成功！ ");
//		}, function(e) {
//			console.log("分享到\"" + share.description + "\"失败: " + e.code + " - " + e.message);
//		});
	}
	
}(mui, document));	




//document.getElementById('about').addEventListener('tap', function() {
//	mui.openWindow({
//		url:'about.html',
//		id:'about.html', 
//		show:{
//	    	autoShow:true,
//	    },
//	    waiting:{
//	      	autoShow:true,//自动显示等待框，默认为true
//	      	title:'正在努力加载...',//等待对话框上显示的提示内容
//	    }
//	});
//}, false);
//我的预约事件
//document.getElementById('order').addEventListener("tap",function () {
//	mui.openWindow({
//		url:"myOrderList.html",
//	    id:"myOrderList",
//	    extras:{
////		    	city_id:2,
////	    	order_type:1,
////		    	cate_id:1,
//	    },
//	    waiting:{
//	      autoShow:true,//自动显示等待框，默认为true
//	      title:'正在加载...',//等待对话框上显示的提示内容
//	    }
//	});
//});
////券包
//document.getElementById('ticket').addEventListener('tap', function() {
//	mui.openWindow({
//		url:'ticket.html',
//		id:'ticket', 
//		show:{
//	    	autoShow:true,
//	    },
//	    waiting:{
//	      	autoShow:true,//自动显示等待框，默认为true
//	      	title:'正在努力加载...',//等待对话框上显示的提示内容
//	    }
//	});
//}, false);
////提现
//document.getElementById('withdraw').addEventListener('tap', function() {
//	mui.openWindow({
//		url:'withdraw.html',
//		id:'withdraw', 
//		show:{
//	    	autoShow:true,
//	    },
//	    waiting:{
//	      	autoShow:true,//自动显示等待框，默认为true
//	      	title:'正在努力加载...',//等待对话框上显示的提示内容
//	    }
//	});
//}, false);
//
////用户地址
//document.getElementById('address').addEventListener('tap', function() {
//	mui.openWindow({
//		url:'uc_address.html',
//		id:'uc_address', 
//		show:{
//	    	autoShow:true,
//	    },
//	    waiting:{
//	      	autoShow:true,//自动显示等待框，默认为true
//	      	title:'正在努力加载...',//等待对话框上显示的提示内容
//	    }
//	});
//}, false);



</script>

</body>
</html>