<?php
namespace Index\Model;
use Think\Model;

class AuthRuleModel extends CommonModel{
	/**
	 * 编辑修改规则
	 * @return multitype:number string |multitype:number string Ambigous <string, mixed>
	 */
	public function ruleEdit(){
		$datas = array();
		$datas   = I('data');

		if(empty($datas['name']) || !isset($datas['name']) || empty($datas['title']) || !isset($datas['title'])){
			return array('status' => 0, 'info' => "规则或者标题不能空");
		}

		if(!empty($datas['id']) || intval($datas['id']) > 0){
			$name = $this->where("`id`= ".$data['id'])->getField('title');
			$status1 = $this->where("`id`= ".$datas['id'])->save($datas);
			$sql = $this->getLastSql();
			if($status1>0){
				$log = '编辑权限规则'.$name;
				$this->sql_log($log,$sql,7,12);
			}
		}
		else {
			$status2 = $this->add($datas);
			$sql = $this->getLastSql();
			if($status2>0){
				
				$log = '添加权限规则'.$datas["title"];
				$this->sql_log($log,$sql,7,13);
			}
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U(''));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U(''));
		}
		else{
			return array('status' => 0, 'info' => "规则已存在，未更新任何信息");
		}
	}
	/**
	 * 规则详情
	 * @param 规则ID int $rule_id
	 * @return array
	 */
	public function getRuleInfo($rule_id = 0){
		$result = array();
		$result = $this->where("`id`=".$rule_id)->find();

		return $result;
	}


	/**
	 * 规则列表
	 * @return multitype:string Ambigous <string, \Think\mixed, boolean, NULL, multitype:, mixed, object>
	 */
	public function getRuleList(){
		$result = array();
		$count = $this->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$showPage = $page->show();
		$list = $this->order("sort")->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['statusTxt'] = $v['status'] == 1 ? '启用' : "<font color='red'>禁用</font>";
			$list[$k]['chStatusTxt'] = $v['status'] == 0 ? "启用" : "禁用";
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);

		return $result;
	}
	public function del($id){
		$authGroup = D('AuthGroup')->select();
		//更新表auth_group
		foreach ($authGroup as $key =>$val){
			$rules = array();
			$groupid =$val['id'];
			$rules = explode(',',$val['rules']);
			for($i=0;$i<count($rules);$i++)
			if(strval($id)==$rules[$i]){
				unset($rules[$i]);
			}
			$data['rules'] = join(',',$rules);
			D('AuthGroup')->where("id = '{$groupid}'")->save($data);
		}
		$title = $this->where("id = '{$id}'")->getField('title');
		$status = $this->where("id = '{$id}'")->delete();
		if($status){
			$sql = $this->getLastSql();
			$log = '删除权限规则'.$title;
			$this->sql_log($log,$sql,7,14);
		}
		return $status ? array('status' => '1','info' => '规则删除成功'):array('status' => '1','info' => '规则删除失败');
	}

}