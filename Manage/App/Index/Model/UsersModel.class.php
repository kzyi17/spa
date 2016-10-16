<?php
namespace Index\Model;
use Think\Model;
class UsersModel extends CommonModel {
	/**
	 * 登录验证
	 * @return multitype:number string |multitype:number string Ambigous <string, mixed>
	 */
	public function Auth(){
		$datas = $_POST;

		$UserCode = I('UserCode', '', 'trim');
		$UserPassword = I('UserPassword', '', 'trim');
		//用户,密码
		if (empty($UserCode) || empty($UserPassword)) {
			die(json_encode(array('status' => 0, 'info' => "请输入帐号密码吧")));
		}

		if ($this->where("`user_code`='" . $UserCode . "'")->count() >= 1) {
			$info = $this->where("`user_code`='" . $UserCode . "'")->field("`uid`, user_password, `status`")->find();
			if ($info['status'] == 0) {
				return array('status' => 0, 'info' => "你的账号被禁用，有疑问联系管理员吧");
			}

			if ($info['user_password'] == encrypt($UserPassword)) {
				$loginMarked = C("TOKEN");
				$loginMarked = md5($loginMarked['admin_marked']);
				$shell = $info['uid'] . md5($info['user_password'] . C('AUTH_CODE'));
				$_SESSION[$loginMarked] = "$shell";
				$shell.= "_" . time();
				setcookie($loginMarked, "$shell", 0, "/");

				$usersUpdata = array(
						'login_ip' => get_client_ip(),
						'last_login_time' => time(),
				);
				$this->where("uid=".$info['uid'])->save($usersUpdata);
				$myInfo = $this->where("`user_code`='" . $UserCode . "'")->field('`uid`,`user_code`,`status`,`admin_type`')->limit(1)->find();
				session('UserInfo', $myInfo);

				return array('status' => 1, 'info' => "登录成功", 'url' => U("Default/index"));
			} else {
				return array('status' => 0, 'info' => "账号或密码错误");
			}
		} else {
			return array('status' => 0, 'info' => "不存在用户为：" . $datas["user_code"] . '的管理员账号！');
		}
	}
    /**
     * 后台用户列表
     * @param unknown_type $data
     * @return multitype:string Ambigous <unknown, \Think\mixed, string, boolean, NULL, multitype:, mixed, object> 
     * @author  homter
     */
	public function getUsersList($data=''){
		//搜索keywords
		if(!empty($data['keywords']) && $data['keywords'] !='')
			$where['user_code'] = array('like',$data['keywords']);
		$result = array();
		$count = $this->where($where)->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$showPage = $page->show();
		$list = $this->where($where)->field('user_password',true)->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['statusTxt'] = $v['status'] == 1 ? '启用' : "<font color='red'>禁用</font>";
			$list[$k]['chStatusTxt'] = $v['status'] == 0 ? "启用" : "禁用";
			$userType = M('users_type')->where("`admin_type` = ".$v['admin_type'])->field('type_name')->find();
			$list[$k]['typeName'] = $userType['type_name'];
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);

