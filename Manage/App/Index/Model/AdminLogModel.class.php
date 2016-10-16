<?php
namespace Index\Model;
use Think\Model;

/**
 * 后台日志列表
 * @author haichao
 *
 */
class AdminLogModel extends Model{
	
	//
	public function getList(){
		$starttime = strtotime(I('starttime'));
		$endtime = strtotime(I('endtime'));
		if($starttime!='' && $endtime!=''){
			$where['create_time'] = array('between',array($starttime,$endtime));
		}else{
			if($starttime!=''){
				$where['create_time'] = array('gt',$starttime);
			}
			if($endtime!=''){
				$where['create_time'] = array('lt',$endtime);
			}
		}
		if(I('log_type')!=''){
			$where['log_type'] = I('log_type');
		}
		if(I('log_type_val')!=''){
			$where['log_type_val'] = I('log_type_val');
		}
		$count      = $this->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,C('PAGE_SIZE'));  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($where)->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$admin = D('Users')->where(array('uid'=>$val['admin_id']))->join('users_type on users_type.id = users.admin_type')->find();
			$list[$key]['admin_code'] = $admin['user_code'];
			$list[$key]['admin_name'] = $admin['user_name'];
			$list[$key]['type_name'] = $admin['type_name'];
		}
		$lists['list'] = $list;
		$lists['page'] = $show;
		return $lists;
	}
	//删除操作
	public function del($id){
		$result = $this->where('id='.$id)->delete();
		return $result ? array('status' => 1,'info' => '删除成功','url' => U('')):array('status' => 0,'info' => '删除失败','url' => U(''));;
	}
}
