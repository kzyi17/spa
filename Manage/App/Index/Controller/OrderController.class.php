<?php
namespace Index\Controller;
use Index\Controller\CommonController;
use Think;
//use Think\Controller;

class OrderController extends CommonController{

	public function index($is_del=0){
		$data = I('data');
		$data['is_del'] = $is_del;
		$order = D('Order')->orderList($data);
		$this->assign("page",   $order['page']);
		$this->assign("list",   $order['list']);
		$this->assign("pay_status",   $data['pay_status']);
		$this->assign("distribution_status",   $data['distribution_status']);
		$this->assign("status",   $data['status']);
		$this->assign("order_no",   $data['order_no']);
		$this->display('index');
	}
	//回收站
	public function recycle(){
		$this->index(1);
	}
	//订单恢复
	public function recycleRegain(){
		$orderId = I('order_id');
		echo json_encode(D('Order')->recycleRegain($orderId));
	}
	/**
	 * 订单删除
	 **/
	public function del(){
		$orderId = I('order_id');
		echo json_encode(D('Order')->del($orderId));
	}
	/**
	 * 订单彻底删除
	 **/
	public function recycleDel(){
		$orderId = I('order_id');
		echo json_encode(D('Order')->recycleDel($orderId));
	}

	/**
	 * 订单和回收站批量删除
	 **/
	public function OrderDelall(){
		$ids = array();
		$datas   = I('data');
		$type   = I('type');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = D('Order')->del($ids[$i]);
			else
				$arry = D('Order')->recycleDel($ids[$i]);
		}
		echo json_encode($arry);exit;

	}
	/**
	 * 订单查看
	 **/
	public function orderShow(){
		$this->setcurrentname('订单详情处理');
		$order = D('Order')->orderShow();
		$this->assign("data",   $order);
		$this->display();
	}
	
	/**
	 * 订单编辑
	 **/
	public function orderEdit(){
		if($_POST){
			echo json_encode(D('Order')->orderEdit());
		}else{

			$order_id = I('order_id')?I('order_id'):0;
			$orders = D('Order')->getOrder($order_id);
			$orderdata = D('Order')->getOrderdata($order_id);
			$orderProductdata = D('Order')->getOrderProductdata($order_id);
			//订单支付方式，配送方式
			$Otherdata = D('Order')->orderOtherdata();
			// 添加地区信息获取 编辑同类地址的数据组
			$Areadata = D('Areas')->getOtherAreasdata($orderdata['city'],$orderdata['area']);
			$this->assign("order",   $orders);
			$this->assign("orderdata",   $orderdata);
			$this->assign("orderProductdata",   $orderProductdata);
			$this->assign("areaData",   $Areadata);
			$this->assign("Otherdata",   $Otherdata);
			$this->display();

		}

	}


	/**
	 * 订单管理员附言
	 **/
	public function orderNote(){
		echo json_encode(D('Order')->orderEditNote());
	}
	/**
	 * 订单支付
	 **/
	public function orderCollection(){
		if(IS_POST){
			$Otherdata = D('Order')->orderPay();
			if($Otherdata['status'] ==1)
				echo '<script type="text/javascript">parent.actionCallback();</script>';
			else
				echo '<script type="text/javascript">parent.actionFailCallback();</script>';
		}else{
			$order_id = I('order_id')?I('order_id'):0;
			$orders = D('Order')->getOrder($order_id);
			$orderdata = D('Order')->getOrderdata($order_id);
			//订单支付方式
			$Otherdata = D('Order')->orderOtherdata();
			$this->assign("payment",   $Otherdata['payment']);
			$this->assign("order",   $orders);
			$this->assign("orderdata",   $orderdata);
			$this->display();
		}

	}
	/**
	 * 发货显示
	 **/
	public function orderDeliver(){
		if(IS_POST){
			$Otherdata = D('Order')->orderDeliver();
			if($Otherdata['status'] ==1){
									
				echo '<script type="text/javascript">parent.actionCallbackmy("'.$Otherdata['info'].'");</script>';
			}
				
			else{
				echo '<script type="text/javascript">parent.actionFailCallbackmy("'.$Otherdata['info'].'");</script>';
			}
				
		}else{
			$order_id = I('order_id')?I('order_id'):0;
			$orders = D('Order')->getOrder($order_id);
			$orderdata = D('Order')->getOrderdata($order_id);
			$productdata = D('Order')->getOrderProductdata($order_id);
			//订单支付方式
			$Otherdata = D('Order')->orderOtherdata();
			// 添加地区信息获取 编辑同类地址的数据组
			$Areadata = D('Areas')->getOtherAreasdata($orderdata['city'],$orderdata['area']);
			$this->assign("areaData",   $Areadata);
			$this->assign("delivery",   $Otherdata['delivery']);
			$this->assign("code",   $Otherdata['code']);
			$this->assign("order",   $orders);
			$this->assign("orderdata",   $orderdata);
			$this->assign("product",   $productdata);
			$this->display();
		}
	}
	/**
	 * 退款显示
	 **/
	public function orderRefundment(){
		if(IS_POST){
			$Otherdata = D('Order')->orderRefundment();
			if($Otherdata['status'] ==1)
				echo '<script type="text/javascript">parent.actionCallback();</script>';
			else
				echo '<script type="text/javascript">parent.actionFailCallback();</script>';
		}else{
			$order_id = I('order_id')?I('order_id'):0;
			$orders = D('Order')->getOrder($order_id);
			$this->assign("order",   $orders);
			$this->display();
		}

	}
	/**
	 * 订单完成/作废
	 **/
	public function orderComplete(){
		echo json_encode(D('Order')->orderComplete());
	}

	/**
	 * 收款单列表
	 **/
	public function orderCollectionlist(){

		$list = D('Order')->orderCollectionlist();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 收款单回收站列表
	 **/
	public function collectionRecycle(){
		$list = D('Order')->orderCollectionlist(array('is_del' => '1'));
		$this->assign('list',$list);
		$this->display('orderCollectionlist');
	}
	/**
	 * 收款单和回收站 批量删除
	 **/
	public function OrderCollectionDelall(){
		$ids = array();
		$type   = I('type');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = D('Order')->collectionDel($ids[$i]);
			else
				$arry = D('Order')->collectionRecycledel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	/**
	 * 收款单查看
	 **/
	public function collectionShow(){
		$data = D('Order')->collectionShow();
		$this->assign('data',$data);
		$this->display();
	}
	/**
	 * 退款单列表
	 **/
	public function orderRefundmentlist(){
		$data['pay_status'] = array('neq',0);//非退款申请状态
		$list = D('Order')->orderRefundmentlist($data);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 退款单回收站列表
	 **/
	public function refundmentRecycle(){
		$list = D('Order')->orderRefundmentlist(array('is_del' => '1'));
		$this->assign('list',$list);
		$this->display('orderRefundmentlist');
	}
	/**
	 * 退款单和回收站 批量删除
	 **/
	public function OrderRefundmentDelall(){
		$ids = array();
		$type   = I('type');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = D('Order')->refundmentDel($ids[$i]);
			else
				$arry = D('Order')->refundmentRecycledel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	/**
	 * 退款单查看
	 **/
	public function refundmentShow(){
		$data = D('Order')->refundmentShow();
		$this->assign('data',$data);
		$this->display();
	}
	/**
	 * 发货单列表
	 **/
	public function orderDeliveryList(){

		$list = D('Order')->orderDeliveryList();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 发货单回收站列表
	 **/
	public function deliveryRecycle(){
		$list = D('Order')->orderDeliverylist(array('is_del' => '1'));
		$this->assign('list',$list);
		$this->display('orderDeliveryList');
	}
	/**
	 * 发货单和回收站 批量删除
	 **/
	public function OrderDeliveryDelall(){
		$ids = array();
		$type   = I('type');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = D('Order')->DeliveryDel($ids[$i]);
			else
				$arry = D('Order')->DeliveryRecycledel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	/**
	 * 发货单查看
	 **/
	public function deliverShow(){
		$data = D('Order')->DeliveryShow();
		$this->assign('data',$data);
		$this->display();
	}
	/**
	 * 退款单申请列表
	 *
	 * @author  haichao
	 */
	public function refundmentList(){
		$data['pay_status'] = 0;//退款申请状态
		$list = D('Order')->orderRefundmentlist($data);
		$this->assign('list',$list);
		$this->display('orderRefundmentlist');
	}
	/**
	 * 处理退款单显示
	 */
	public function refundmentDocshow(){
		$admin = session('UserInfo');
		$id = I('get.id');
		$refund = D('Order')->refundmentShow($id);
		$productList = D('Order')->getOrderProductdata($refund['order_id']);
		$this->assign('productList',$productList);
		$this->assign('data',$refund);
		$this->display('refundmentShow');

		if(IS_POST){
			$data = I('data');
			$data['dispose_time'] = time();
			$data['admin_id'] = $admin['uid'];
			$data['id'] = (isset($data['id']) && intval($data['id']) >0)?$data['id']:0;
			M('refundment_doc')->where('id = '.$data['id'])->save($data);
			echo '<script>popupalert("申请处理","'.U('order/orderRefundmentlist').'");</script>';
		}

	}
	/**
	 * 收款单 退款单 罚款单 回收站恢复
	 *
	 * @author  haichao
	 */
	public function regain(){
		$action = I('get.action');
		$action = $action.'Regain';
		$ids = array();
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('Order')->$action($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	//购买商品详情
	public function shopTemplate(){
		$order_id = I('get.id');
		$orderProductData = D('Order')->getOrderProductdata($order_id);
		$orderData = D('Order')->where('order_id = '.$order_id)->find();
		$orderData['member_code'] = D('Members')->where(array('member_id'=>$orderData['member_id']))->getField('member_code');
		$orderDatas = M('order_data')->where('order_id = '.$order_id)->find();
		$shopDetail = D('Members')->shopDetail($orderData['member_id']);
		$this->assign('shopdetail',$shopDetail);
		$this->assign('data',$orderData);
		$this->assign('product',$orderProductData);
		$this->assign('orderdata',$orderDatas);
		$this->display();
	}
	public function pickTemplate($type='0'){
		$order_id = I('get.id');
		$orderProductData = D('Order')->getOrderProductdata($order_id);
		$orderData = D('Order')->where('order_id = '.$order_id)->find();
		$orderData['member_code'] = D('Members')->where(array('member_id'=>$orderData['member_id']))->getField('member_code');
		$orderData['shipping'] = D('Delivery')->where(array('id'=>$orderData['shipping_id']))->getField('name');
		$orderDatas = M('order_data')->where('order_id = '.$order_id)->find();
		$orderDatas['address'] = D('Areas')->getAddressDetail($orderDatas['province'],$orderDatas['city'],$orderDatas['area'],$orderDatas['address']);
		$shopDetail = D('Members')->shopDetail($orderData['member_id']);
		$this->assign('shopdetail',$shopDetail);
		$this->assign('data',$orderData);
		$this->assign('product',$orderProductData);
		$this->assign('orderdata',$orderDatas);
		if($type=='0')
			$this->display();
	}
	public function mergeTemplate(){
		$this->pickTemplate('1');
		$this->display();
	}
	public function expressTemplate(){
		$order_id = I('get.id');
		$orderDatas = M('order_data')->where('order_id = '.$order_id)->find();
		$order = M('order')->where('order_id = '.$order_id)->find();
		$express = M('express')->select();
		$this->assign('orderRow',$orderDatas);
		$this->assign('order',$order);
		$this->assign('express',$express);
		$this->display();
	}
	public function expressPrint(){
		$config_conver = array();
		$this->layout  = 'print';

		$order_id     = I('order_id');
		$express_id   = I('express_id',1,'intval');
		$expressObj   = D('express');
		$expressRow   = $expressObj->where('id = '.$express_id)->find();
		if(empty($expressRow))
		{
			die('不存在此快递单信息');
		}

		$expressConfig     = unserialize($expressRow['config']);
		import("Common.Extend.Express");
	 	$expresswaybillObj = new \Express();
		
		$config_conver       = $expresswaybillObj->conver($expressConfig,$order_id);
		$this->assign('config_conver',str_replace('trackingLeft','letterSpacing',$config_conver));
		$this->assign('order_id',$order_id);
		$this->assign('expressRow',$expressRow);
		$this->display();
	}
	/**
	 * 售后服务列表
	 * @author  haichao
	 */
	public function afterSaleList(){
		$data = I('data');
		$lists = D('AfterSale')->getAfterSaleList($data);
		$this->assign('list',$lists['list']);
		$this->assign('page',$lists['page']);
		$this->assign('type',$data['type']);
		$this->assign('order_no',$data['order_no']);
		$this->display();
	}
	/**
	 * 售后服务查看
	 * @author  haichao
	 */
	public function afterSaleShow(){
		if(IS_POST){
			$data = D('AfterSale')->updateAfterSale();
			echo json_encode($data);exit();
		}
		$data = D('AfterSale')->afterSaleShow();
		$order_id = D('Order')->where(array('order_no'=>$data['order_no']))->getField('order_id');
		$pay_status = D('Order')->where(array('order_no'=>$data['order_no']))->getField('pay_status');
		$distribution_status = D('Order')->where(array('order_no'=>$data['order_no']))->getField('distribution_status');
		$status = D('Order')->where(array('order_no'=>$data['order_no']))->getField('status');
		$data['order_id'] = $order_id;
		$data['pay_status'] = $pay_status;
		$data['distribution_status'] = $distribution_status;
		
		$productList = D('Order')->getOrderProductdata($order_id);
		$this->assign('productList',$productList);
		$this->assign('data',$data);
		$this->display();
		
	}
	//退款操作 @author  haichao
	public function orderRefund(){
		if(IS_POST){
			$Otherdata = D('Order')->orderRefund();
			if($Otherdata['status'] ==1)
				echo '<script type="text/javascript">parent.actionCallback();</script>';
				//echo '<script type="text/javascript">window.reload();</script>';
			else
				echo '<script type="text/javascript">parent.alert();</script>';
		}else{
			$order_id = I('order_id')?I('order_id'):0;
			$orders = D('Order')->getOrder($order_id);
			$this->assign("order",   $orders);
			$this->display();
		}
	}
	//售后删除 @author  haichao
	public function afterSaleDel(){
		$id = I('id');
		echo json_encode(D('AfterSale')->del($id));
	}
	//售后批量删除
	public function afterSaleDelall(){
		$ids = array();
		$type   = I('type');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = D('AfterSale')->del($ids[$i]);
		}
		echo json_encode($arry);
	}
	public function test(){
		//dump(json_decode('{"120000":"","130000":"河北省","140000":"山西省","150000":"内蒙古自治区","210000":"辽宁省","220000":"吉林省","230000":"黑龙江省","310000":"上海市","320000":"江苏省","330000":"浙江省","340000":"安徽省","350000":"福建省","360000":"江西省","370000":"山东省","410000":"河南省","420000":"湖北省","430000":"湖南省","450000":"广西壮族自治区","460000":"海南省","500000":"重庆市","510000":"四川省","520000":"贵州省","530000":"云南省","540000":"西藏自治区","610000":"陕西省","620000":"甘肃省","630000":"青海省","640000":"宁夏回族自治区","650000":"新疆维吾尔自治区"}'));
		$status = M('after_sale')->where(array('id'=>9))->getField('status,type');
		dump($status);

	}
}