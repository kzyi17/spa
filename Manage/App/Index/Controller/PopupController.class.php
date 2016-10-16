<?php
namespace Index\Controller;

/**
 * 弹窗插件应用控制器
 * @author Muke
 *
 */
class PopupController extends CommonController{
	
	public function _initialize() {
		C('SHOW_PAGE_TRACE',false);
	}

	/**
	 * 上传组件
	 * @author Muke
	 */
	public function myuploader(){
		$config = array();
		if(strtolower(I('multi','false')) == 'true'){
			$config['multi'] = TRUE;
		}else{
			$config['multi'] = FALSE;
		}
		$uploadpath = I('uploadpath','');
		$up_conf = C('upload_'.$uploadpath);
		$config['uploadpath'] = $uploadpath;
		
		if(!$up_conf || !$uploadpath){
			$config['exts'] = C('upload_exts');
			$config['maxSize'] = C('upload_maxSize');
			
			
		}else{
			$config['exts'] = $up_conf['exts'];
			$config['maxSize'] = $up_conf['maxSize'];
		}
		$this->assign('config',$config);
		$this->display();
	}
	
	/**
	 * 商品检索弹窗
	 * @author Muke
	 */
	public function searchGoods(){
		$this->display('searchGoods');
	}
}