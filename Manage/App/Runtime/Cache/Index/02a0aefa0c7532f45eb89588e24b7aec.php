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
    <script language="javascript" type="text/javascript" src="/mlbspa/manage/Public/js/datePicker/WdatePicker.js"></script>
    <table width="99.5%" border="0" cellspacing="0" cellpadding="0" class="tab">
      <tr colspan="12">
        <div style="margin-bottom:10px;">
          <form action="" method="post">
            关键字：
            <input type="text"  class="input" name="keywords" style="width:100px;" value="<?php echo ($keywords); ?>"/>
            <input type="submit" value="搜索" class="btn submit" style="margin-left:20px;"/>
            <input type="button" value="新增管理员" class="btn submit" onclick="javascript:addNewUser();" style="margin-left:20px;"/>
          </form>
         <div style="float:right;"><a href="/mlbspa/manage/Auth/userType" class="btn">管理员类型</a></div>
        </div>
      </tr>
      <thead>
        <tr>
          <td width="6%">ID</td>
          <td width="16%">类型</td>
          <td width="12%">用户名</td>
		  <td width="12%">管理员姓名</td>
          <td width="24%">开通时间</td>
          <td width="10%">状态</td>
          <td width="">操作</td>
        </tr>
      </thead>
      <form action="" method="post" >
        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr align="center" id="<?php echo ($vo["uid"]); ?>">
            <td><?php echo ($vo["uid"]); ?></td>
            <td><?php echo ($vo["typeName"]); ?></td>
            <td><?php echo ($vo["user_code"]); ?></td>
			<td><?php echo ($vo["user_name"]); ?></td>
            <td><?php echo (date('Y-m-d H:i',$vo["create_time"])); ?></td>
            <td><?php echo ($vo["statusTxt"]); ?></td>
            <td><?php if($vo["uid"] > 1): ?>[ <a title="" href="javascript:void(0);" class="opStatus"  val="<?php echo ($vo["status"]); ?>"><?php echo ($vo["chStatusTxt"]); ?></a> ][ <a href="javascript:addNewUser(<?php echo ($vo["uid"]); ?>);">编辑 </a> ] [ <a onclick="delModel('/mlbspa/manage/Auth/AuthDel/id/<?php echo ($vo["uid"]); ?>')" href="javascript:void(0);"  >删除 </a> ]<?php endif; ?></td>

          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </form>
      <tr align="center">
        <td colspan="12"><?php echo ($page); ?></td>
      </tr>
    </table>
  </DIV>
</DIV>
</DIV>
<DIV class=clear></DIV>
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

</BODY></HTML>
<script type="text/javascript">

$(function(){
	//快捷启用禁用操作
	$(".opStatus").click(function(){
		var obj=$(this);
		var id=$(this).parents("tr").attr("id");
		var status=$(this).attr("val");
		$.getJSON("/mlbspa/manage/Auth/opUsersStatus", { uid:id, status:status }, function(json){
			if(json.status==1){
				//popup.success(json.info);
				$(obj).attr("val",json.data.status).html(status==1?"启用":"禁用").parents("td").prev().html(status==1?'<font color="red">禁用</font>':"启用");
			}else{
				popup.alert(json.info);
			}
		});
	});
});


//删除指定id管理员
function delModel(url){
	if(url!=null){
		popup.confirm('删除管理员','你确定要删除吗',function(action){
			if(action == 'ok'){
			 $.ajax({
				 type: "GET",
				 url: url,
				 dataType: "json",
				success: function(json){
					if(json.status==1){
						popup.alert(json.info);
						window.location.reload();
						return false;
					}else{
						popup.alert(json.info);
						
						return false;
					}
				}
			 });
			}
		});
		//删除多个
	}else{

	}
}
function addNewUser(user_id){
	var url = "/mlbspa/manage/Auth/AuthEdit/id/@user_id@";
	url = url.replace('@user_id@',user_id?user_id:0);
	art.dialog.open(url,{
		id:'addRuleWin',
	    title:'管理员设置',
	    okVal:'确定',
	    ok:function(iframeWin, topWin){
	    	var formObject = iframeWin.document.forms['UserForm'];
	    	var user_code = $(iframeWin.document).find('input[name="data[user_code]"]').val();
			var password = $(iframeWin.document).find('input[name="data[user_password]"]').val();
			var uid = $(iframeWin.document).find('input[name="data[uid]"]').val();
			var password2 = $(iframeWin.document).find('input[name="repassword"]').val();
			var admin_type = $(iframeWin.document).find('input[name="admin_type"]').val();
			if( (uid=='' && checkUsercode(user_code) && checkPassword(password,password2)) || (uid!='' && password == password2)){
			   $.post("/mlbspa/manage/Auth/AuthEdit", $(formObject).serialize(), function(json){

					if(json.status == 1)
					{
						popup.alert(json.info);
						window.location.reload();
						return true;
					}
					else
					{
						popup.alert(json.info);
						return false;
					}
				},'json');
	   	 	}else{
	   	 		if(!checkPassword(password,password2))
	   	 			$(iframeWin.document).find('input[name="repassword"]').next('label').html('密码不能为空或不一致');
	   	 		if(!checkUsercode(user_code))
	   	 			$(iframeWin.document).find('input[name="data[user_code]"]').next('label').html('用户名不能为空或不符合');
	   	 		return false;
	   	 	}
	    }
	});
}
//检测用户名
function checkUsercode(user_code){
	if(user_code =='')
		return false;
	return true;
}
//检测密码
function checkPassword(pass,repass){
	if(pass =='' || repass =='')
		return false;
	if(pass != repass)
		return false;
	return true;
}
</script>