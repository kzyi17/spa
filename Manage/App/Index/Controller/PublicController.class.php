<?php
namespace Index\Controller;
use Think\Controller;

use Common\Extend\UploadFile;
class PublicController extends Controller {
	/**
	 +----------------------------------------------------------
	 * 初始化
	 +----------------------------------------------------------
	 */
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		header('Content-Type:application/json; charset=utf-8');
		$token = C('TOKEN');
		$this->loginMarked = md5($token['admin_marked']);
		C('SHOW_PAGE_TRACE',false);
	}
	

	public function index(){

		if (IS_POST) {
			$code = I('verify_code', '', 'trim');
			$returnLoginInfo = D("Users")->Auth();
			if($returnLoginInfo['status']){
				//验证码
				if (!$this->check_verify($code)) {
					die(json_encode(array('status' => 0, 'info' => "验证码错误啦，再输入吧")));
				}
				echo json_encode($returnLoginInfo);
			}
			else{
				echo json_encode($returnLoginInfo);
			}
			
		}
		else{
			$this->display();
		}

	}

	/**
	 * 验证码
	 */
	public function verify_code() {
		$config =    array( 'fontSize'    =>    20,    // 验证码字体大小
							'length'      =>    4,     // 验证码位数
							'useNoise'    =>    false, // 关闭验证码杂点
				     		'codeSet'     =>    '0123456789',
					      );
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}

	/**
	 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
	 * @param unknown_type $code
	 * @param unknown_type $id
	 * @return boolean
	 */
	function check_verify($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}

	/**
	 * 安全退出
	 */
	public function loginOut() {
		session("UserInfo", null); // 删除name
		session($_SESSION[$this->loginMarked],null);
		setcookie($_COOKIE[$this->loginMarked], "", time() - 3600);
		unset($_SESSION['UserInfo'], $_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
		$this->redirect("Public/index");
	}
	public function area_child(){
		$pid = I('pid');
		$data = M('areas')->where("parent_id = '{$pid}'")->select();
		echo json_encode($data);
	}
	
	/**
	 * 上传组件服务端(可优化)(可防止重复上传)
	 * @author Muke
	 */
	public function upload(){
		$data["flag"] = 200;		//操作码
		$data["msg"] = "";			//提示信息
		$data['data'] = null; 		//返回数据

		$uptype = I('uptype');
		$conf = C('upload_'.$uptype);
		
		//上传配置
		$UpConf = array();
		$UpConf['autoSub'] = true;	// 启用子目录保存文件
		$UpConf['subType'] = 'date'; //子目录命名规则
		
		$UpConf['thumb']              = true; //设置需要生成缩略图，仅对图像文件有效
		$UpConf['imageClassPath']     = 'Common.Extend.MyImage';// 设置引用图片类库包路径
		$UpConf['thumbPrefix']		 = ',,';
		$UpConf['thumbSuffix']       = '_s,_m,_l';  //生产2张缩略图
		$UpConf['thumbMaxWidth']      = '200,400,600';//设置缩略图最大宽度
		$UpConf['thumbMaxHeight']     = '200,400,600';//设置缩略图最大高度
		
		if(!$uptype || !$conf){
			$conf = C('upload_defalut');
			$UpConf['maxSize'] = $conf['maxSize'];	//设置上传文件大小
			$UpConf['allowExts'] = $conf['exts'];	//设置上传文件类型
			$UpConf['savePath'] = C('upload_rootPath').$conf['path'];	//设置附件上传目录
		}else{
			$UpConf['maxSize'] = $conf['maxSize'];	//设置上传文件大小
			$UpConf['allowExts'] = $conf['exts'];	//设置上传文件类型
			$UpConf['savePath'] = C('upload_rootPath').$conf['path'];	//设置附件上传目录
		}
		
		// 初始化上传类
		$upload = new UploadFile($UpConf);
		
		if (!$upload->upload()) {
			//捕获上传异常
			$data["flag"] = 101;
			$data["msg"] = $upload->getErrorMsg();
		} else {
			//取得成功上传的文件信息
			$upinfo = $upload->getUploadFileInfo();
			$info_name = $upinfo[0]['name'];
			$info_folder = $upinfo[0]['path'];
			$info_type = $upinfo[0]['extension'];
			//获取用户信息
// 			$user = session('UserInfo');
			
			//保存数据库
			$savedate = array(
					'type'  	=> $info_type,
					'folder' 	=> $conf['path'].$info_folder,
					'name'		=> $info_name,
					'posttime'  => time(),
// 					'uid'		=> $user['uid']
					
			);
			$info_id = M('images')->add($savedate);
			
			if($info_id){
				$data['photo_id'] = $info_id;
				$data['savename'] = $conf['path'].$upinfo[0]['fullsavename'];
// 				$data[''] = $conf['path'];
// 				$data[''] = '';
				$data['data'] = $data;
			}else{
				$data["flag"] = 101;
				$data["msg"] = '上传失败';
			}
		}
		
		exit(json_encode($data));
	}
	
	public function uploadfile(){
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize            = 3292200;
		//设置上传文件类型
		$upload->allowExts          = explode(',', 'jpg,gif,png,jpeg');
		//设置附件上传目录
		$upload->savePath           = C('upload_rootPath_defalut');
		
		$upload->autoSub            =  true;					// 启用子目录保存文件
		$upload->subType			= 'date';
		//设置需要生成缩略图，仅对图像文件有效
		$upload->thumb              = true;
		// 设置引用图片类库包路径
		$upload->imageClassPath     = 'Common.Extend.MyImage';
		
		$upload->thumbPrefix        = 'm_,s_';  //生产2张缩略图
		//设置缩略图最大宽度
		$upload->thumbMaxWidth      = '400,100';
		//设置缩略图最大高度
		$upload->thumbMaxHeight     = '400,100';
		
		if (!$upload->upload()) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
// 			import('@.ORG.Image');
// 			//给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
// 			Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], APP_PATH.'Tpl/Public/Images/logo.png');
// 			$_POST['image'] = $uploadList[0]['savename'];

// 			echo json_encode($upload->savePath);
			exit(json_encode($uploadList));
		}
		
		
	}
	
	public function test(){
// 		$upload = new UploadFile();
// 		$upload->allowExts
// 		echo $test->maxSize;
		
// 		exit(json_encode($upload->imageClassPath));
// 		echo 'test';
// 		$this->display();
		//exit(json_encode(session('UserInfo')));
		
	    echo encrypt('admin888');;
	}

}