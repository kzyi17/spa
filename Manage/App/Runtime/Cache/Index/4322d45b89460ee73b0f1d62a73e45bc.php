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

<script type="text/javascript" src="/mlbspa/manage/Public/js/kindeditor/kindeditor-min.js"></script><script type="text/javascript" src="/mlbspa/manage/Public/js/kindeditor/lang/zh_CN.js"></script><script type="text/javascript" src="/mlbspa/manage/Public/js/artTemplate/artTemplate.js"></script><script type="text/javascript" src="/mlbspa/manage/Public/js/artTemplate/artTemplate-plugin.js"></script><script type="text/javascript" src="<?php echo C('WEB_STATICS');?>/js/form/form.js"></script>
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
      <DIV class=current><?php echo ($pagetitle); ?></DIV>
    </DIV>
    <div class="content_box">
        <form action="/mlbspa/manage/Share/adSave" name="merchantForm" method="post">
        	<input type="hidden" name="ad_id" value="<?php echo ($ad["ad_id"]); ?>" />
        	<div class="content form_content">
        		<div id="table_box_1">
        			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tab form_table">
                        <col width="120px" />
                        <col />
                        <tr>
							<td>广告名称：</td>
							<td><input class="normal" name="ad_name" type="text" value="<?php echo ($ad["ad_name"]); ?>" pattern="required" alt="名称不能为空" /><label>*</label></td>
						</tr>
						<tr>
							<td>投放位置：</td>
							<td>
								<select class="auto" name="pos_id">
                                    <option value="0">请选择</option>
                                    <?php if(is_array($adposList)): $i = 0; $__LIST__ = $adposList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pos): $mod = ($i % 2 );++$i;?><option value="<?php echo ($pos["pos_id"]); ?>" <?php echo ($ad['pos_id']==$pos['pos_id'] ? 'selected="selected"':''); ?>><?php echo ($pos["pos_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
	                            <label></label>
							</td>
						</tr>
						<tr>
							<td>链接产品：</td>
							<td>
								<select class="auto" name="sharegoods_id">
                                    <option value="0">请选择</option>
                                    <?php if(is_array($goodsList)): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pos): $mod = ($i % 2 );++$i;?><option value="<?php echo ($pos["sharegoods_id"]); ?>" <?php echo ($ad['sharegoods_id']==$pos['sharegoods_id'] ? 'selected="selected"':''); ?>><?php echo ($pos["goods_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
	                            <label></label>
							</td>
						</tr>
						<tr>
							<td>封面图片：</td>
							<td>
								<input type="hidden" name="cover" value="<?php echo ($article["cover"]); ?>" />
								<div id="cover" class="cover">
									<?php if(empty($article["cover"])): else: ?>
										<img src="<?php echo C('WEB_STATICS'); echo ($article["cover"]); ?>" style="margin-bottom:5px; opacity:1;width:120px;height:120px"><?php endif; ?>
								</div>
								<button class="btn cover_upload" type="button" onclick="myUploader()"><span class="add">上传图片</span></button>
								<!--图片模板-->
								<script type='text/html' id='coverTpl'>
									<img src="<?php echo C('WEB_STATICS');?><%=img%>" style="margin-bottom:5px; opacity:1;width:120px;height:120px">
                            	</script>
								<label></label>
							</td>
						</tr>
                    </table>
        		</div>
            </div>
            <div class="commonBtnArea" >
                <button class="btn submit" type="submit" onclick="return checkForm()">保存</button>  
            </div>
        </form>
    </div>

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

<script language="javascript">
//图片上传组件
function myUploader(){
	var tempUrl = "<?php echo U('popup/myuploader',array('uploadpath'=>'share','multi'=>'false'));?>";
	art.dialog.open(tempUrl,{
		title:'图片上传',
		okVal:'保存',
		ok:function(iframeWin, topWin){
			var uploadObject = $(iframeWin.document).find('[id^="up_"]');
			if(uploadObject.length == 0) return;
			
			var picJson = $.parseJSON($(uploadObject).find('input:hidden[name="specJson"]').val());
			$('input[name="cover"]').val(picJson.img);
			$('#cover').html(template.render('coverTpl',picJson));
			
		}
	});
}

//提交表单前的检查
function checkForm()
{
	//整理商品图片
	var goodsPhoto = [];
	$('#thumbnails img').each(function(){
		goodsPhoto.push(this.alt);
	});
	if(goodsPhoto.length > 0)
	{
		$('input[name="_imgList"]').val(goodsPhoto.join(','));
		$('input[name="photo_id"]').val($('#thumbnails img[class="current"]').attr('alt'));
	}
	return true;
}

</script>
</BODY></HTML>