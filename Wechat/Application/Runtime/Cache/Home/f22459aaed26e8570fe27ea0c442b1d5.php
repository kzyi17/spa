<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>开始赚钱</title>
<base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
<link href="css/mui.min.css" rel="stylesheet"/>
<link href="css/style.css" rel="stylesheet"/>
<style>
	html,
	body {
		background-color: #efeff4;
	}
	.mui-bar~.mui-content .mui-fullscreen {
		top: 44px;
		height: auto;
	}
	.mui-pull-top-tips {
		position: absolute;
		top: -20px;
		left: 50%;
		margin-left: -25px;
		width: 40px;
		height: 40px;
		border-radius: 100%;
		z-index: 1;
	}
	.mui-bar~.mui-pull-top-tips {
		top: 24px;
	}
	.mui-pull-top-wrapper {
		width: 42px;
		height: 42px;
		display: block;
		text-align: center;
		background-color: #efeff4;
		border: 1px solid #ddd;
		border-radius: 25px;
		background-clip: padding-box;
		box-shadow: 0 4px 10px #bbb;
		overflow: hidden;
	}
	.mui-pull-top-tips.mui-transitioning {
		-webkit-transition-duration: 200ms;
		transition-duration: 200ms;
	}
	.mui-pull-top-tips .mui-pull-loading {
		/*-webkit-backface-visibility: hidden;
		-webkit-transition-duration: 400ms;
		transition-duration: 400ms;*/
		margin: 0;
	}
	.mui-pull-top-wrapper .mui-icon,
	.mui-pull-top-wrapper .mui-spinner {
		margin-top: 7px;
	}
	.mui-pull-top-wrapper .mui-icon.mui-reverse {
		/*-webkit-transform: rotate(180deg) translateZ(0);*/
	}
	.mui-pull-bottom-tips {
		text-align: center;
		background-color: #efeff4;
		font-size: 15px;
		line-height: 40px;
		color: #777;
	}
	.mui-pull-top-canvas {
		overflow: hidden;
		background-color: #fafafa;
		border-radius: 40px;
		box-shadow: 0 4px 10px #bbb;
		width: 40px;
		height: 40px;
		margin: 0 auto;
	}
	.mui-pull-top-canvas canvas {
		width: 40px;
	}
	.mui-slider-indicator.mui-segmented-control {
		background-color: #efeff4;
	}
	/*.mui-control-content .mui-loading {
		margin-top: 40px;
	}*/
</style>
</head>

<body>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">开始赚钱</h1>
</header>
<div class="mui-content">
	<div id="slider" class="mui-slider mui-fullscreen">
		<div class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
			<div id="segmentedControls" class="mui-scroll">
			
			</div>
		</div>
		<div id="segmentedControlContents" class="mui-slider-group" >
			<div class="mui-slider-item mui-control-content mui-active">
				<div class="mui-scroll-wrapper">
					<div class="mui-scroll">
						<div class="mui-loading">
							<div class="mui-spinner">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/mui.min.js"></script>
