var bgImgName = "";
var getUrl = "userapi.asp";
$(document).ready(function(){
	$("#b-left").click(function(){	//输入和菜单切换
		bgImgName = $("#b-leftimg").attr("src").toLowerCase();
		//alert(bgImgName);
		if(bgImgName=="userimg/b_left_1.png"){
			$("#dock-wrapper").animate({bottom:'-50px'},500,function(){
				$("#b-leftimg").attr("src","userimg/b_left_2.png");	
				$("#menulist").css({"display":"none"});
				$("#enterlist").css({"display":"block"});
			});
			$("#dock-wrapper").animate({bottom:'0'},500);			
		}else{
			$("#dock-wrapper").animate({bottom:'-50px'},500,function(){
				$("#b-leftimg").attr("src","userimg/b_left_1.png");	
				$("#menulist").css({"display":"block"});
				$("#enterlist").css({"display":"none"});
			});
			$("#dock-wrapper").animate({bottom:'0'},500);
		}
	});//输入和菜单切换结束	
	$("#enterright").click(function(){//发送文本信息
		var textc = $("#userContent").val();
		if(textc==""){alert("请输入内容后再发送！");return;}//为空时不发送
		$("#mainbody").append("<div id=\"vtext2\"><div id=\"vtext-left2\"><img width=\"45px\" src=\"userimg/me.png\" /></div><div id=\"vtext-mid2\"><span>" + textc + "</span></div></div>");//显示发送的内容
		var reg=new RegExp("&","g");
		var textc = textc.replace(reg,"$");
		textc = encodeURI(textc);
		getClick("gtext&kw=" + textc);	
		$("#userContent").val("");
	});//发送文本信息
});
function menushow(menuid){
	if(menuid==1){
		$("#menulist1").toggle();
		$("#menulist2").hide();
		$("#menulist3").hide();
	}
	if(menuid==2){
		$("#menulist2").toggle();
		$("#menulist1").hide();
		$("#menulist3").hide();
	}
	if(menuid==3){
		$("#menulist3").toggle();
		$("#menulist2").hide();
		$("#menulist1").hide();
	}
}
function menuhide(){
	$("#menulist1").hide();
	$("#menulist2").hide();
	$("#menulist3").hide();
}
function getView(menuString){
	location.href="http://union.66666999.com/api/weixin/tiaozhuan.asp?id=" + menuString;
}
function getClick(menuString){
	var menuS = menuString.toLowerCase();
	var menuS2 = "";
	var myDate = new Date();  
	$("#imgbottom").css({"display":"block"});
	switch(menuS){
		case "login":menus2	= "?st=login&username=" + $("#username").val() + "&password1=" + $("#password1").val();break;//登陆
		case "regester":menus2	= "?st=regester";break;//发送注册框
		case "regester2":menus2	= encodeURI("?st=regester2&rrealname=" + $("#rrealname").val() + "&rusername=" + $("#rusername").val() + "&tuijianren=" + $("#tuijianren").val() + "&alipayc=" + $("#alipayc").val())+ "&rpassword1=" + $("#rpassword1").val() + "&rpassword2=" + $("#rpassword2").val();break;//注册
		default:menus2	= encodeURI("?st=" + menuString);break;
	}
	//alert(encodeURI(getUrl + menus2));
	$.get(getUrl + menus2 + "&" + Math.random(),function(data,status){
		if(status=="success"){
			if(data.indexOf("LoginStyle")>0){$("#mainbody").html("");}
			$("#mainbody").append(data);
		}else{
			alert("数据错误，请重试！");
		}
		$("#imgbottom").css({"display":"none"});
		toTopScroll();
	});
}
function toTopScroll(){
	setTimeout("getBobyHeight()",100)	
}
function getBobyHeight(){
	var scrollTop =	$(document.body).height();
	$(document.body).scrollTop(scrollTop);
}