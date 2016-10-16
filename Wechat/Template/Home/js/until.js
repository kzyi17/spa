/**
 * 工具函数
 * 
 */

/**
 * 将HTML String 转换为 dom 元素
 * @param {Object} text
 */
function parseStringToHTML(text) {
    var i, a = document.createElement("div"),
        b = document.createDocumentFragment();
    a.innerHTML = text;
    while (i = a.firstChild) b.appendChild(i);
    return b;
}
	
/**
 * 检查手机号
 * 
 */
function isMobile(mobile){
	if(typeof mobile == 'undefined'){
		return false;
	}
	var rule = /^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/;
	if(rule.test(mobile)){
		return true;
	}else{
		return false;
	}
}

/**
 * 输入框输入字数限制
 * @param {Object} field 表单对象
 * @param {Object} countfield 字数提示框ID
 * @param {Object} maxlimit 限制字符
 */
function textCounter(field, countfield, maxlimit) {  
	if (field.value.length > maxlimit) {
   		//如果元素区字符数大于最大字符数，按照最大字符数截断；  
		field.value = field.value.substring(0, maxlimit);  
	}else{
   		//在记数区文本框内显示剩余的字符数；  
		document.getElementById(countfield).innerText = field.value.length + '/' + maxlimit;
	}
}

/**
 * 拍照添加图片
 * @param {Object} successCallback
 */
function cameraImages(successCallback) {
	successCallback = successCallback || mui.noop;
    var cmr = plus.camera.getCamera();
    cmr.captureImage(function(p) {
        plus.io.resolveLocalFileSystemURL(p, function(entry) {
            var localurl = entry.toLocalURL(); //把拍照的目录路径，变成本地url路径，例如file:///........之类的。
			successCallback(localurl);
        }, function(e) {
        	mui.toast("读取拍照文件错误");
			console.log("读取拍照文件错误：" + e.message);
		});
    }, function(e) {
    	switch(e.code)
		{
			case 11:
			case 2:
				mui.toast("取消拍照 ");
		  		break;
			default:
		  		mui.toast("很抱歉，获取拍照失败 ");
		}
    });
}

/**
 * 相册选择图片
 */
function galleryImages() {
    // 从相册中选择图片
    mui.toast("从相册中选择图片");
    plus.gallery.pick(function(e) {
        for (var i in e.files) {
            //console.log(e.files[i]);
            compressImg(e.files[i],function(event){
				var newImg = event.target;
				
			});
        }
    }, function(e) {
    	console.log(JSON.stringify(e));
        mui.toast("取消选择图片");
    }, {
        filter: "image",
        multiple: true
    });
}

/**
 * 压缩图片
 * @param {Object} url
 * @param {Object} successCallback
 */
function compressImg(url,successCallback){
	var maxSize = 1200;//图片压缩后的最大尺寸
	successCallback = successCallback || mui.noop;
	var _img_ = new Image();
	_img_.src = url; 
	_img_.onload = function() {
		var tmph = _img_.height;
        var tmpw = _img_.width;
        var isHengTu = tmpw > tmph;
        var max = Math.max(tmpw, tmph);
        var min = Math.min(tmpw, tmph); 
        var bili = min / max;
        if (max > maxSize) {
            max = maxSize;
            min = Math.floor(bili * max);
        }
        tmph = isHengTu ? min : max; 
        tmpw = isHengTu ? max : min;
        _img_.onload = null;
         
        //压缩图片
        plus.zip.compressImage({
			src:url,
			//dst:"_doc/userhead.jpg",
			quality:50,
			width: tmpw+"px",
			height: tmph+"px"
			//overwrite:true
		},function(event) {
			console.log(JSON.stringify(event));
			successCallback(event);
		},function(error) {
			console.log(JSON.stringify(error));
		});
		
	};
    
}

/**
 * base64编码
 * @param {Object} url
 * @param {Object} successCallback
 */