<script src="js/mui.pullToRefresh.js"></script>
<script src="js/mui.pullToRefresh.material.js"></script>
<script src="js/api.js"></script> 
<script src="js/until.js"></script> 
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/template.helper.js" type="text/javascript" charset="utf-8"></script>
<script>
mui.init();
(function($) {
	//阻尼系数
	var deceleration = mui.os.ios?0.003:0.0009;
	
	var controlsElem = document.getElementById("segmentedControls");
	var contentsElem = document.getElementById("segmentedControlContents");
	
	$.ready(function() {
		param = {limit:20};
		Api.shareArticleCate(param,function(res){
			//console.log(JSON.stringify(res));
			var controlsHtml = [];
			var contentsHtml = [];
			var pages = [];
			for(i = 0; i < res.length; i ++){
				controlsHtml.push(template('cateListTpl',res[i]));
				contentsHtml.push(template('contentsItemTpl',res[i]));
				page = 1;
				pages.push(page);
			}
			controlsElem.innerHTML = controlsHtml.join('');
			contentsElem.innerHTML = contentsHtml.join('');
			
			//默认第一个高亮
			controlsElem.querySelector('.mui-control-item').classList.add('mui-active');
			contentsElem.querySelector('.mui-control-content').classList.add('mui-active');
			setTimeout(function() {
				contentsElem.querySelector('.mui-control-content').querySelector('.mui-loading').remove();
				firstPullEl = contentsElem.querySelector('.mui-scroll');
				$(firstPullEl).pullToRefresh().pullUpLoading();
			},1000);
			
			//初始化滚动组件
			$('.mui-scroll-wrapper').scroll({
				bounce: false,
				indicators: true, //是否显示滚动条
				deceleration:deceleration
			});
			
			var controlListElem = controlsElem.querySelectorAll('.mui-control-item');
			var contentListElem = contentsElem.querySelectorAll('.mui-control-content');
			var currentTab=0;
			
			//监听滑动tab点击事件
			document.getElementById('slider').addEventListener('slide', function(e) {
				currentTab = e.detail.slideNumber;
				currentElem= contentListElem[currentTab];
				if (currentElem.querySelector('.mui-loading')) {
					setTimeout(function() {
						currentElem.querySelector('.mui-loading').remove();
						currentPullEl = currentElem.querySelector('.mui-scroll');
						$(currentPullEl).pullToRefresh().pullUpLoading();
					}, 500);
				}
			});
			
			//循环初始化所有下拉刷新，上拉加载。
			$.each(document.querySelectorAll('.mui-slider-group .mui-scroll'), function(index, pullRefreshEl) {
				$(pullRefreshEl).pullToRefresh({
					up: {
						callback: function() {
							var self = this;  
							var ul = self.element.querySelector('.mui-table-view');
							var currentElem= contentListElem[currentTab];
							var cateId = currentElem.id.substring(5);
							var page = pages[currentTab];
							var limit = 10;
							var params = {limit:limit,page:page,cate_id:cateId};
							setTimeout(function() {
								Api.shareArticleList(params,function(res){
									if(res.length>0){
										ul.appendChild(createFragment(res));
										if(res.length<limit){
											self.endPullUpToRefresh(true);
										}else{
											self.endPullUpToRefresh();
											++pages[currentTab];
										}
									}else{
										self.endPullUpToRefresh(true);
									}
								},function(res){
									self.endPullUpToRefresh(true);
		    						$.toast(res.errmsg);
								});
							}, 1500);	
							
						}
					}
				});
			});
			
			//列表项点击事件
			$('.mui-table-view').on('tap','li',function(e){
				var id = this.getAttribute("id");
				var url = "<?php echo U('share/newsDetail');?>" + "?id="+id;
				window.location = url;
				//console.log(id);
			});
			
		});
		
		var createFragment = function(listData) {
			var fragment = document.createDocumentFragment();
			for(i = 0; i < listData.length; i ++){
				tmpHtml = template('articleListTpl',{article:listData[i]});
				tmpElement = parseStringToHTML(tmpHtml)
				fragment.appendChild(tmpElement);
			}
			return fragment;
		};
		
	});
})(mui);
</script>
<script id="cateListTpl" type="text/html"> 
	<a class="mui-control-item" href="#cate_<%=cate_id%>"><%=cate_name%></a>
</script>
<script id="contentsItemTpl" type="text/html"> 
	<div id="cate_<%=cate_id%>" class="mui-slider-item mui-control-content">
		<div class="mui-scroll-wrapper">
			<div class="mui-scroll">
				<div class="mui-loading">
					<div class="mui-spinner">
					</div>
				</div>
				<ul class="mui-table-view">
				</ul>	
			</div>
		</div>
	</div>
</script>
<script id="articleListTpl" type="text/html"> 
	<li class="mui-table-view-cell mui-media" id="<%=article['article_id']%>">
		<a href="javascript:;">
			<%if(article['cover']){%>
				<img class="mui-media-object mui-pull-right" src="<%=article['_url']+article['cover']%>">
			<%}else{%>
				<img class="mui-media-object mui-pull-right" src="images/nopic.jpg">
			<%}%>
			<div class="mui-media-body">
				<%=article['title']%>
				<p class='mui-ellipsis'>阅读量：<%=article['hits']%><span class="mui-pull-right"><%=dateFormat(article['create_time'],'M-d') %></span></p>
			</div>
		</a>
	</li>
</script>
</body>
</html>