<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<!-- saved from url=(0048)http://127.0.0.1/phpadmin/system.php/Index/index -->
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>总运营管理中心  - 美联帮</TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<SCRIPT type=text/javascript src="/mlbspa/manage/Public/js/base.js"></SCRIPT>
<link href="/mlbspa/manage/Public/css/base.css"  rel="stylesheet" type="text/css"/>
<link href="/mlbspa/manage/Public/css/layout.css"  rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo C('WEB_STATICS');?>/js/asyncbox/skins/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo C('WEB_STATICS');?>/js/artdialog/skins/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo C('WEB_STATICS');?>/js/autovalidate/style.css" />
<script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/jquery-1.9.0.min.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/jquery.JSON.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/jquery.form.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/functions.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/asyncbox/asyncbox.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/PCASClass.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/artdialog/artDialog.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/mlbspa/manage/Public/js/common.js"></script>

<META name=GENERATOR content="MSHTML 8.00.6001.23515">
</HEAD>

<BODY>
<DIV class=wrap>
<DIV id=Top>
  <DIV class=logo><A href="<?php echo C('WEB_WWW');?>" target="_blank"><IMG src="<?php echo C('WEB_STATICS');?>/public/logo.png"  border="0"></A></DIV>
  <DIV class=help><A href="#">使用帮助</A><SPAN><A href="<?php echo C('WEB_MOBILE');?>" target="_blank">官网</A></SPAN></DIV>
  <DIV class=menu>
    <ul>
      <?php echo ($menu); ?>
    </ul>
  </DIV>
</DIV>
<DIV id=Tags>
  <DIV class=userPhoto><IMG src="/mlbspa/manage/Public/images/userPhoto.jpg"> </DIV>
  <DIV class=navArea>
    <DIV class=userInfo>
      <DIV><A class=loginOut href="<?php echo U('Public/loginOut');?>" ><SPAN>&nbsp;</SPAN>退出系统</A></DIV>
      欢迎您，<?php echo ($my_info["user_code"]); ?>
      <!--| <A href="#">个人信息管理</A>--> | <A href="<?php echo U('Default/myInfo');?>">修改密码</A> | <A href="<?php echo U('Default/cache');?>">清除缓存</A></DIV>
      <DIV class=nav><FONT id=today>2014-03-27 09:44:25</FONT>您的位置：<?php echo ($currentNav); ?></DIV>
  </DIV>
</DIV>

<script type="text/javascript" src="/mlbspa/manage/Public/js/common.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/form/form.js"></script>
<DIV class=clear></DIV>
<DIV class=mainBody> <DIV id=Left>
  <DIV id=control></DIV>
  <div class="subMenuList">
        <div class="itemTitle"><?php if(CONTROLLER_NAME == 'Index'): ?>常用操作<?php else: ?>子菜单<?php endif; ?> </div>
        <?php if($isThreeMenu == 1): ?><ul>
            <?php if(is_array($sub_menu)): foreach($sub_menu as $key=>$secend): ?><div class="menuItemTitle"><?php echo ($secend["info"]); ?></div>
                <?php if(is_array($secend["list"])): foreach($secend["list"] as $key=>$sv): ?><li><a href="<?php echo ($sv["url"]); ?>"><?php echo ($sv["title"]); ?></a></li><?php endforeach; endif; endforeach; endif; ?>
        </ul>
        <?php else: ?>
        <ul>
            <?php if(is_array($sub_menu)): foreach($sub_menu as $key=>$sv): ?><li><a href="<?php echo ($sv["url"]); ?>"><?php echo ($sv["title"]); ?></a></li><?php endforeach; endif; ?>
        </ul><?php endif; ?>
        
  </div>
  <!--
  <DIV class=QRcode>移动设备访问本页：<BR>
    <BR>
    <IMG src="<?php echo ($QRcodeUrl); ?>"></DIV>-->
