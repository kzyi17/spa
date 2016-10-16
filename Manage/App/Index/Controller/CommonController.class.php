<?php
namespace Index\Controller;
use Think\Controller;
/**
 * 基础类
 * @author homter
 *
 */
class CommonController extends Controller {
	protected $loginMarked;
	protected $Users;
	protected $Auth;
	protected $userInfo;
	protected $uid;
	protected $action_name='编辑';
	public $error_wait_time;
	public $sidebar = array();
	public $html_admin = array(1 => "管理员", 2 => "普通会员");
	
	public function _initialize() {

		$this->Users = D("Users");
		$this->Auth = new \Think\Auth();
		$this->userInfo = session('UserInfo');
		$this->uid = $this->userInfo['uid'];
//		$this->error_wait_time = C('TRACE_ERROR_WAIT_TIME');
		$this->assign('my_info', $this->userInfo);

		$token = C('TOKEN');
		$this->loginMarked = md5($token['admin_marked']);
		if (!$this->checkLogin()) {
			$this->error('登录超时，请重新登录', U('Public/index'));
		}
	
		if (!$this->checkAuth()) {
			trace("无访问权限 ", '_initialize', 'object');
			$this->error('您无访问权限！');
		}
		$this->assignBase();
		
		
		// $isThreeMenu 判断是否有三级菜单 1-有，0-无;
		$sub_menu = $this->show_sub_menu();
		$isThreeMenu = is_array($sub_menu[0]['list']) ? 1 : 0;
		$WEB_ROOT = C('WEB_ROOT');
		$WEB_MEMBER = C('WEB_MEMBER');
		$admin_big_menu = C('admin_big_menu');
		$admin_sub_menu = C('admin_sub_menu');
		$this->assign('action_name', $this->action_name() ? $this->action_name() : $this->action_name);
		$this->assign("menu", $this->show_menu());
		$this->assign('isThreeMenu', $isThreeMenu);
		$this->assign("sub_menu", $this->show_sub_menu());
		$this->assign("my_info", $this->userInfo);  
		$this->assign('WEB_ROOT',$WEB_ROOT);
		$this->assign('currentNav', $this->currentNav());
		
	}
	/**
	  * 当前操作名
	  * @author haichao
	  */
	protected function currentNav(){
		$admin_big_menu = C('admin_big_menu');
		$admin_sub_menu = C('admin_sub_menu');
		$WEB_ROOT = C('WEB_ROOT');
		if($this->action_name()){
			$action_name = $this->action_name();
		}else{
			$action_name = $this->action_name;
		}
		$default = array_keys($admin_sub_menu[CONTROLLER_NAME]);
		return "<a href='".$WEB_ROOT."/".CONTROLLER_NAME.'/'.$default[0]."'>".$admin_big_menu[CONTROLLER_NAME]."</a>".' > '.$action_name;
		
	}
	/**设置操作显示名
	  * @author haichao
	  */
	protected function setcurrentname($action_name){
		$old = $this->action_name;
		$this->action_name = $action_name;
		$this->assign('action_name', $action_name);
		$this->assign('currentNav', $this->currentNav());
		$this->action_name = $old;
	}
	/**获取操作显示名
	  * @author haichao
	  */
	protected function action_name(){
		$admin_sub_menu = C('admin_sub_menu');
		if($admin_sub_menu[CONTROLLER_NAME][ACTION_NAME]){
			return $admin_sub_menu[CONTROLLER_NAME][ACTION_NAME];
		}else{
			for($i=0;$i<count($admin_sub_menu[CONTROLLER_NAME]);$i++){
				if(isset($admin_sub_menu[CONTROLLER_NAME][$i]['list'][ACTION_NAME])){
					return $admin_sub_menu[CONTROLLER_NAME][$i]['list'][ACTION_NAME];
				}
			}
			return $admin_sub_menu[CONTROLLER_NAME][0]['info'];
		}
	}
	protected function assignBase() {
		$this->assign('html_admin', $this->html_admin);
	}
	
	protected function checkAuth() {

		$auth = new \Think\Auth();
	//	trace($this->userInfo, 'checkAuth $this->userInfo', 'object');
		/**
		 * APP_NAME 当前项目名
		 * CONTROLLER_NAME 当前模块名
		 * ACTION_NAME 当前操作名
		 */
		//需要验证的规则列表,支持逗号分隔的权限规则或索引数组
		//        $name = APP_NAME.'/'.GROUP_NAME.'/'.MODULE_NAME . '/' . ACTION_NAME;
		$name = '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
		
		//当前用户id
		$uid = $this->userInfo['uid'];
			
		//验证类型
		$type = 1;

		//执行check的模式
		$mode = 'url';
	
		//'or' 表示满足任一条规则即通过验证;
		//'and'则表示需满足所有规则才能通过验证
		$relation = 'and';
	
		if ($this->userInfo['admin_type'] == 1) {//最高管理员 类型 =1
			return true;
		}
		
		if ($auth->check($name, $uid, $type, $mode, $relation)) {//权限检查
			return true;
		}
		return false;
	}
	
