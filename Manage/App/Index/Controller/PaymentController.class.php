<?php
namespace Index\Controller;
use Index\Controller\CommonController;


class PaymentController extends CommonController{
	
	public function payList(){
		
		$deliveryId = I('get.id');
		$deliveryData = D('Delivery')->where(array('id'=>$deliveryId))->field('type')->find();
		$where['status'] = 1;
		$where['type'] = array('gt',0);
		$paymentList = D('Payment')->where($where)->field('name,id')->select();
		if($deliveryData['type']=='1'){
			$payment = D('Payment')->where(array('status' => 1,'type'=>0))->field('name,id')->select();
		}
		if($payment){
			$paymentList = array_merge($paymentList,$payment);
		}
		echo json_encode($paymentList);
	}
}