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
					<a href="<?php echo U('Adgoods/adgoodsAdd');?>" class="btn">添加广告商品</a>
					<!-- <?php if($search['is_del'] != 1): ?><button class='btn' onclick="delModels(0)"> 批量上架  </button>
					<button class='btn' onclick="delModels(2)"> 批量下架  </button>
					<button class='btn' onclick="delModels(1)"> 批量删除  </button>
					<?php else: ?>
					<button class='btn' onclick="delModels(-1)"> 批量删除  </button>
					<button class='btn' onclick="delModels(0)"> 批量恢复删除  </button><?php endif; ?> -->
					
				</td>
				<td align="right">
				<!-- <form action="<?php echo U('Product/productList');?>" method="post" name="searchForm" id="quickForm">
			      <IMG src="/mlbspa/manage/Public/images/icon_search.gif" align="absmiddle">
			      
			      &nbsp;
			       <input placeholder="你要搜索的商家名称" id="newName" class="input" type="text" name="name" value="" />
			      &nbsp;
			      <button class="btn quickSubmit">搜索</button>
			    </form> -->
    			</td>
			</tr>
		</tbody>	
    </table>
    
    <table width="99.5%" border="0" cellpadding="0" cellspacing="0" class="tab">
    	<colgroup>
  			<col width="60px"/>
			<col/>
			<col/>
			<col/>
			<col/>
			<col width="100px"/>
		</colgroup>
      <thead>
        <tr align="center">
          <td>选择</td>
          <td>广告商品名称</td>
          <td>商家</td>
          <td>兑换分数</td>
          <td>总积分</td>
          <td>是否可兑换</td>
          <td>操作</td>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($AdgoodsList)): $i = 0; $__LIST__ = $AdgoodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo['product_id']); ?>">
            <td align="center"><input name="del_id[]" type="checkbox" value="<?php echo ($vo['adgoods_id']); ?>" /></td>
            <td><?php echo ($vo['adgoods_name']); ?></td>
            <td><?php echo ($vo['merchant_name']); ?></td>
            <td align="center"><?php echo ($vo['point']); ?></td>
            <td align="center"><?php echo ($vo['total_point']); ?></td>
            <td align="center">
            	<?php if(($vo['total_point']) <= "0"): ?>可兑换
            	<?php else: ?>
            		广告中<?php endif; ?>
            </td>
            <td align="center">
			<?php if($search['is_del'] == 1): ?><!-- <a  href='javascript:updateShelf(<?php echo ($vo["product_id"]); ?>,0,"恢复")'>[恢复删除]</a>
				<a   href='javascript:productRecyleDel(<?php echo ($vo["product_id"]); ?>)'>[强力删除]</a> -->
			<?php else: ?>
				<a href='<?php echo U("Adgoods/adgoodsEdit","id=".$vo['adgoods_id']);?>'>[编辑]</a> 
				<!-- <a href="javascript:void(0)" onclick='delModel({link:"<?php echo U("Product/productDel","id=".$vo["product_id"]) ?>"})'>[删除]</a> --><?php endif; ?>
			</td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
    <form action="" method="post" id="opForm">
      <input id="cid" type="hidden" name="data[class_id]" />
      <input id="act" type="hidden" name="act" />
      <input id="pid" type="hidden" name="data[pid]" />
      <input id="name" type="hidden" name="data[class_name]" />
    </form>
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