	protected function checkLogin() {
       
		if (isset($_COOKIE[$this->loginMarked])) {
			
			$cookie = explode("_", $_COOKIE[$this->loginMarked]);
			$timeout = C("TOKEN");
			if (time() > (end($cookie) + $timeout['admin_timeout'])) {
				setcookie("$this->loginMarked", NULL, -3600, "/");
				unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
				$this->error("登录超时，请重新登录", U("Public/index"));
			} else {
				if ($cookie[0] == $_SESSION[$this->loginMarked]) {
					setcookie("$this->loginMarked", $cookie[0] . "_" . time(), 0, "/");
					return true;
				} else {
					setcookie("$this->loginMarked", NULL, -3600, "/");
					unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
					$this->error("帐号异常，请重新登录", U("Public/index"));
				}
			}
		} else {
			return false;
		}
		
		/*
		if ($this->uid > 0 && !empty($this->userInfo)) {
			return true;
		}
		return false;
		*/
	}	  
    
	/**
	 +----------------------------------------------------------
	 * 显示一级菜单
	 +----------------------------------------------------------
	 */
	private function show_menu() {
		$cache = C('admin_big_menu');
		$count = count($cache);
		$i = 1;
		$menu = "";
		foreach ($cache as $url => $name) {
			if ($i == 1) {
				$css = $url == CONTROLLER_NAME || !$cache[CONTROLLER_NAME] ? "fisrt_current" : "fisrt";
				$menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
			} else if ($i == $count) {
				$css = $url == CONTROLLER_NAME ? "end_current" : "end";
				$menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
			} else {
				$css = $url == CONTROLLER_NAME ? "current" : "";
				$menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
			}
			$i++;
		}
		return $menu;
	}
	
	/**
	 +----------------------------------------------------------
	 * 显示二级菜单
	 +----------------------------------------------------------
	 */
	private function show_sub_menu() {
		$big = CONTROLLER_NAME == "Index" ? "Common" : CONTROLLER_NAME;
		$cache = C('admin_sub_menu');
		$sub_menu = array();
		if ($cache[$big]) {
			$cache = $cache[$big];
			foreach ($cache as $url => $title) {
				if(is_array($title)){
					//$url = $big == "Common" ? $url : "$big/$url";
					$sub_menu[$url]['info'] = $title['info'];
					foreach ($title['list'] as $url2 => $title2) {
						$url3 = $big == "Common" ? $url2 : "$big/$url2";
						$sub_menu[$url]['list'][$url2] = array('url' => U("$url3"), 'title' => $title2);
					}
					 
				}
				else{
					$url = $big == "Common" ? $url : "$big/$url";
					$sub_menu[] = array('url' => U("$url"), 'title' => $title);
				}
	
			}
			return $sub_menu;
		} else {
			return $sub_menu[] = array('url' => '#', 'title' => "该菜单组不存在");
		}
	}
	
	/**
	 * 验证token信息
	 * 
	 * @author  homter
	 */
	
	protected function checkToken() {
		if (IS_POST) {
			if (!M("Users")->autoCheckToken($_POST)) {
				die(json_encode(array('status' => 0, 'info' => '令牌验证失败')));
			}
			unset($_POST[C("TOKEN_NAME")]);
		}
	}
	
	protected function sql_log($log,$sql,$type=0,$val=0){
		$statuss = C('LOG_STATUS');
		$status = true;
		if($statuss[$type]){
			if($statuss[$type]['status']=='0'){
				$status = false;
			}else{
				if($statuss[$type]['val_status'][$val]=='0'){
					$status = false;
				}
			}
		}
		if(strpos($sql,"SELECT")===false && $status){
			$data['log'] = $log;
			$data['ip'] = get_client_ip();
			$adminInfo = session('UserInfo');
			$data['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
			$data['create_time'] = time();
			$logs = C('LOG');
			$data['log_type'] = $type;
			$data['log_type_val'] = $val;
			$data['sql_log'] = $sql;
			M('admin_log')->add($data);
		}
	}
}