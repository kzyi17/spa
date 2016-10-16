<?php
namespace Index\Controller;
use Index\Controller\CommonController;

class AuthController extends CommonController{
	/**
	 * 管理列表
	 */
	public function index(){
		$data['keywords'] = I('post.keywords')?I('post.keywords'):'';
		$result = D('Users')->getUsersList($data);
		$this->assign("page",   $result['showPage']);
		$this->assign("list",   $result['list']);
		$this->assign("keywords",   $data['keywords']);
		$this->display();
	}
	/**
	 * 组管理
	 */
	public function group(){
		$result = D('AuthGroup')->getGroupList();
		$this->assign("page",   $result['showPage']);
		$this->assign("list",   $result['list']);
		$this->display();
	}
	/**
	 * 组删除
	 * @author  haichao
	 */
	public function groupDel(){
		$Id = I('get.id',0,'intval');
		echo json_encode(D('AuthGroup')->del($Id));
	}
	/**
	 * 用户/组关联
	 */
	public function access(){
		$users = D("Users")->getUsersList();
		$usersList = $users['list'];
	//	$memberList = $this->get('list');
		foreach ($usersList as $k => $v) {
			$usersList[$k]['groups'] = $this->Auth->getGroups($v['uid']);
			foreach ($usersList[$k]['groups'] as $gk => $gv) {
				$usersList[$k]['groupsTitle'][] = $gv['title'];
			}
			$usersList[$k]['groupsTitle'] = implode(",", $usersList[$k]['groupsTitle']);
	//		trace($usersList[$k]['groupsTitle'], __FUNCTION__ . $v['id'] . ' groupsTitle', 'object');
			$userType = M('users_type')->where("`admin_type` = ".$v['admin_type'])->field('type_name')->find();
			$usersList[$k]['typeName'] = $userType['type_name'];
		}
		$this->assign('list', $usersList);
		$this->display();
	}

	public function accessGroup(){
		$id = I("get.id", 0, 'intval'); //uid
		if (empty($id)) {
			$this->error("无效操作！", U("/Auth/access"));
		}
		$userData = D("Users")->getUserInfo($id);
		$count = M("AuthGroup")->where(array('status'=>1))->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$showPage = $page->show();
		$groups = M("AuthGroup")->where(array('status'=>1))->limit("$page->firstRow, $page->listRows")->select();
	    $userGroups = $this->getUserGroupsIds($this->Auth->getGroups($id)); //用户组信息

        foreach ($groups as $k => $v) {
            if (in_array($v['id'], $userGroups)) {
                $groups[$k]['group'] = 1;
            } else {
                $groups[$k]['group'] = 2;
            }
        }
        $groupRuleStatusHtml = array(1 => "已授权", 2 => "未授权");
        $groupRuleStatusAction = array(1 => "禁用", 2 => "授权");
        $this->assign('groupRuleStatusHtml', $groupRuleStatusHtml);
        $this->assign('groupRuleStatusAction', $groupRuleStatusAction);
        $this->assign('userData', $userData);
        $this->assign('list', $groups);
        $this->assign('page', $showPage);
		$this->display();
	}

	/**
	 * 指定用户组关联修改
	 */
	public function editAccessGroup() {
		$data = array();
		$data['id'] = I("post.id", 0, 'intval');
		$data['gid'] = I("post.gid", 0, 'intval');
		$data['status'] = I("post.status", 0, 'intval');
		$where = array();
		$where['uid'] = $data['id'];
		$where['group_id'] = $data['gid'];
		$accessGroupData = M("AuthGroupAccess")->where($where)->field('group_id', 'uid')->select(); //关联表信息
		$groupData = M("AuthGroup")->field('id,title,rules')->select($data['gid']); //组信息
		header('Content-Type:application/json; charset=utf-8');
		if (empty($groupData)) {
			echo json_encode(array('status' => 0, 'info' => "无效组"));
		}
		$title = M("auth_group")->where('id = '.$data['gid'])->getField('title');
		if ($data['status'] == 2) {//取消授权操作
			
			$result = M("AuthGroupAccess")->where($where)->delete();
			$sql = M("AuthGroupAccess")->getLastSql();
			if($result>0){
				$log = '用户/组关联 -'.$title.'-禁用';
				$this->sql_log($log,$sql,7,9);
			}
		} else {
			if (empty($accessGroupData)) {//组未关联用户 添加关联
				$result = M("AuthGroupAccess")->data($where)->add();
				$sql = M("AuthGroupAccess")->getLastSql();
				if($result>0){
					$log = '用户/组关联 -'.$title.'-授权';
					$this->sql_log($log,$sql,7,8);
				}
			}
		}
		//        $this->ajaxReturn($this->AuthGroupAccess->getLastSql(), "操作成功", 1);
		if (!empty($result)) {
			$data['status'] = (intval($data['status']) == 2 ? 1 : 2);
			echo json_encode(array('status' => 1, 'info' => "操作成功", 'data'=>$data));
		}
	//	echo json_encode(array('status' => 0, 'info' => "操作失败"));
	}

