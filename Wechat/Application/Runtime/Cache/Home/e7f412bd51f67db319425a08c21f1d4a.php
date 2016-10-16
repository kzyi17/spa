<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>文章内容</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
    .news-title{
    	text-align: center;
    	line-height: 40px;
    }
    .news-title-sub{
    	padding: 0 10px;
    }
    .news-contnet{
    	clear: both;
    	padding: 10px 10px;
    	overflow: hidden;
    } 
    .news-contnet img{
    	width: 100%!important;
    	height: auto!important;
    }
    .pt80{
    	padding-bottom: 80px;
    }
    .btadimg{
    	position: fixed;
    	bottom: 0;
    	height: 80px;
    	z-index: 9999;
    }
    .btadimg img{
		width: 100%;
		height: 80px;
	}
	.midad img{width: 100%;}    
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">文章内容</h1>
</header>
<div class="mui-content pt80"> 
	<div id="articleInfo"></div>
	<div>
		精彩推荐
	</div>
	<ul class="mui-table-view" id="rmdList">
		
	</ul>
</div>
</body>
<script id="articleInfoTpl" type="text/html"> 
	<div class="news-title"><%=article.title%></div>
	<div class="news-title-sub"><span class=""><%=dateFormat(article.create_time,'Y-M-d') %></span> <%if(ad_font.length>0){%><span class="ml10"><%=ad_font[0].ad_name%></span><%}%></div>
	<div class="news-contnet">
		<%=#article.content%>
	</div>
	<div>
		<span>阅读 <%=#article.hits%></span>
		<!--<span>点赞 1000</span>-->
	</div>
	<%if(ad_mid.length>0){%>
	<div class="midad"><img src="<%=ad_mid[0]['_url']+ad_mid[0]['content']%>" /></div>
	<%}%>
	<%if(ad_bottom.length>0){%>
	<div class="btadimg"><img src="<%=ad_bottom[0]['_url']+ad_bottom[0]['content']%>"/></div>
	<%}%>
</script>
<script id="rmdListTpl" type="text/html"> 
<%for(i = 0; i < list.length; i ++) {%>
	<li class="mui-table-view-cell mui-media" id="<%=list[i]['article_id']%>">
		<a href="javascript:;">
			<%if(list[i]['cover']){%>
				<img class="mui-media-object mui-pull-right" src="<%=list[i]['_url']+list[i]['cover']%>">
			<%}else{%>
				<img class="mui-media-object mui-pull-right" src="images/nopic.jpg">
			<%}%>
			<div class="mui-media-body">
				<%=list[i]['title']%>
				<p class='mui-ellipsis'>阅读量：<%=list[i]['hits']%><span class="mui-pull-right"><%=dateFormat(list[i]['create_time'],'M-d') %></span></p>
			</div>
		</a>
	</li>
<%}%>
</script>
<script src="js/mui.min.js"></script>
<script src="js/api.js"></script> 
<script src="js/app.js"></script>
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/template.helper.js" type="text/javascript" charset="utf-8"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" charset="utf-8">

(function($) {
	wx.config({
	    debug: false,
	    appId: '<?php echo ($signPackage["appId"]); ?>',
	    timestamp: <?php echo ($signPackage["timestamp"]); ?>,
	    nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
	    signature: '<?php echo ($signPackage["signature"]); ?>',
	    jsApiList: [
	      'onMenuShareTimeline',
	      'onMenuShareAppMessage',
	    ]
	});
	
	wx.ready(function () {
		
	});
	
	$.init();
	var shares = {};
	var userId,article_id;
	var title;
	var self;
	template.config('escape', false);
	$.ready(function(){
		article_id = "<?php echo ($id); ?>";
		var param = {article_id:article_id};
		Api.shareArticleInfo(param,function(res){
			document.getElementById("articleInfo").innerHTML = template('articleInfoTpl', res);
			title = res.article.title;
			document.title = title;
//			console.log(JSON.stringify(res));
			var shareData = {
				    title: title,
				    desc: title,
				    link: '<?php echo get_url();?>&uid=<?php echo ($user_id); ?>',
				    imgUrl: res._url + res.article.cover
				  };
		    wx.onMenuShareAppMessage(shareData);
		    wx.onMenuShareTimeline(shareData);
			
			
			//获得slider插件对象
			$('.mui-slider').slider({
			  	interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
			}); 
		});
		
		var state = app.getState();
		userId = "<?php echo ($user_id); ?>";
		setTimeout(function(){
			Api.getSharePoint({user_id:userId,article_id:article_id},function(res){
				//console.log(JSON.stringify(res));
				//$.toast(res.success);
			});
		},5000);
		
		Api.shareRmdArticleList({limit:10},function(res){
			document.getElementById("rmdList").innerHTML = template('rmdListTpl', {list:res});
		});
		
		$('.mui-table-view').on('tap','li',function(e){
			var id = this.getAttribute("id");
			var url = "<?php echo U('share/newsDetail');?>" + "?id="+id;
			window.location = url;
		});
		
	});
	
})(mui);	
</script>
</html>