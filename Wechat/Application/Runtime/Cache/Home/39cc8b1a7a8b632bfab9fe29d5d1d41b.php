<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>邀请好友</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
    	.tt{
    		font-size: 18px;
    		line-height: 40px;
    		color: #FF0000;
    		font-weight: bold;
    		padding: 10px 10px;
    	}
    	
    	.dd{
    		font-size: 16px;
    		line-height: 36px;
    		padding: 0 10px;
    	}
    	
    	.share{
    		text-align: center;
    		padding: 20px 0;
    	}
    	.share button{
    		padding: 10px 40px;
    	}
    	.code{
    		text-align: center;
    		padding: 20px 0;
    	}
    	
    	.code img{
			width: 50%;
		}    	
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">邀请好友</h1>
</header>
<div class="mui-content"> 
	<div class="tt">您的推广二维码：</div>
	<div class="code">
		<img src="images/80x80.png" id="urlImg"/>
	</div>
	<div class="dd">邀请成功将获得10积分奖励，以及永久获得小伙伴收益10%的额外分成。</div>
	<div class="share">
		<button type="button" class="mui-btn mui-btn-red" id="share">立即分享</button>
	</div>
</div>
</body>
<script src="js/mui.min.js"></script>
<script src="js/template.js"></script>
<script src="js/api.js"></script> 
<script src="js/app.js"></script>
<script type="text/javascript" charset="utf-8">
(function($) {
	$.init(); 
	var shares = {};
	var userId;
	$.ready(function(){
		userId = "<?php echo ($user_id); ?>";
		var shareUrl = "http://qr.topscan.com/api.php?text=http://o2o.szwzlm.com/weixin/user/reg?userid="+userId;
		document.getElementById("urlImg").src = shareUrl;
		
//		plus.share.getServices(function(s) {
//			if (s && s.length > 0) {
//				for (var i = 0; i < s.length; i++) {
//					var t = s[i];
//					shares[t.id] = t;
//				}
//			}
//		}, function() {
//			console.log("获取分享服务列表失败");
//		});
	});
	//推荐好友
	document.getElementById('share').addEventListener('tap', function(){
		var ids = [{
				id: "weixin",
				ex: "WXSceneSession"
			}, {
				id: "weixin",
				ex: "WXSceneTimeline"
			}, {
				id: "qq"
			}],
			bts = [{
				title: "发送给微信好友"
			}, {
				title: "分享到微信朋友圈"
			}, {
				title: "分享到QQ"
			}];
		plus.nativeUI.actionSheet({
			cancel: "取消",
			buttons: bts
		}, function(e) {
			var i = e.index;
			if (i > 0) {
				var s_id = ids[i - 1].id;
				var share = shares[s_id];
				if (share) { 
					if (share.authenticated) {
						shareMessage(share, ids[i - 1].ex);
					} else {
						share.authorize(function() {
							shareMessage(share, ids[i - 1].ex);
						}, function(e) {
							console.log("认证授权失败：" + e.code + " - " + e.message);
						});
					}
				} else {
					mui.toast("无法获取分享服务，请检查manifest.json中分享插件参数配置，并重新打包")
				}
			}
		});	
	}, false);
	function shareMessage(share, ex) {
		var msg = {
			extra: {
				scene: ex
			}
		};
		
		msg.href = "http://mlb.mywork99.com/weixin/index/register?userid="+userId;
		msg.title = "分享推荐好友";
		msg.content = "美联帮会员推荐";
		/*if (~share.id.indexOf('weibo')) {
			msg.content += "；体验地址：http://www.dcloud.io/hellomui/";
		}*/
		msg.thumbs = ["images/80x80.png"];
		share.send(msg, function() {
			console.log("分享到\"" + share.description + "\"成功！ ");
		}, function(e) {
			console.log("分享到\"" + share.description + "\"失败: " + e.code + " - " + e.message);
		});
	}
})(mui);	
</script>
</html>