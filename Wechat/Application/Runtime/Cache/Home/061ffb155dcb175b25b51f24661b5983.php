<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我的兑换</title>
    <base href="/mlbspa/weixin/<?php echo TMPL_PATH; echo MODULE_NAME;?>/" />
    <link href="css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css" />
    <style type="text/css">
    	.adr-row{padding-right: 20px;}
    	.adr-tlebar{
    		position: relative;
		    overflow: hidden;
		    padding: 11px 15px;
		    background-color: inherit;
    	}
    	.adr-tlebar:after {
		    position: absolute;
		    right: 0;
		    bottom: 0;
		    left: 15px;
		    height: 1px;
		    content: '';
		    -webkit-transform: scaleY(.5);
		    transform: scaleY(.5);
		    background-color: #c8c7cc;
	    }
	    .adr-title{
	    	color: #8f8f94; 
    		font-size: 12px;
	    }
	    .adr-red{
	    	color: #CF2D28; 
	    	padding: 0 8px;
	    	font-size: 12px;
	    }
	    .adr-def-btn{
	    	font-size: 12px;
	    	color: #666666;
	    }
	    .adr-del-btn{
	    	font-size: 12px;
	    	color: #666666;
	    }
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">我的兑换</h1>
</header>
<div class="mui-content">
	<div id="addressList"></div>
</div>
<script id="addressListTpl" type="text/html"> 
<%for(i = 0; i < list.length; i ++) {%>
	<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed mt10">
		<div class="mui-table adr-tlebar">
    		<div class="mui-table-cell mui-col-xs-9 mui-ellipsis"><span class="adr-title">兑换时间：<%=dateFormat(list[i]['exchange_time'],'Y-M-d h:m:s')%></span></div>
    		<div class="mui-table-cell mui-col-xs-5 mui-ellipsis mui-text-right">
    			<a class="adr-del-btn">
    			<%switch (list[i]['shipstatus']){
					case "0":
					  x="待发货";
					  break;
					case "1":
					  x="已经发货";
					  break;
					case "2":
					  x="已确认收货";
					  break;
				}%>
				<%=x%>
    			</a>
    		</div>
    	</div>
        <li class="mui-table-view-cell mui-media">
            <div class="adr-row mui-navigate-right">
            	<img class="mui-media-object mui-pull-left" src="images/nopic.jpg">
            	<div class="mui-media-body">
					<p class="mui-h5 mui-ellipsis"><%=list[i]['goods_name']%></p>
                	<p class="mui-h5 mui-ellipsis">消耗积分：<%=list[i]['exchange_point']%></p>
				</div>
            	
            </div>
        </li>
    </ul> 
<%}%>
</script>
<script src="js/mui.min.js"></script>
<script src="js/api.js"></script>
<script src="js/app.js"></script>
<script src="js/arttmpl.js"></script>
<script src="js/template.helper.js" ></script>
<script type="text/javascript">
(function($) {
	$.init();
	$.ready(function() {
		var param = {};
		param.user_id = <?php echo ($userInfo["user_id"]); ?>;
		Api.getUserExchangeList(param,function(res){
			console.log(JSON.stringify(res));
			var html = template('addressListTpl', {list:res});
			document.getElementById("addressList").innerHTML = html;
		},function(res){
			console.log(JSON.stringify(res));
		});
		
	});
	
}(mui));
</script>
</body>
</html>