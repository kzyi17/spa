<?php
namespace Index\Controller;

use Think\Controller;
class HandleController extends Controller{
	
	/**
	 * 订单处理
	 * @author Muke
	 */
	public function order(){
		$where = array();
		$where['order.status'] = 2;
		$where['d.time'] = array('ELT',strtotime("-14 day"));
		
		$Model_order = M('order');
		$Model_member = M('member');
		$Model_distribution = M('distribution');
		
		//获取符合处理条件的订单
		$list = $Model_order ->field('order.order_id,d.time as delivery_time,order.order_no,order.member_id,od.points,order.goods_amount')
		 					->join('delivery_doc as d on d.order_id = order.order_id')
		 					->join('order_data as od on od.order_id = order.order_id','left')
							->where($where)
							->select();

		//处理订单涉及到的返利、积分返还等
		foreach ($list as $v){
			//自动确认收货
			$Model_order->where('order_no='.$v['order_no'])->setField('status',5);
			
			//增加积分处理
			D('Account')->addPoints($v['member_id'],$v['points']);
			D('Account')->addConsume($v['member_id'],$v['goods_amount']);
			
			
			if(M('refundment_doc')->where('order_id='.$v['order_id'])->select()){//检查是否有退款
				M('distribution')->where(array('order_sn'=>$v['order_no']))->setField('status',-1);
			}else{//返利处理
				D('Account')->addDistribution($v['member_id'],$v['order_no']);
			}
			
		}
		
		
		print_r($list);
		
	}
	
}