		return $result;
	}

	public function getUserInfo($uid = 0){
		$result = array();
		$result = $this->where(array('uid'=>$uid))->field('user_password',true)->find();

		return $result;
	}
	/**
	 * 添加修改管理员
	 * @return multitype:number string
	 * @author  haichao
	 */
	public function AuthEdit(){
		$data = I('data');
		$access = I('access');
		if(!empty($data['uid']) || $data['uid'] !=''){
			//密码为空不修改密码
			if($data['user_password'] !='')
				$data['user_password'] = encrypt($data['user_password']);
			else
				unset($data['user_password']);
			$user_code = $this->where(array('uid' =>$data['uid']))->getField('user_code');	
			$lastId = $this->where(array('uid' =>$data['uid']))->save($data);
			$sql = $this->getLastSql();
			if($lastId>0){
				$sql = $this->getLastSql();
				$log = '修改管理员'.$user_code;
				$this->sql_log($log,$sql,8,1);
			}
			//删除对应的auth_group_access表
			M('auth_group_access')->where(array('uid' =>$data['uid']))->delete();
		}else{
			$data['user_password'] = encrypt($data['user_password']);
			$data['create_time'] = time();
			$data['status'] = 1;
			$uid = $this->add($data);
			if($uid>0){
				$sql = $this->getLastSql();
				$log = '添加管理员'.$data["user_code"];
				$this->sql_log($log,$sql,8,2);
			}
		}
		//添加auth_group_access表
		if($access){
			for($i=0;$i<count($access);$i++){
				$UserAccess['uid'] =  $uid ? $uid : $data['uid'];
				$UserAccess['group_id'] = $access[$i];
				$lastacess = M('auth_group_access')->add($UserAccess);
			}
		}
		if($data['uid']!='')
			return ($lastId || $lastacess) ? array('status' => 1, 'info' => "修改成功"):array('status' => 0, 'info' => "修改失败");
		else
			return ($uid|| $lastacess) ? array('status' => 1, 'info' => "添加成功"):array('status' => 0, 'info' => "添加失败");
	}
	/**
	 * 类型添加修改
	 *
	 * @author  haichao
	 */
	public function typeEdit(){
		$data = I('data');
		if(!empty($data['id']) || $data['id'] !=''){
			$type_name = M('users_type')->where("`id`= ".$data['id'])->getField('type_name');
			$status1 = M('users_type')->where(array('id' =>$data['id']))->save($data);
			$sql = M('users_type')->getLastSql();
			if($status1>0){
				$log = '编辑用户类型'.$type_name;
				$this->sql_log($log,$sql,8,4);
			}
		}else{
			$status2 = M('users_type')->add($data);
			$sql = M('users_type')->getLastSql();
			if($status2>0){
				
				$log = '添加用户类型'.$data["type_name"];
				$this->sql_log($log,$sql,8,5);
			}
		}
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U(''));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U(''));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息");
		}

	}
	/**
	 * 管理员类型删除
	 * @param 类型ID $Id
	 * @return multitype:number string
	 * @author  haichao
	 */
	public function typeDel($Id){
		$type_name = M('users_type')->where(array('id'=>$Id))->getField('type_name');
		$lastId = M('users_type')->where(array('id'=>$Id))->delete();
		if($lastId>0){
			$sql = M('users_type')->getLastSql();
			$log = '删除类型'.$type_name;
			$this->sql_log($log,$sql,8,6);
		}
		return $lastId ? array('status' => 1, 'info' => "删除成功"):array('status' => 0, 'info' => "删除失败");
	}
	
	public function updatePassword($datas) {
		if(empty($datas['user_code']) || $datas['user_code']!= $_SESSION['UserInfo']['user_code']){
			return array('status' => 0, 'info' => "参数有误或者session失效");
		}
		
		$dataInfo = $this->where(array('user_code'=>$datas['user_code']))->field('user_password')->find();

		if (encrypt(trim($datas['pwd0'])) != $dataInfo['user_password']) {
			return array('status' => 0, 'info' => "旧密码错误");
		}
		if (trim($datas['pwd']) == '') {
			return array('status' => 0, 'info' => "密码不能为空");
		}
		if (trim($datas['pwd']) != trim($datas['pwd1'])) {
			return array('status' => 0, 'info' => "两次密码不一致");
		}
		if (trim($datas['pwd']) == trim($datas['pwd0'])) {
			return array('status' => 0, 'info' => "新密码不能与旧密码相同");
		}
		$data['uid'] = $_SESSION['UserInfo']['uid'];
		$data['user_password'] = encrypt($datas['pwd']);
		if ($this->save($data)) {
			return array('status' => 1, 'info' => "你的密码已经成功修改，请重新登录", 'url' => U('Public/loginOut'));
		} else {
			return array('status' => 0, 'info' => "密码修改失败");
		}
	}
}