function base64Encode(url,successCallback){
	successCallback = successCallback || $.noop;
	plus.io.resolveLocalFileSystemURL(url, function(entry) {
		var reader = null;
		entry.file( function ( file ) {
			reader = new plus.io.FileReader();
			reader.onloadend = function ( e ) {
				successCallback(e.target.result);
			};
			reader.readAsDataURL(file);
		});
	},function(e) {
		console.log("读取文件错误：" + e.message);
	});
}

/**
* 图片懒加载
* @param {Object} obj DOMElement
* @param {Function} callback 加载完成回调函数
* 
* @author fanrong33
* @version 1.1.0 build 20160107
*/
function lazyload(obj, callback) {
	var debug = false; // 默认打印调试日志
	if (obj.getAttribute('data-loaded')) {
		return;
	}

	var image_url = obj.getAttribute('data-lazyload');
	debug && console.log(image_url);

	// 1. 转换网络图片地址为本地缓存图片路径，判断该图片是否存在本地缓存
	// http://...jpg -> md5
	// 缓存目录 _downloads/image/(md5).jpg
	var image_md5 = md5(image_url);
	var local_image_url = '_downloads/image/' + image_md5 + '.jpg'; // 缓存本地图片url
	var absolute_image_path = ""; // 平台绝对路径
	plus.io.resolveLocalFileSystemURL(local_image_url, function(entry) {
		absolute_image_path = entry.toLocalURL();
		var temp_img = new Image();
		entry.file(function(file) {
			var totalSize = localStorage.getItem(image_md5 + "totalSize");
			debug && console.log(file.size + " " + totalSize);
			debug && console.log(absolute_image_path);
			if (file && file.size == totalSize) {
				//此时文件是完整的，可以直接进行展示。
				debug && console.log('存在本地缓存图片文件' + local_image_url + '，直接显示');

				// 1.1 存在，则直接显示（本地已缓存，不需要淡入动画）
				obj.setAttribute('src', absolute_image_path);
				obj.setAttribute('data-loaded', true);
				obj.classList.add('img-lazyload');
				callback && callback(obj);
			} else {
				//文件没有下载完整，进行下载处理。
				debug && console.log('不存在本地缓存图片文件');

				// 1.2 下载图片缓存到本地
				debug && console.log('开始下载图片' + image_url + ' 缓存到本地: ' + local_image_url);
				download_img(image_url, local_image_url, image_md5, obj, callback);
			}
		});
	}, function(Ex) {
		//文件不存在，进行下载处理。
		debug && console.log('不存在本地缓存图片文件');
		// 1.2 下载图片缓存到本地
		debug && console.log('开始下载图片' + image_url + ' 缓存到本地: ' + local_image_url);
		download_img(image_url, local_image_url, image_md5, obj, callback);
	}); 
}

/**
 * 
 * @param {Object} image_url 文件下载地址
 * @param {Object} local_image_url 文件本地缓存地址
 * @param {Object} image_md5 文件名md5加密
 * @param {Object} obj img对象
 * @param {Object} callback 回调方法
 */
function download_img(image_url, local_image_url, image_md5, obj, callback) {
	var debug = false; // 默认打印调试日志
	var download_task = plus.downloader.createDownload(image_url, {
		filename: local_image_url // filename:下载任务在本地保存的文件路径
	}, function(download, status) {
		localStorage.setItem(image_md5 + "totalSize", download.totalSize);
		if (status != 200) {
			// 下载失败,删除本地临时文件
			debug && console.log('下载失败,status' + status);
			if (local_image_url != null) {
				plus.io.resolveLocalFileSystemURL(local_image_url, function(entry) {
					entry.remove(function(entry) {
						debug && console.log("临时文件删除成功" + local_image_url);
						// 重新下载图片
						download_img();
					}, function(e) {
						debug && console.log("临时文件删除失败" + local_image_url);
					});
				});
			}
		} else if (status === 200) {
			// 把下载成功的图片显示
			// 将本地URL路径转换成平台绝对路径
			obj.setAttribute('src', plus.io.convertLocalFileSystemURL(local_image_url));
			obj.setAttribute('data-loaded', true);
			obj.classList.add('img-lazyload');
			callback && callback(obj);
		}
	});
	download_task.start();
}