</DIV>

  <DIV id=Right>
    <DIV class="Item hr">
      <DIV class=current><?php echo ($action_name); ?></DIV>
    </DIV>
    <table width="99.5%">
    	<colgroup>
  			<col />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<button class='btn' id='checkbuttonall' onclick="selected()"> 全选  </button>
					<a href="<?php echo U('merchant/spaAdd');?>" class="btn">添加项目</a>
					<!-- <?php if($search['is_del'] != 1): ?><button class='btn' onclick="delModels(0)"> 批量上架  </button>
					<button class='btn' onclick="delModels(2)"> 批量下架  </button>
					<button class='btn' onclick="delModels(1)"> 批量删除  </button>
					<?php else: ?>
					<button class='btn' onclick="delModels(-1)"> 批量删除  </button>
					<button class='btn' onclick="delModels(0)"> 批量恢复删除  </button><?php endif; ?> -->
					
				</td>
				<td align="right">
				<form action="<?php echo U('merchant/spaList');?>" method="post" name="searchForm" id="quickForm">
			      <IMG src="/mlbspa/manage/Public/images/icon_search.gif" align="absmiddle">
			      <!-- &nbsp;
			      <select name="category_id">
			        <option value="0">选择分类</option>
			        <?php if(is_array($catList)): $i = 0; $__LIST__ = $catList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate["class_id"]); ?>"><?php echo ($cate["fullname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			      </select>
			      &nbsp;
			       <select name="is_del">
			        <option value="">选择上下架</option>
			        <option value="0">上架</option>
					<option value="2">下架</option>
					<option value="1">删除</option>
			      </select> -->
			      &nbsp;
			       <input placeholder="你要搜索的商家名称" id="newName" class="input" type="text" name="name" value="" />
			      &nbsp;
			      <button class="btn quickSubmit">搜索</button>
			    </form>
    			</td>
			</tr>
		</tbody>	
    </table>
    
    <table width="99.5%" border="0" cellpadding="0" cellspacing="0" class="tab">
    	<colgroup>
  			<col width="60px"/>
			<col/>
			<col/>
			<col width="120px"/>
			<col width="100px"/>
			<col width="90px"/>
			<col width="100px"/>
			<col width="100px"/>
		</colgroup>
      <thead>
        <tr align="center">
          <td>选择</td>
          <td>项目名称</td>
          <td>所属商家</td>
          <td>所属类目</td>
          <td>预约类型</td>
          <td>售价</td>
          <td>上架时间</td>
          <td>操作</td>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($spaList)): $i = 0; $__LIST__ = $spaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo['spa_id']); ?>">
            <td align="center"><input name="del_id[]" type="checkbox" value="<?php echo ($vo['spa_id']); ?>" /></td>
            <td><?php echo ($vo['spa_name']); ?></td>
            <td><?php echo ($vo['merchant_name']); ?></td>
            <td><?php echo ($vo['cate_name']); ?></td>
            <td align="center"><?php echo ($vo['order_type']==1 ? '预约到店':'预约上门'); ?></td>
            <td align="center">￥<?php echo ($vo['sale_price']); ?> <span style="color:#bbbbbb;text-decoration: line-through;">￥<?php echo ($vo['market_price']); ?></span></td>
            <td align="center"><?php echo date('Y/m/d',$vo['create_time']);?></td>
            <td align="center">
			<?php if($search['is_del'] == 1): ?><!-- <a  href='javascript:updateShelf(<?php echo ($vo["product_id"]); ?>,0,"恢复")'>[恢复删除]</a>
				<a   href='javascript:productRecyleDel(<?php echo ($vo["product_id"]); ?>)'>[强力删除]</a> -->
			<?php else: ?>
				<a href='<?php echo U("Merchant/spaEdit","id=".$vo['spa_id']);?>'>[编辑]</a> 
				<a href="javascript:void(0)" onclick='delModel({link:"<?php echo U("merchant/spaDel","id=".$vo["spa_id"]) ?>"})'>[删除]</a><?php endif; ?>
			</td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
    <div class="commonBtnArea" >
        <?php echo ($page); ?>
    </div>
  </DIV>
</DIV>
</DIV>
<DIV class=clear></DIV>
<script language="javascript">
//创建表单实例
var formObj = new Form('searchForm');
$(function()
{
	<?php if(($search)): ?>var searchForm = <?php echo json_encode($search);?>;
		formObj.init(searchForm);<?php endif; ?>
});
</script>
<DIV id=Bottom> <?php echo C('Copyright');?> </DIV>
<SCRIPT type=text/javascript>
    $(window).resize(autoSize);
    $(function(){
        autoSize();
        $(".loginOut").click(function(){
            var url=$(this).attr("href");
            popup.confirm('你确定要退出吗？','你确定要退出吗',function(action){
                if(action == 'ok'){ window.location=url; }
            });
            return false;
        });

        var time=self.setInterval(function(){$("#today").html(date("Y-m-d H:i:s"));},1000);


    });

