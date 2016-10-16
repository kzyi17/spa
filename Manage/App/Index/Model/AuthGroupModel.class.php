<?php
namespace Index\Model;
use Think\Model;

class AuthGroupModel extends CommonModel{

	public function groupEdit(){
		$datas = array();
		$datas   = I('data');

		if(empty($datas['title']) || !isset($datas['title'])){
			return array('status' => 0, 'info' => "用户组不能空");
		}

		if(!empty($datas['id']) || intval($datas['id']) > 0){
			$title = $this->where("`id`= ".$datas['id'])->getField('title');
			$status1 = $this->where("`id`= ".$datas['id'])->save($datas);
			$sql = $this->getLastSql();
			if($status1>0){
				$log = '编辑用户组'.$title;
				$this->sql_log($log,$sql,8,3);
			}
		}
		else {
			$status2 = $this->add($datas);
			$sql = $this->getLastSql();
			if($status2>0){
				
				$log = '添加用户组'.$datas["title"];
				$this->sql_log($log,$sql,8,4);
			}
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U(''));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U(''));
		}
		else{
			return array('status' => 0, 'info' => "您未更新任何信息");
		}

	}
	/**
	 * 用户组详情
	 * @param unknown_type $group_id
	 * @return Ambigous <\Think\mixed, boolean, NULL, mixed, object>
	 */
	public function getGroupInfo($group_id = 0){
		$result = array();
		$result = $this->where("`id`=".$group_id)->find();

		return $result;
	}
	/**
	 * 用户组列表
	 * @return multitype:string Ambigous <string, \Think\mixed, boolean, NULL, multitype:, mixed, object>
	 */
	public function getGroupList(){
		$result = array();
		$count = $this->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$showPage = $page->show();
		$list = $this->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['statusTxt'] = $v['status'] == 1 ? '启用' : "<font color='red'>禁用</font>";
			$list[$k]['chStatusTxt'] = $v['status'] == 0 ? "启用" : "禁用";
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);

		return $result;
	}
	/**
	 * 删除用户组
	 * @param unknown $Id
	 * @return multitype:number string
	 * @author  haichao
	 */
	public function del($Id){
		$data = D('AuthGroup')->where("id = '{$Id}'")->find();
		$lastId = D('AuthGroup')->where("id = '{$Id}'")->delete();
		$sql = $this->getLastSql();
		if($lastId>0){
			$log = '删除用户组'.$data["title"];
			$this->sql_log($log,$sql,8,5);
		}
		M('auth_group_access')->where("group_id = '{$Id}'")->delete();
		return $lastId?array('status' => 1,'info' =>'用户组删除成功'):array('status' =>0,'info' =>'用户组删除失败');
	}
}