	/**
	 * 组规则管理
	 *
	 * @author  homter
	 */
	public function groupRule(){
		$id = I("get.id", 0, 'intval'); //组id
        $groupData = M("AuthGroup")->find($id);
        if (empty($groupData)) {
            $this->error("无效操作！", U("Auth/Group"));
        }

        $count = M("AuthRule")->where(array('status'=>1))->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
        $showPage = $page->show();
        $ruleData = M("AuthRule")->where(array('status'=>1))->order("sort")->limit("$page->firstRow, $page->listRows")->select();

//        $this->getRuleList(array("id" => $id)); //规则信息
//        $ruleData = $this->get('list');

        $groupRuleData = explode(",", $groupData['rules']);


        foreach ($ruleData as $k => $v) {
            if (in_array($v['id'], $groupRuleData)) {
                $ruleData[$k]['group'] = 1;
            } else {
                $ruleData[$k]['group'] = 2;
            }
        }

        $this->assign('list', $ruleData);
        $this->assign('page', $showPage);
        $this->assign('groupRuleData', $groupRuleData);
        $groupRuleStatusHtml = array(1 => "已授权", 2 => "未授权");
        $groupRuleStatusAction = array(1 => "禁用", 2 => "授权");
        $this->assign('groupRuleStatusHtml', $groupRuleStatusHtml);
        $this->assign('groupRuleStatusAction', $groupRuleStatusAction);
        $this->assign("groupData", $groupData);
        $this->display();
	}

	/**
	 * 组规则状态管理
	 *
	 * @author  homter
	 */
	public function editGroupRule() {
		$data = array();
		//        $data['status'] = 1;
		$data['id'] = I("post.id", 0, 'intval');
		$data['rid'] = I("post.rid", 0, 'intval');
		$data['status'] = I("post.status", 0, 'intval');
		$groupData = M("AuthGroup")->field('id,rules,title')->find($data['id']);
		$ruleData = M("AuthRule")->field('id,title')->find($data['rid']);
		if (empty($groupData)) {
			$this->ajaxReturn($data, "无效组", 0);
		}
		if (empty($ruleData)) {
			$this->ajaxReturn($data, "无效规则", 0);
		}
		$groupRuleData = explode(",", $groupData['rules']);
		$find = $this->_find_in_map($data['rid'], $groupRuleData);
		if ($data['status'] == 2) {
			unset($groupRuleData[$find]);
		} else {
			if (!in_array($data['rid'], $groupRuleData)) {
				$groupRuleData[] = $data['rid'];
				sort($groupRuleData);
			}
		}
		$title =  M("AuthGroup")->where('id=' . $data['id'])->getField('title');
		$ruletitle =  M("AuthRule")->where('id=' . $data['rid'])->getField('title');
		
		$result = M("AuthGroup")
		->where('id=' . $data['id'])
		->data(array('rules' => implode(",", $groupRuleData)))
		->save();
		
		if (!empty($result)) {
			//$sql = M('users_type')->getLastSql();
			if($data['status']==1){
				$type = '授权';
				$val = 6;
			}else{
				$type = '禁用';
				$val = 6;
			}
			$sql = M('AuthGroup')->getLastSql();
			$log = $type.'用户组'.$title.'中的('.$ruletitle.')规则'.'操作';
			$this->sql_log($log,$sql,7,$val);
			$data['status'] = (intval($data['status']) == 2 ? 1 : 2);
			echo json_encode(array('status' => 1, 'info' => "操作成功", 'data'=>$data));
		}
//		echo json_encode(array('status' => 0, 'info' => "操作失败", 'data'=>$data));
	}

	protected function _find_in_map($find, $arr) {
		foreach ($arr as $k => $v) {
			if (intval($v) == $find) {
				return $k;
			}
		}
		return false;
	}

	/**
	 * 规则管理
	 *
	 */
	public function rule(){
		$result = D('AuthRule')->getRuleList();
		$this->assign("page",   $result['showPage']);
		$this->assign("list",   $result['list']);
		$this->display();
	}

