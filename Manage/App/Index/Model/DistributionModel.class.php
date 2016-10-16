<?php
namespace Index\Model;
use Think\Model;

/**
 * 返利模型
 * @author Muke
 *
 */
class DistributionModel extends  CommonModel{
	
	public function getList(){
		if(I('get.order_member_id')){
			$where['order_member_id'] = I('get.order_member_id');
		}
		
		if(I('get.member_id')){
			$where['member_id'] = I('get.member_id');
		}
		if($where!=null){
			$list = $this->where($where)->select();
		}else{
			$list = $this->select();
		}
		return $list;
	}
	/**
	 * 获取用户待返现总金额
	 * @author haichao
	 * @param int $member_id
	 */
	function getAudit($member_id){
		$where['member_id'] = $member_id;
		$where['status'] = array(0,1,'or');
		$result = $this->where($where)->Sum('amount');
		if($result){
			return $result;
		}
		return 0;
	}
	/**
	 * 分销等级编辑
	 * @author haichao
	 *
	 */
	public function disLevelEdit(){
		$data = I('data');
		/*添加验证*/
		if (empty($data["level_name"])) {
			return array('status' => 0, 'info' => "分销等级姓名不能为空哦");
		}

		if(!empty($data['level_id']) || intval($data['level_id']) > 0){

			$status1 = M('distribution_level')->where("`level_id`= ".$data['level_id'])->save($data);
		}
		else {
			$status2 = M('distribution_level')->add($data);
		}
		$sql = M('distribution_level')->getLastSql();
		if($status1!=0){
			$log = '修改会员等级'.$data["level_name"];
			$this->sql_log($log,$sql,4,11);
			return array('status' => 1, 'info' => "成功更新", 'url' => U('member/disLevel'));
		}
		elseif($status2!=0){
			$log = '添加会员等级'.$data["level_name"];
			$this->sql_log($log,$sql,4,12);
			return array('status' => 1, 'info' => "添加成功", 'url' => U('member/disLevel'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('member/disLevel'));
		}
		return $data;
	}
	/**
	 * 分销等级删除
	 * @author haichao
	 *
	 */
	public function disLevelDel($id){
		$level_name = M('distribution_level')->where("`level_id`= ".$id)->getField('level_name');
		$status = M('distribution_level')->where("`level_id`= ".$id)->delete();
		if($status!=0){
			$log = '删除会员等级会员等级'.$level_name;
			$sql = M('distribution_level')->getLastSql();
			$this->sql_log($log,$sql,4,13);
			return array('status' => 1, 'info' => "删除成功", 'url' => U('member/disLevel'));
		}
		else{
			return array('status' => 1, 'info' => "删除失败", 'url' => U('member/disLevel'));
		}
	}
	
	/**
	 * 创建待返利
	 * @author Muke
	 */
	public function forRebate($data){
		$data['status'] = 0;
		$data['time'] = time();
		return $this->data($data)->add();
	}
	
	/**
	 * 成功返利
	 * @author Muke
	 */
	public function doRebate(){
		
	}
	
	
}