<?php
namespace Index\Controller;
use Think\Controller;
class DefaultController extends CommonController {
	public function index(){
		$current_date=getdate();
		$time = strtotime($current_date['year'].'-'.$current_date['mon'].'-'.$current_date['mday']);
		//$time_lag = 86400;
		$where['reg_time'] = array('gt',$time);
		
		$model_users = M('members');
		$this->assign('member_total_count',$model_users->count());
		$this->assign('member_count',$model_users->where($where)->count());
		$this->display();
	}
	
	/**
	 * 修改密码
	 * 
	 * @author  homter
	 */
	public function myInfo(){
		if (IS_POST) {
			$this->checkToken();
			echo json_encode(D("Users")->updatePassword($_POST));
		} else {
			$this->display();
		}
	}
	public function cache(){
		header("Content-type: text/html; charset=utf-8");
		//清文件缓存
		
		$this->assign('mobile',$this->getCacheList('mobile'));
		$this->assign('manage',$this->getCacheList('manage'));
		$this->display();
		//$this->display();
	}
	public function getCacheList($name){
		$showmay = realpath(APP_PATH.'../../');
		$mobile = $showmay."/$name.".'/App/Runtime';
		$mobileCache = realpath($mobile);
		$list = scandir($mobileCache);
		for($i=0;$i<count($list);$i++){
			if($list[$i]!='..'){
				$lists[$i]['name'] = $list[$i];
				$lists[$i]['path'] = realpath($mobileCache.'/'.$list[$i]);
			}
		}
		return $lists;
	}
	public function del_cache(){
		$dels = I('del');
		$type = I('type');
		for($i=0;$i<count($dels);$i++){
			delDirAndFile($dels[$i]);
		}
		$Cache = new \Think\Cache\Driver\Memcache();
		$Cache->clear();
		$this->redirect("Default/cache");
	}
	public function getChildrenFiles(){
		$type=I('type');
		$showmay = realpath(APP_PATH.'../../');
		$type = $showmay."/$type.".'/App/Runtime';
		$typeCache = realpath($type);
		$dir = base64_decode($_POST['file']);
		$list = scandir($dir);
		
		for($i=0;$i<count($list);$i++){
			if($list[$i]!='..' && $list[$i]!='.'){
				$lists[$i]['name'] = $list[$i];
				$lists[$i]['path'] = realpath($dir.'/'.$list[$i]);
				$lists[$i]['decode_path'] = base64_encode($lists[$i]['path']);
			}
		}
		echo json_encode($lists);
		
	}
}