	/**
	 * 批量删除规则
	 */
	public function ruleDelAll(){
		$authRule = D("AuthRule");
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = $authRule->del($ids[$i]);
		}
		echo json_encode($arry);exit;

	}

	/**
	 * 编辑规格
	 */
	public function ruleEdit(){
		$M = D('AuthRule');
		if (IS_POST) {
			echo json_encode($M->ruleEdit());
		} else {
			$dataRow = null;
			$rule_id = I("get.id",'','intval');
			$dataRow = $M->getRuleInfo($rule_id);
			$this->assign("data", $dataRow);
			$this->display();
		}
	}


	/**
	 * 编辑用户组
	 */
	public function groupEdit(){

		$M = D('AuthGroup');
		if (IS_POST) {
			echo json_encode($M->groupEdit());
		} else {
			$dataRow = null;
			$group_id = I("get.id",'','intval');
			$dataRow = $M->getGroupInfo($group_id);
			$this->assign("data", $dataRow);
			$this->display();
		}
	}
	/**
	 * 更改管理员状态
	 * @return multitype:number string multitype:string number  |multitype:number string
	 */
	public function opUsersStatus(){
		$M = M("Users");
		$datas['uid'] =  I("get.uid", '', 'intval');
		$datas['status'] = I("get.status") == 1 ? 0 : 1;
		$data = $M->where('uid = '.$datas['uid'])->find();
		$lastId = $M->save($datas);
		if ($datas['status']==1) {
			$title='启动';
			$val = 7;
		}else{
			$title='禁用';
			$val = 8;
		}
		header('Content-Type:application/json; charset=utf-8');
		if ($lastId) {
			$sql = $M->getLastSql();
			$log = $title.'管理员'.$data["user_code"];
			$this->sql_log($log,$sql,8,$val);
			echo json_encode(array('status' => 1, 'info' => "处理成功", 'data' => array("status" => $datas['status'], "txt" => $datas['status'] == 1 ? "禁用" : "启动")));
		} else {
			echo json_decode(array('status' => 0, 'info' => "处理失败"));
		}
	}

	/**
	 * 更新状态
	 */
	public function opCommonStatus() {
		$table = I('get.table');
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode($this->opStatus($table));
	}




	public function opStatus($op = 'AuthGroup') {
		$M = M("$op");
		$t = I('table');
		$datas['id'] =  I("get.id", '', 'intval');
		$datas['status'] = I("get.status") == 1 ? 0 : 1;
		$data = $M->where('id = '.$datas['id'])->find();
		$lastId = $M->save($datas);
		if ($datas['status']==1) {
			$title='启用';
			$val = 1;
		}else{
			$title='禁用';
			$val = 2;
		}
		if($t == 'AuthGroup'){
			$type = '用户组';
			
		}else if($t == 'AuthRule'){
			$type = '规则';
			if($datas['status']==1){
				$val = 10;
			}else{
				$val = 11;
			}
		}else{
			
		}
		if ($lastId) {
			$sql = $M->getLastSql();
			$log = $title.$type.$data["title"];
			$this->sql_log($log,$sql,7,$val);
			return array('status' => 1, 'info' => "处理成功", 'data' => array("status" => $datas['status'], "txt" => $datas['status'] == 1 ? "禁用" : "启动"));
		} else {
			return array('status' => 0, 'info' => "处理失败");
		}
	}


	public function getUserGroupsIds($groups = array()) {
		$ids = array();
		if (is_array($groups)) {
			foreach ($groups as $k => $v) {
				$ids[] = $v['id'];
			}
		}
		return $ids;
	}
	/**
	 * 管理员删除
	 * @param number $id
	 * @return unknown
	 * @author  haichao
	 */

	public function AuthDel(){
		$datas = array();
		$id   = I('id',0,'intval');
		$data = M('users')->where("uid = '{$id}'")->find();
		$lastId = M('users')->where("`uid`= ".$id)->delete();
		$sql = M('users')->getLastSql();
		if($lastId>0){
			$log = '删除管理员'.$data["user_code"];
			$this->sql_log($log,$sql,8,3);
		}
		M('auth_group_access')->where("`uid`= ".$id)->delete();
		echo json_encode($lastId ? array('status' => 1, 'info' => "管理员删除成功"):array('status' => 0, 'info' => "管理员删除失败"));
	}
	/**
	 * 添加修改管理员
	 *
	 * @author  haichao
	 */
	public function AuthEdit(){
		$M = D('Users');
		if (IS_POST) {
			echo json_encode($M->AuthEdit());
		} else {
			$dataRow = null;
			$userGroup = array();
			$uid = I("get.id",'0','intval');
			$userGroups = M('auth_group_access')->where(array('uid = '.$uid))->field('group_id')->select();
			foreach($userGroups as $key => $val){
				$userGroup[] = $val['group_id'];
			}
			$dataRow = $M->getUserInfo($uid);
			$userType = M('users_type')->select();
			$result = D('AuthGroup')->getGroupList();
			$this->assign("group",   $result['list']);
			$this->assign("userGroup",   $userGroup);
			$this->assign("userType",   $userType);
			$this->assign("data", $dataRow);
			$this->display();
		}
	}
	/**
	 * 管理员类型
	 *
	 * @author  haichao
	 */
	public function userType(){
		$userType = M('users_type')->select();
		$this->assign("list", $userType);
		$this->display();
	}
	/**
	 * 管理员类型添加编辑
	 *
	 * @author  haichao
	 */
	public function userTypeEdit(){
		$M = D('Users');
		if (IS_POST) {
			echo json_encode($M->typeEdit());
		}else{
			$id = I("get.id",'0','intval');
			$userType = M('users_type')->where("id = '{$id}'")->find();
			$this->assign("data", $userType);
			$this->display();
		}
	}
	/**
	 * 删除管理员类型
	 *
	 * @author  haichao
	 */
	public function usersTypeDel(){
		$M = D('Users');
		$Id = I('id',0,'intval');
		echo json_encode($M->typeDel($Id));
	}
	/**
	 * 批量删除管理员类型
	 *
	 * @author  haichao
	 */
	public function usersTypeDelAll(){
		$M = D('Users');
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = $M->typeDel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
}

?>