<?php
namespace Index\Model;
use Think\Model;

/**
 * 返利模型
 * @author Muke
 *
 */
class AfterSaleModel extends  CommonModel{
	//供应商列表
	public function getAfterSaleList($data=array()){
		$where['is_del'] = $data['is_del'] ? $data['is_del'] :0;
		if($data['order_no']){
			$where['order_no'] = $data['order_no'];
		}
		if($data['type']){
			$where['type'] = $data['type'];
		}
		$count      = $this->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count, 30);  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($where)->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key => $val){
			$member_code = D('Members')->where('member_id = '.$val['member_id'])->getField('member_code');
			$list[$key]['member_code'] = $member_code;
		}
		$lists = array('list'=>$list, 'page'=>$show);
		return $lists;
	}
	
	public function afterSaleShow(){
		$where['id'] = I('get.id');
		$where['is_del'] = 0;
		$data = $this->where($where)->find();
		$data['member_code'] = D('Members')->where('member_id = '.$data['member_id'])->getField('member_code');
		$data['admin'] = M('users')->where('uid = '.$data['admin_id'])->getField('user_code');
		
		return $data;
	}
	//售后处理
	public function updateAfterSale(){
		$data = I('data');
		$data['reply_time'] = time();
		$afterSale = $this->where(array('id'=>$data['id']))->find();
		$adminInfo = session('UserInfo');
		$data['admin_id'] = $adminInfo['uid'];
		if($data['id'] >0){
			if($afterSale['type'] =='barter'){
				$order['status']= 2;
				$title = '换货';
			}else if($afterSale['type'] =='refund'){
				$order['pay_status']= 2;
				$title = '退款';
			}else if($afterSale['type'] =='returns'){
				$order['status']= 5;
				$title = '退货';
			}else{
			}
			if($data['status'] ==0){
				$status = '审核中';
			}else if($data['status'] ==1){
				$status = '不同意';
			}else if($data['status'] ==2){
				$status = '同意';
			}else{
			}
			D('Order')->where(array('order_no'=>$afterSale['order_no']))->save($order);
			
			$lastId = $this->where(array('id'=>$data['id']))->save($data);
			$sql = $this->getLastSql();
			if($lastId){
				$log = '(订单号：'.$afterSale['order_no'].')售后服务类型'.$title.$status;
				$this->sql_log($log,$sql);
			}
			return array('status'=>1,'info'=>'处理成功','url'=>U('Order/afterSaleList'));
		}else{
			return array('status'=>0,'info'=>'处理失败','url'=>U('Order/afterSaleList'));
		}
	}
	public function del($Id){
		$where['id'] = $Id;
		$return = $this->where($where)->delete();
		if($return){
			return array('status'=>1,'info'=>'删除成功','url'=>U('Order/afterSaleList'));
		}else{
			return array('status'=>0,'info'=>'删除失败','url'=>U('Order/afterSaleList'));
		}
	}
	
	
	
}