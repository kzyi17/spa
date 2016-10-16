<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>收益明细</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <style>
   		.my-table{list-style: none;padding: 0 0px;}
   		.my-table .my-table-row{
		    width: 100%;
		    font-size: 14px;
   		}
   		.my-table .my-table-row .my-table-row-cell{
   			display: inline-block;
   			line-height: 28px;
   		}
   		.my-table .my-table-row .my-table-row-cell.tr1{
   			width: 30%;
   			text-align: center;
   		}
   		.my-table .my-table-row .my-table-row-cell.tr2{
   			width: 45%;
   			text-align: center;
   		}
   		.my-table .my-table-row .my-table-row-cell.tr3{
   			width: 25%;
   			text-align: center;
   		}
   		.my-table .my-table-row-th{
   			border: 1px solid #777777;
   			line-height: 38px;
   			margin-bottom: 10px;
   			font-size: 16px;
   		}
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav myheader">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 class="mui-title">收益明细</h1>
</header>
<div class="mui-content"> 
	<ul class="my-table mt20">
		<li class="my-table-row my-table-row-th"><span class ="my-table-row-cell tr1">时间</span><span class="my-table-row-cell tr2">来源</span><span class="my-table-row-cell tr3">收益</span></li>
	</ul> 
	<ul class="my-table" id="shareLog">
	</ul> 
	
</div>
</body>
<script id="shareLogTpl" type="text/html"> 
	<%for(i = 0; i < list.length; i ++) {%>
	<li class="my-table-row">
		<span class ="my-table-row-cell tr1 mui-ellipsis"><%=dateFormat(list[i]['create_time'],'Y-M-d') %></span><span class="my-table-row-cell tr2 mui-ellipsis"><%=list[i]['log_info']%></span><span class="my-table-row-cell tr3 mui-ellipsis"><%=list[i]['point']%>积分</span>
	</li>
	<%}%>
</script>
<script src="js/mui.min.js"></script>
<script src="js/api.js"></script> 
<script src="js/app.js"></script>
<script src="js/arttmpl.js" type="text/javascript" charset="utf-8"></script>
<script src="js/template.helper.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
(function($) { 
	
	$.ready(function(){
		var user_id = "<?php echo ($user_id); ?>";
		
		Api.getShareLog({user_id:user_id},function(res){
			document.getElementById("shareLog").innerHTML = template('shareLogTpl', {list:res});
			//console.log(JSON.stringify(res));
		});
	});

})(mui);	
</script>
</html>