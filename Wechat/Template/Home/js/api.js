/**
 * API接口调用
 * @author www.mywork99.com
 */
(function($, owner) {
	var ServerDomainRoot = "http://192.168.1.5/mlb/Api/";//接口服务器地址
	//var ServerDomainRoot = "http://o2o.szwzlm.com/api/";//接口服务器地址
	
	/**
	 * API接口调用底层
	 * @param {Object} URL
	 * @param {Object} RequestObject
	 * @param {Object} ResponseObject
	 */
	owner.RESTRequest = function(URL,RequestObject, ResponseObject){
		plus.nativeUI.showWaiting();
		var MyXMLHttpRequest = new plus.net.XMLHttpRequest();
		RequestObject = RequestObject || {};
		MyXMLHttpRequest.onreadystatechange = function() {
			switch (MyXMLHttpRequest.readyState) {
				case 0:
					console.log( "xhr请求已初始化" );
					break;
				case 1:
					console.log( "xhr请求已打开" );
					break;
				case 2:
					console.log( "xhr请求已发送" );
					break;
				case 3:
					console.log( "xhr请求已响应");
					break;
				case 4:
					if (MyXMLHttpRequest.status == 200) {
						var responseObject = JSON.parse(MyXMLHttpRequest.responseText || '[]');
						if(typeof(responseObject.errcode) == "undefined"){
							ResponseObject.Success(responseObject);
						}else{
							ResponseObject.Error(responseObject);
						}
						plus.nativeUI.closeWaiting();
					} else {
						plus.nativeUI.closeWaiting();
						setTimeout(function(){
							owner.ShowError("发生了通讯错误！");
						},1000);
					}
					break;
				}
		}
		MyXMLHttpRequest.open("POST", URL);
		MyXMLHttpRequest.setRequestHeader('Content-Type','application/json');
		MyXMLHttpRequest.send(JSON.stringify(RequestObject)); 
		
	};
	
	/**
	 * 显示错误
	 */
	owner.ShowError = function(msg){
		plus.nativeUI.toast(msg);
	};
	
	/**
	 * API接口调用底层(Mui Ajax)
	 * @param {Object} URL
	 * @param {Object} params
	 * @param {Object} onSuccess
	 * @param {Object} onError
	 * @param {Int} retry
	 */
	owner.MuiAjax = function(URL, params, onSuccess, onError, retry){
		var onSuccess = onSuccess || $.noop;
		var onError = onError || $.noop;
		var retry = arguments[4]?arguments[4]:3;
		$.ajax(URL, {
	        data:JSON.stringify(params),
	        dataType:'json',
	        type:'post',
	        timeout:3000,
	        success:function(data){
	        	if(typeof(data.errcode) == "undefined"){
					onSuccess(data);
				}else{
					onError(data);
				}
	        },
	        error:function(xhr,type,errorThrown){
	            retry--;
	            if(retry > 0) {
	            	console.log('try again connect：' + retry);
	            	return owner.MuiAjax(URL, params, onSuccess, onError, retry);
	            }	
	            owner.onError('FAILED_NETWORK');
	        }
	    })
	};
	
	owner.onError = function(errcode){
	    switch(errcode){
		    case 'FAILED_NETWORK':
		        $.toast('网络不佳');
		        break;
		    case 'INVALID_TOKEN':
		        wv_login.show();
		        break;
		    default:
		        console.log(errcode);
	    }
	};
	
	

	/**
	 * API调用工具
	 */
	owner.getApi = function(API,params,successCallback,errCallback,driver){
		var driver = driver || 'PLUSNET';
		var APIURL = ServerDomainRoot + API + "?token=asdqwe";
		successCallback = successCallback || $.noop;
		errCallback = errCallback || $.noop;
		params = params || {};
		
		//调试用
		console.log(APIURL);
		console.log(JSON.stringify(params));
		
		if(mui.os.plus){
  			var Response = new Object();
			Response.Success = function(responseObject) {
				console.log('Api request Success: ' + JSON.stringify(responseObject));
				successCallback(responseObject);
			}
			Response.Error = function(responseObject) {
				console.log('Api request Error: '+ JSON.stringify(responseObject));
				errCallback(responseObject);
			}
			
			owner.RESTRequest(APIURL,params, Response);
  		}else{
  			owner.MuiAjax(APIURL,params,successCallback,errCallback,3);
  		}
		
//		if(driver == 'PLUSNET'){
//			var Response = new Object();
//			Response.Success = function(responseObject) {
//				console.log('Api request Success: ' + JSON.stringify(responseObject));
//				successCallback(responseObject);
//			}
//			Response.Error = function(responseObject) {
//				console.log('Api request Error: '+ JSON.stringify(responseObject));
//				errCallback(responseObject);
//			}
//			
//			owner.RESTRequest(APIURL,params, Response);
//		}else if(driver == 'MUIAJAX'){
//			owner.MuiAjax(APIURL,params,successCallback,errCallback,3);
//		}
		
	};
	
//////////////////////////////////////////////////////////////
//-+-+-+-+-+-+-+-+-接口实现+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
//////////////////////////////////////////////////////////////

	/**
	 * 获取商家列表
	 * @param {Object} params POST参数
	 * @param {Object} successCallback 成功回调函数
	 * @param {Object} errCallback	失败回调函数
	 */
	owner.getMerchantList = function(params,successCallback,errCallback){
		owner.getApi('merchant/getlist',params,successCallback,errCallback);
	};
	
	/**
	 * 获取商家详情
	 * @param {Object} params POST参数
	 * @param {Object} successCallback 成功回调函数
	 * @param {Object} errCallback	失败回调函数
	 */
	owner.getMerchantInfo = function(params,successCallback,errCallback){
		owner.getApi('merchant/getinfo',params,successCallback,errCallback);
	};

	/**
	 * 获取美容项目列表
	 * @param {Object} params POST参数
	 * @param {Object} successCallback 成功回调函数
	 * @param {Object} errCallback	失败回调函数
	 */
	owner.getSpaList = function(params,successCallback,errCallback){
		owner.getApi('spa/getlist',params,successCallback,errCallback);
	};


	/**
	 * 获取商家相册
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getMerchantGallery = function(params,successCallback,errCallback){
		owner.getApi('merchant/getgallery',params,successCallback,errCallback);
	};
	
	/**
	 * 获取商家美容项目
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getMerchantSpa = function(params,successCallback,errCallback){
		owner.getApi('merchant/getspa',params,successCallback,errCallback);
	};
	
	
	
	/**
	 * 获取美容项目详情
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSpaInfo = function(params,successCallback,errCallback){
		owner.getApi('spa/getinfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取美容项目相册
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSpaGallery = function(params,successCallback,errCallback){
		owner.getApi('spa/getgallery',params,successCallback,errCallback);
	};
	
	/**
	 * 提交spa订单
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.spaOrderCommit = function(params,successCallback,errCallback){
		owner.getApi('order/spacommit',params,successCallback,errCallback);
	};
	
	/**
	 * 获取spa订单信息
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSpaOrderInfo = function(params,successCallback,errCallback){
		owner.getApi('order/spainfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取spa订单预支付信息
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSpaOrderPrePay = function(params,successCallback,errCallback){
		owner.getApi('order/spaprepay',params,successCallback,errCallback);
	};
	
	/**
	 * 获取广告首页商品列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getAdGoodsIndexList = function(params,successCallback,errCallback){
		owner.getApi('exchange/getindexlist',params,successCallback,errCallback);
	};
	
	/**
	 * 获取广告商品列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getAdGoodsList = function(params,successCallback,errCallback){
		owner.getApi('exchange/getlist',params,successCallback,errCallback);
	};
	
	/**
	 * 获取兑换商品列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getExchangeGoodsList = function(params,successCallback,errCallback){
		owner.getApi('exchange/getExchangeList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取广告商品详情
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getAdGoodsInfo = function(params,successCallback,errCallback){
		owner.getApi('exchange/getInfo',params,successCallback,errCallback);
	};
	
	/**
	 * 我的预约
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getMyOrderList = function(params,successCallback,errCallback){
		owner.getApi('User/getmyorderlist',params,successCallback,errCallback);
	};
	
	/**
	 * 我的信息
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getMyInfo = function(params,successCallback,errCallback){
		owner.getApi('User/getmyinfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取用户推荐体系
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getUserfxc = function(params,successCallback,errCallback){
		owner.getApi('User/getfxc',params,successCallback,errCallback);
	};
	
	/**
	 * 领取积分
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getAdPoint = function(params,successCallback,errCallback){
		owner.getApi('exchange/gainad',params,successCallback,errCallback);
	};
	
	/**
	 * 兑换商品
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.exchangeAd = function(params,successCallback,errCallback){
		owner.getApi('exchange/exgoods',params,successCallback,errCallback);
	};
	
	/**
	 * 获取用户券包
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getTickets = function(params,successCallback,errCallback){
		owner.getApi('user/gettickets',params,successCallback,errCallback);
	};
	
	/**
	 * 获取短信验证码
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSmsCode = function(params,successCallback,errCallback){
		owner.getApi('public/getsmscode',params,successCallback,errCallback);
	};
	
	/**
	 * 用户登录
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.login = function(params,successCallback,errCallback){
		owner.getApi('User/login',params,successCallback,errCallback);
	};
	
	/**
	 * 用户注册
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.register = function(params,successCallback,errCallback){
		owner.getApi('User/register',params,successCallback,errCallback);
	};
	
	/**
	 * 修改用户资料
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.updateInfo = function(params,successCallback,errCallback){
		owner.getApi('User/updateinfo',params,successCallback,errCallback);
	};
	
	/**
	 * 新手学堂
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.guideList = function(params,successCallback,errCallback){
		owner.getApi('share/guideList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章分类
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.shareArticleCate = function(params,successCallback,errCallback){
		owner.getApi('share/getarticlecate',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.shareArticleList = function(params,successCallback,errCallback){
		owner.getApi('share/getArticleList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章分类及其文章列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.shareArticleCateAndList = function(params,successCallback,errCallback){
		owner.getApi('share/getArticleCateAndList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取推荐文章列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.shareRmdArticleList = function(params,successCallback,errCallback){
		owner.getApi('share/getRmdArticleList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章详情
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.shareArticleInfo = function(params,successCallback,errCallback){
		owner.getApi('share/getArticleInfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章分享系统用户信息
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getShareUserInfo = function(params,successCallback,errCallback){
		owner.getApi('share/getUserInfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取文章积分
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getSharePoint = function(params,successCallback,errCallback){
		owner.getApi('share/getPoint',params,successCallback,errCallback,'MUIAJAX');
	};
	
	/**
	 * 获取收益明细
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getShareLog = function(params,successCallback,errCallback){
		owner.getApi('share/getShareLog',params,successCallback,errCallback);
	};
	
	/**
	 * 获取积分商城商品列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getShareGoodsList = function(params,successCallback,errCallback){
		owner.getApi('share/getShareGoodsList',params,successCallback,errCallback);
	};
	
	/**
	 * 获取积分商城商品详情
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getShareGoodsInfo = function(params,successCallback,errCallback){
		owner.getApi('share/getShareGoodsInfo',params,successCallback,errCallback);
	};
	
	/**
	 * 兑换商品
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.exchangeShare = function(params,successCallback,errCallback){
		owner.getApi('share/exgoods',params,successCallback,errCallback);
	};
	
	/**
	 * 用户收货地址列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.addressList = function(params,successCallback,errCallback){
		owner.getApi('user/getAddressList',params,successCallback,errCallback);
	};
	
	/**
	 * 新增用户收货地址
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.addressAdd = function(params,successCallback,errCallback){
		owner.getApi('user/addAddress',params,successCallback,errCallback);
	};
	
	/**
	 * 删除用户收货地址
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.addressDel = function(params,successCallback,errCallback){
		owner.getApi('user/delAddress',params,successCallback,errCallback);
	};
	
	/**
	 * 设置用户默认收货地址
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.addressSetDefault = function(params,successCallback,errCallback){
		owner.getApi('user/setDefaultAddress',params,successCallback,errCallback);
	};
	
	/**
	 * 获取兑换信息
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getShareGoodsExchangeInfo = function(params,successCallback,errCallback){
		owner.getApi('share/getExchangeInfo',params,successCallback,errCallback);
	};
	
	/**
	 * 获取我的兑换列表
	 * @param {Object} params
	 * @param {Object} callback
	 */
	owner.getUserExchangeList = function(params,successCallback,errCallback){
		owner.getApi('user/getExchangeList',params,successCallback,errCallback);
	};
	
}(mui, window.Api= {}));