</SCRIPT>
<SCRIPT type=text/javascript>
 //多选框全选
function selected()
{
	var allsel=document.getElementsByName("del_id[]");
	if(allsel.length==0){
		allsel=document.getElementsByName("check[]");
	}else{
		allsel=document.getElementsByName("del_id[]");
	}
	var ischeck = $("#checkall").is(":checked");
	var length = allsel.length;
	if(ischeck){
	   for(var i=0;i<allsel.length;i++)
	   {
			if(!allsel[i].checked){
				allsel[i].checked=!allsel[i].checked;
			}
	   }
   }else{
	for(var i=0;i<allsel.length;i++)
	{
		allsel[i].checked=false;
	}
   }
}
//按钮全选
var checktoggle = true;
 $("#checkbuttonall").on('click',function(){
	var allsel=document.getElementsByName("del_id[]");
	if(allsel.length==0){
		allsel=document.getElementsByName("check[]");
	}else{
		allsel=document.getElementsByName("del_id[]");
	}
	var length = allsel.length;
	if(checktoggle){
	   for(var i=0;i<allsel.length;i++)
	   {
			if(!allsel[i].checked){
				allsel[i].checked=!allsel[i].checked;
				checktoggle = false;
			}
	   }
   }else{
	for(var i=0;i<allsel.length;i++)
	{
		allsel[i].checked=false;
		checktoggle = true;
	}
   }
 });

 /**
  * 跳转
  */
 function popupalert(info,url){
 	art.dialog.tips(info, 1.5);
 	location.href =url;
 }
</script>


</SCRIPT>

<!-- <script>
function updateShelf(id,val,mytip){
	if(val==0){
		var tip = '上架';
	}else{
		var tip = '下架';
	}
	if(mytip!=''){
		tip = mytip;
	}
	popup.confirm(tip,'你确定要'+tip+'吗',function(action){
			if(action == 'ok'){
			 $.ajax({
				 type: "GET",
				 url: '<?php echo U('Product/updateShelf');?>?id='+id +'&value='+val,
				 dataType: "json",
				success: function(json){
					if(json.status==1){
						//popup.alert(json.info);
						window.location.reload();
						return false;
					}else{
						window.location.reload();
						return false;
					}
				}
			 });
			}
		});
}
//回收站删除，彻底删除
function productRecyleDel(id){
	popup.confirm('彻底删除商品','你确定要彻底删除商品吗',function(action){
			if(action == 'ok'){
			 $.ajax({
				 type: "GET",
				 url: '<?php echo U('Product/productRecyleDel');?>?id='+id ,
				 dataType: "json",
				success: function(json){
					if(json>=1){
						$('#'+id).remove();
						return false;
					}else{
						popup.alert(json.info);
						return false;
					}
				}
			 });
			}
		});
}
//删除批量id会员
function delModels(val){
	if(val==0){
		var tip = '上架';
	}else if(val==2){
		var tip = '下架';
	}else{
		var tip = '删除';
		
	}
	popup.confirm(tip,'你确定要'+tip+'所选吗',function(action){
		if(action == 'ok'){
			var delids = new Array();
			var ids = $('input[name="del_id[]"]');
			var j = 0;
			for(i=0;i<ids.length;i++){
				if(ids.eq(i).is(":checked")==true){
					delids[j] = ids.eq(i).val();
					j++;
				}
			}
			if(val==-1){
				var url = '<?php echo U('Product/productRecyleDelAll');?>?del_id=' + delids;
			}else{
				var url = '<?php echo U('Product/updateShelfAll');?>?del_id=' + delids + '&value='+val;
			}
			$.ajax({
				 type: "GET",
				 url: url,
				 dataType: "json",
				success: function(json){
					if((json.status==1 && val!=-1)|| (json>=1 && val==-1)){
						window.location.reload();
						//$("json.id").remove();
						return false;
					}else{
						popup.alert(json.info);
						return false;
					}
				}
			 });
		}
	});

}

</script> -->