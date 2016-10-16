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
    .title{
		margin: 20px 15px 7px;
		color: #6d6d72;
		font-size: 15px;
	}
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">新手学堂</h1>
</header>
<div class="mui-content"> 
	<div class="title">
		新手学堂
	</div>
	<ul class="mui-table-view" id="guideList">
	</ul>
	
</div>
</body>
<script id="guideListTpl" type="text/html"> 
	<%for(i = 0; i < list.length; i ++) {%>
		<li class="mui-table-view-cell" id="<%=list[i]['article_id']%>">【<%=list[i]['title']%>】</li>
    <%}%>
</script> 	
<script src="js/mui.min.js"></script>
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/api.js"></script> 
<script type="text/javascript" charset="utf-8">
(function($) {
	$.init();
	$.ready(function(){
		param = {limit:10};
		Api.guideList(param,function(res){
			document.getElementById("guideList").innerHTML = template('guideListTpl',{list:res});
			console.log(JSON.stringify(res));
		});
		$('.mui-table-view').on('tap','li',function(e){
			var id = this.getAttribute("id");
			var url = "<?php echo U('share/guideDetail');?>" + "?id="+id;
			window.location = url;
		});
	});	
})(mui);
</script>
</html>