<?php
namespace Index\Model;
use Think\Model;
class WithdrawModel extends CommonModel {
	
	public function getList(){
		//分页
		$where['is_del'] = 0;
		$count = $this->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,C('PAGE_SIZE'));
		$list = $this->where($where)->select();
		foreach ($list as $key => $val){
			$list[$key]['member_code'] = M('Members')->where(array('member_id'=>$val['member_id']))->getField('member_code');
		}
		$show       = $Page->show();// 分页显示输出
		$lists = array('list'=>$list, 'page'=>$show);
		return $lists;
	}
	public function withdrawDel($id){
		$data = $this->where('id = '.$id)->find();
		$member_code = M('Members')->where(array('member_id'=>$data['member_id']))->getField('member_code');
		$status = $this->where('id = '.$id)->delete();
		$sql = $this->getLastSql();
		$log = '删除会员'.$member_code.'提现申请';
		$this->sql_log($log,$sql,4,14);
		return $status ? array('flag'=>1,'info'=>'删除成功','url'=>"U('Member/Withdraw')") : array('flag'=>0,'info'=>'删除失败','url'=>'');
	}
}