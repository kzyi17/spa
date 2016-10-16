<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>新手学堂</title>
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
    }
    
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">新手学堂</h1>
</header>
<div class="mui-content"> 
	<div id="articleInfo"></div>
</div>
</body>
<script id="articleInfoTpl" type="text/html"> 
	<div class="news-title"><%=article.title%></div>
	<div class="news-contnet">
		<%=#article.content%>
	</div>
</script>
<script src="js/mui.min.js"></script>
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/api.js"></script> 
<script type="text/javascript" charset="utf-8">
(function($) {
	$.init();
	$.ready(function(){
		var article_id = "<?php echo ($id); ?>";
		var param = {article_id:article_id};
		Api.shareArticleInfo(param,function(res){
			console.log(JSON.stringify(res));
			document.getElementById("articleInfo").innerHTML = template('articleInfoTpl', res);
		});
	});
})(mui); 
</script>
</html>