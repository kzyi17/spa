<?php
namespace Index\Model;
use Think\Model;
use Think\Model\RelationModel;
use Think;
class OrderModel extends CommonModel{

	protected $_auto = array (
		array('create_time','time',1,'function'), // 对create_time字段在添加的时候写入当前时间戳
		array('status','1',1,'string'),
		array('order_no','getOrder_no',1,'callback'),
	);
	 protected $_link = array(
		'OrderData'=>array(
			'mapping_type'      => HAS_ONE,
			'class_name'        => 'order_data',
			'foreign_key'       =>  'order_id',
			'relation_foreign_key'  =>  'order_id',
		),
		'OrderProduct'=>array(
			'mapping_type'      => HAS_MANY,
			'class_name'        => 'order_product',
			'foreign_key'       =>  'id',
			'relation_foreign_key'  =>  'order_id',
		)
   );
	/**
	 * 订单列表
	 **/
	public function orderList($data){

		//列出订单
		//回收站条件
		$delete = $data['is_del'];
		if(!empty($delete) && $delete =='1')
			$where['is_del'] = 1;
		else
			$where['is_del'] = 0;
		//搜索条件
		$pay_status = $data['pay_status'];
		if($pay_status !='' && $pay_status !=null)
			$where['pay_status'] = $pay_status;
		$distribution_status = $data['distribution_status'];
		if($distribution_status !='' && $distribution_status !=null)
			$where['distribution_status'] = $distribution_status;
		$status = $data['status'];
		if($status !='' && $status !=null)
			$where['status'] = $status;
		$order_no = $data['order_no'];
		if($order_no !='' && $order_no !=null)
			$where['order_no'] = $order_no;
		//分页
		$list      = $this->where($where)->select();// 查询满足要求的总记录数
		$count = count($list);
		$Page       = new \Think\Page($count,C('PAGE_SIZE'));  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($where)->field('goods_amount,order_amount',true)->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//$list       = $this->where($where)->field('goods_amount,order_amount',true)->order('create_time DESC')->select();
		foreach ($list as $key => $val){

			$Member = M('Members')->where(array('member_id'=>$val['member_id']))->field('member_code')->find();
			$list[$key]['memberUser'] = $Member['member_code'];   // 订单姓名
			$Payment = M('Payment')->where(array('id'=>$val['pay_type']))->field('name')->find();
			$list[$key]['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';    // 订单支付方式名称
			$Delivery = M('delivery')->where(array('id'=>$val['shipping_id']))->field('name')->find();
			$list[$key]['deliveryName'] = $Delivery['name'];    // 订单配送方式名称
		}
		$lists = array('list'=>$list, 'page'=>$show);
		return $lists;

	}
	/**
	 * 订单查看
	 **/
	public function orderShow(){
		$order_id = I('get.order_id');
		$order = M('Order')->where("order_id = '{$order_id}'")->find();
		$orderData = M('OrderData')->where("order_id = '{$order_id}'")->find();

		$data = array_merge($order,$orderData);
		//商品信息
		$orderProductData = $this->getOrderProductdata($order_id);
		$data['product'] = $orderProductData;
		//总重量
		$data['goods_weight'] = $this->getOrderProductweight($order_id);
		$provinceId = $orderData['province'];
		$cityId = $orderData['city'];
		$areaId = $orderData['area'];
		//地址
		$province = M('Areas')->where("area_id = '{$provinceId}'")->field('area_name')->find();
		$city = M('Areas')->where("area_id = '{$cityId}'")->field('area_name')->find();
		$area = M('Areas')->where("area_id = '{$areaId}'")->field('area_name')->find();
		$data['provinceName'] = $province['area_name'];
		$data['cityName'] = $city['area_name'];
		$data['areaName'] = $area['area_name'];
		//配送方式 支付方式
		$deliveryId = $data['shipping_id'];
		$payType = $data['pay_type'];
		$delivery = M('delivery')->where("`id` = '{$deliveryId}'")->field('name')->order('sort')->find();
		$payment = M('Payment')->where("`id` = '{$payType}'")->field('name')->order('sort')->find();
		$data['deliveryName'] = $delivery['name'];
		$data['payTypeName'] = ($payment['name']!='') ? $payment['name']:'货到付款';
		//dump($data);
		return $data;
	}
	/**
	 * 订单支付方式，配送方式
	 **/
	public function orderOtherdata(){
		$Delivery = M('delivery')->field('id,name,freight_id')->order('sort')->select();
		foreach($Delivery as $key =>$val){
			$Delivery[$key]['code'] = M('freight_company')->where('id = '.$val['freight_id'])->getField('code');
		}
		$payment = M('Payment')->field('id,name')->order('sort')->select();
		$data['delivery'] = $Delivery;
		$data['payment'] = $payment;
		return $data;
		//
	}
	/**
	 * 订单添加编辑
	 **/
	public function orderEdit(){
		$orderData = I('orderdata');
		$order = I('data');
		$product = I('product');
		//dump($order);dump($orderData);dump($product);
		//表order
		$username = trim($order['username']);
		$userInfo = M('members')->where("`member_code` = '{$username}'")->field('member_id')->find();
		unset($order["username"]);
		$order['member_id'] = $userInfo ? $userInfo['member_id'] : 0;

		//拼接要购买的商品或货品数据,组装成固有的数据结构便于计算价格
		$length = count($product['product_id']);
		$buyInfo = array(
				'goods' => array('id' => array() , 'data' => array()),
				'product' => array('id' => array() , 'data' => array())
		);
		for($i = 0;$i < $length;$i++)
		{
			//货品数据
			if($product['product_id'][$i]!=0)
			{
				$buyInfo['product']['id'][] = $product['product_id'][$i];
				$buyInfo['product']['data'][$product['product_id'][$i]] = array('count' => $product['goods_nums'][$i]);
			}
					//商品数据
			else
			{
				$buyInfo['goods']['id'][] = $product['goods_id'][$i];
				$buyInfo['goods']['data'][$product['goods_id'][$i]] = array('count' => $product['goods_nums'][$i]);
			}
		}
		//订单费用 商品费用等信息
		$fee = $this->count($buyInfo,$order['member_id'],$orderData,$order);
		$goodsResult = $fee['goods'];
		$orderFee = $fee['order'];
		$order["goods_amount"] = $goodsResult['final_sum'];
		$order["order_amount"] = $orderFee['orderAmountPrice'];
		$order["discount"] = $orderData['discount'];
		//dump($orderFee['orderAmountPrice']);
		//$order['order_amount'] = $this->getOrderamount();
		//表orderdata

		$orderData['invoice'] = !empty($orderData["invoice"]) ? $orderData['invoice'] : 0;
		$orderData['is_insured'] = !empty($orderData["is_insured"]) ? $orderData['is_insured'] : 0;
		$orderData['distribution_time'] = !empty($orderData["distribution_time"]) ? $orderData['distribution_time'] : 0;
		$orderData["country"] = 0;
		$orderData["shipping_fee"] =$orderFee['deliveryPrice'];
		$orderData["pay_fee"] =$orderFee['paymentPrice'];
		$orderData["taxes"] = $orderFee['taxPrice'];
		$orderData["promotions"] = $goodsResult['proReduce']+$goodsResult['reduce'];
		$orderData['insured']        = $orderFee['insuredPrice'];
		$orderData["points"] = $goodsResult['point'];
		unset($orderData['discount']);

		//添加订单 修改订单
		if (empty($order["order_id"])) {
			//表order
			unset($order["order_id"]);
			$order['create_time'] = time();
			$order['status'] = 1;
			$order['order_no'] = $this->createOrderNum();
			$lastId = $this->add($order);
			$sql = $this->getLastSql();
			if($lastId){
				$log = '(添加订单订单号：'.$order['order_no'].')金额：'.$order["order_amount"] ;
				$this->sql_log($log,$sql,2,3);
			}
			$info = $lastId ? array('status' => 1, 'info' => '订单添加成功', 'url' => U('Order/index')):array('status' => 0, 'info' => '订单添加失败', 'url' => '');
			//表orderdata
			$orderData['order_id'] = $lastId;
			M('order_data')->add($orderData);

		}else{
			//表order
			$order_id = $order['order_id'];
			$order_no = $this->where("order_id = '{$order_id}'")->getField('order_no');
			$lastId = $this->where("order_id = '{$order_id}'")->save($order);
			$sql = $this->getLastSql();
			if($lastId){
				$log = '(保存订单订单号：'.$order_no.')金额：'.$order["order_amount"] ;
				$this->sql_log($log,$sql,2,1);
			}
			$info = $lastid ? array('status' => 1, 'info' => '订单保存成功', 'url' => U('Order/index')):array('status' => 0, 'info' => '订单保存失败', 'url' => '');
			//表orderdata
			M('order_data')->where("order_id = '{$order_id}'")->save($orderData);
		}

		//表order_product
		$productIDs = $product;
		if($order_id){
			$Ids = M('order_product')->where("order_id = '{$order_id}'")->field('id')->select();
			M("order_product")->where("order_id = '{$order_id}'")->delete();
		}
		
		for($i=0;$i<count($productIDs);$i++){
			$prudectData['order_id'] = $order_id ? $order_id:$lastId;
			$prudectData['product_id'] = $productIDs["product_id"]? $product["product_id"][$i]:'';
			$prudectData['goods_id'] = $product["goods_id"]? $product["goods_id"][$i]:'';
			
			$prudectData['goods_weight'] = $product["goods_weight"]? $product["goods_weight"][$i]:'';
			$goods_nums = $product["goods_nums"]? $product["goods_nums"][$i]:'';
			$prudectData['goods_price'] = $product['goods_price'][$i];
			$prudectData['real_price'] = $goodsResult['priceone'][$i];
			$prudectData['goods_nums'] = $goods_nums;
			
			//修改序列化goods_array里的goods_nums
			$goodsArray = $product["goods_array"]? $product["goods_array"][$i]:'';
			$goodsArray = htmlspecialchars_decode($goodsArray);

			$data = json_decode($goodsArray,true);
			$data['goods_nums'] = $goods_nums;
			$data['img'] = $data['img'];
			$prudectData['goods_array'] = json_encode($data);
			if($order_id){//删除订单商品order_product表
				$id = $Ids[$i]['id'];
				$prudectData['id'] = $id;
			}
			
			M('order_product')->add($prudectData);
		}
			if($order_id){
				$info = array('status' => 1, 'info' => '订单保存成功', 'url' => U('Order/index'));
			}
			else{
				$info = array('status' => 1, 'info' => '订单添加成功', 'url' => U('Order/index'));
			}

		return $info;
	}
	//订单彻底删除
	public function recycleDel($orderId){
		$Order = M("Order");
		$datas = $Order->where("order_id = '{$orderId}'")->join('members ON members.member_id=order.member_id')->field('members.member_code,order.order_no')->find();
		$orderData = M("order_data");
		$orderProduct = M("order_product");
		$info = $Order->where("order_id = '{$orderId}'")->delete()? array('status' => 1, 'info' => '订单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
		$info = $orderData->where("order_id = '{$orderId}'")->delete()? array('status' => 1, 'info' => '订单数据表 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单数据表  删除失败');
		$info = $orderProduct->where("order_id = '{$orderId}'")->delete()? array('status' => 1, 'info' => '订单商品 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单商品  删除失败');
		$sql = $Order->getLastSql();
		if($info['status']==1){
			
			$log = '删除订单(订单号：'.$datas['order_no'].')会员：'.$datas['member_code'];
			$this->sql_log($log,$sql,2,2);
		}
		return $info;
	}
	//订单删除
	public function del($orderId){

		$Order = M("Order");
		$datas = $Order->where("order_id = '{$orderId}'")->join('members ON members.member_id=order.member_id')->field('members.member_code,order.order_no')->find();
		$data['is_del'] = 1;
		$info = $Order->where("order_id = '{$orderId}'")->save($data)? array('status' => 1, 'info' => '订单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
		$sql = $Order->getLastSql();
		if($info['status']==1){
			
			$log = '订单移到回收站(订单号：'.$datas['order_no'].')会员：'.$datas['member_code'];
			$this->sql_log($log,$sql,2,4);
		}
		return $info;
	}
	//订单恢复
	public function recycleRegain($orderId){

		$Order = M("Order");
		$datas = $Order->where("order_id = '{$orderId}'")->join('members ON members.member_id=order.member_id')->field('members.member_code,order.order_no')->find();
		$data['is_del'] = 0;
		$info = $Order->where("order_id = '{$orderId}'")->save($data)? array('status' => 1, 'info' => '订单 恢复成功', 'url' => "") : array('status' => 0, 'info' => '订单恢复失败');
		$sql = $Order->getLastSql();
		if($info['status']==1){
			$log = '订单恢复(订单号：'.$datas['order_no'].')会员：'.$datas['member_code'];
			$this->sql_log($log,$sql,2,5);
		}
		return $info;
	}
	/**
	 * @brief 产生订单ID
	 * @return string 订单ID
	 */
	public function createOrderNum()
	{
		return date('YmdHis').rand(100000,999999);
	}

	/**
	 * 订单表
	 */
	public function getOrder($orderId)
	{
		$order = M('order')->where("order_id = '{$orderId}'")->find();
		$member_id = $order['member_id'];
		$member = M('Members')->where("member_id = '{$member_id}'")->field('member_code')->find();
		$order['username'] = $member['member_code']?$member['member_code']:'游客';
		$freight_id = M('delivery')->where('id = '.$order['shipping_id'])->getField('freight_id');
		$order['freight_code'] = M('freight_company')->where('id = '.$freight_id )->getField('code');
		return $order;
	}
	/**
	 * 订单信息副表
	 */
	public function getOrderdata($orderId)
	{
		$orderData = M('order_data')->where("order_id = '{$orderId}'")->find();
		return $orderData;
	}
	/**
	 * 订单商品表order_product
	 */
	public function getOrderProductdata($orderId)
	{
		if($orderId !=null && $orderId !=0){
			$orderProduct= M('order_product')->where("order_id = '{$orderId}'")->field('goods_array,real_price')->select();

			for($i=0;$i<count($orderProduct);$i++){
				$real_price = $orderProduct[$i]['real_price'];
				$orderProduct[$i] = json_decode($orderProduct[$i]['goods_array'],true);
				$orderProduct[$i]['data'] = json_encode($orderProduct[$i]);
				$orderProduct[$i]['spec_array'] = json_decode($orderProduct[$i]['spec_array'],true);
				$orderProduct[$i]['real_price'] = $real_price;
				$orderProduct[$i]['img'] = $this->photoHandle($orderProduct[$i]['img']);
			}
		}
		return $orderProduct;
	}
	//图片处理
	public function photoHandle($img,$imgtype='s'){
			$arr = explode('.',$img);
			$type = $arr[count($arr)-1];
			$img = $arr[0].'_'.$imgtype.'.'.$type;
		return $img;
	}
	/**
	 * 订单商品总重量
	 */
	public function getOrderProductweight($order_id)
	{
		$orderProductData = M('OrderProduct')->where("order_id = '{$order_id}'")->field('goods_weight,goods_nums')->select();
		$weight = 0;
		for($i=0;$i<count($orderProductData);$i++){
			$weight += $orderProductData[$i]['goods_weight'] * $orderProductData[$i]['goods_nums'];
		}
		return $weight;
	}
	/**
	 * 获取同类地址的数据组
	 */
	public function getOrderAreasdata($cityId,$areaId,$provinceId='0')
	{
		$areas['province']= $cityData  = M('areas')->where("parent_id = '{$provinceId}'")->select();


		$areas['city']  = M('areas')->where("parent_id = '{$provinceId}'")->select();
		$areas['area']  = M('areas')->where("parent_id = '{$cityId}'")->select();
		return $areas;
	}
	/**
	 * 获取同类地址的数据（单个）
	 */
	 public function getAreasdata($Id){
		$data  = M('areas')->where("area_id = '{$Id}'")->find();
		$parent_id = $data['parent_id'];
		$areas  = M('areas')->where("parent_id = '{$parent_id}'")->select();
		return $areas;
	 }

	 /**
	 * 修改订单管理附言
	 */
	 public function orderEditNote(){
		$order_id = I('order_id')?I('order_id'):0;
		$data['note'] = I('note')?I('note'):'';
		$lastId  = M('order_data')->where("order_id = '{$order_id}'")->save($data);
		return $lastId ?array('status' => 1,'info' => '更新成功','url' => U('')):array('status' => 0,'info' => '没有更新任何信息','url' => U(''));
	 }
	 /**
	 * 订单支付
	 */
	 public function orderPay(){
		$order_id = I('order_id')?I('order_id'):0;
		$data = I('collection');
		$order['status'] = 2;
		if($data['pay_type']!='0'){
			$order['pay_status'] = 1;
		}else{
			$order['pay_status'] = 0;
		}
		$this->addCollectiondoc($order_id,$data);
		
		$lastId  = M('order')->where("order_id = '{$order_id}'")->save($order);
		//插入收款单
		//dump($order_id);
		//dump($data);
		
		return ($lastId) ?array('status' => 1,'info' => '支付成功','url' => U('')):array('status' => 0,'info' => '支付失败','url' => U(''));
	 }

	  /**
	 * 订单配送发货
	 */
	 public function orderDeliver(){
		$order_id = I('order_id');
		$data  = $this->where("order_id = '{$order_id}'")->field('pay_type,pay_status,status,member_id,order_no,goods_amount')->find();
		
		//获取推荐人信息
		$re_members = D('Members')->getRecommendInfo($data['member_id']);
		$member_code = D('Members')->where(array('member_id'=>$data['member_id']))->getField('member_code');
		$order_goods = M('order_product')->where('order_id='.$order_id)->getField('product_id,real_price,goods_nums',true);
		
		$rebateAmount = 0;
		
		foreach ($order_goods as $v){
			$tmpPrice = M('group_price')->where(array('product_id'=>$v['product_id'],'distribution_level_id'=>$re_members['level_id']))->getField('price');
			if($tmpPrice){
				$rebateAmount += ($v['real_price'] - $tmpPrice)*$v['goods_nums'];
			}else{
				$rebateAmount += ($v['real_price']*(100-$re_members['level_discount'])/100)*$v['goods_nums'];
			}
		}
		
		if($re_members and $re_members['level_discount']){
			$rebateData = array(
					'member_id'=>$re_members['member_id'],
					'order_sn'=>$data['order_no'],
					'order_amount'=>$data['goods_amount'],
					'order_member_id'=>$data['member_id'],
					'discount'=>$re_members['level_discount'],
					'amount'=>$rebateAmount,
			);
			//创建待返利
			D('distribution')->forRebate($rebateData);
		}
		
		
		if($data['pay_status']=='0' && $data['pay_type'] !='0'){
			return array('status' => 0,'info' => '未支付，不能发货','url' => U(''));
		}
		if($data['status']=='1' && $data['pay_type'] =='0'){
			return array('status' => 0,'info' => '请先确认','url' => U(''));
		}
		//dump($data);die();
		$datas = I('deliver');
		$orders['status'] = 2;
		$orders['distribution_status'] = 1;
		$orderdata['distribution_time'] = time();;
		$lastId  = M('order')->where("order_id = '{$order_id}'")->save($orders);
		M('order_data')->where("order_id = '{$order_id}'")->save($orderdata);
		//插入发货单
		$this->addDeliverydoc($order_id,$datas);
		/** 发送发货短信通知 */
		$sendcode = I('sendcode');
		if($sendcode){
			$params['mobile'] = $datas['mobile'];
			$code = D('FreightCompany')->getFreighName($datas['freight_code']);
			$params['delivery_code'] = '('.$code.')'.$datas['delivery_code'];
			$params['member_code'] = $datas['name'] ? $datas['name'] : $member_code;
			$tpls = C('TPL_CONFIG');
			$params['tpl_id'] = $tpls['deliver'];
			$data = D('Smscode')->sendCode($params);
			if($data['flag']=200){
				$title=',发货短信已发送';
			}else{
				$title=',发货短信发送失败';
			}
		}
		if($lastId){
			return array('status' => 1,'info' => '发货成功'.$title,'url' => U(''));
		}else{
			return array('status' => 0,'info' => '发货失败'.$title,'url' => U(''));
		}
		
		//return ($lastId) ?array('status' => 1,'info' => '配送','url' => U('')):array('status' => 0,'info' => '配送失败','url' => U(''));
	 }
	 /**
	 * 订单退款
	 */
	 public function orderRefundment(){
		$order_id = I('order_id');
		$order['pay_status'] = 2;
		$data = I('refundment');
		$lastId  = M('order')->where("order_id = '{$order_id}'")->save($order);
		$this->addRefundmentdoc($order_id,$data);
		return ($lastId) ?array('status' => 1,'info' => '退款','url' => U('')):array('status' => 0,'info' => '退款失败','url' => U(''));
	 }
	 /**
	  * 售后退款
	  */
	 public function orderRefund(){
	 	$order_id = I('order_id');
	 	$Order = $this->where("order_id = '{$order_id}'")->find();
		
	 	$order['pay_status'] = 2;
		$data = I('refundment');
		if($Order['pay_status']==1){
		 	$amount = $data['amount'];
		 	$account['member_id'] = $Order['member_id'];
		 	$memberamount = D('Members')->where(array('member_id'=>$Order['member_id']))->getField('my_account');
			$type = D('AfterSale')->where(array('order_no'=>$Order['order_no']))->getField('type');
			if($type=='returns'){
				$type_name = '退货';
				$val = 2;
			}else{
				$type_name = '退款';
				if($type=='refund'){
					$val = 1;
				}else{
					$val = 3;
				}
			}
			
		 	$account['account_type'] = 0;
			$account['member_id'] = $Order['member_id'];
		 	$account['type'] = 0;
		 	$account['event'] = 6;
		 	$account['time'] = time();
		 	$account['amount'] = $amount;
		 	$account['amount_log'] = floatval($memberamount) + floatval($amount);
		 	$account['note'] = '订单 '.$Order['order_no'].' '.$type_name;
		 	M('account_log')->add($account);
		 	$MemberData['my_account'] = $account['amount_log'];
			$sql = $this->getLastSql();
			$log = '(订单号：'.$order_id.')售后服务类型'.$type_name.'后台退款金额成功，金额：'.$amount;
			$this->sql_log($log,$sql,3,$val);
		 	D('Members')->where(array('member_id'=>$Order['member_id']))->save($MemberData);
		 	$lastId  = $this->where("order_id = '{$order_id}'")->save($order);
	 	}else{
	 		$lastId = false;
	 	}
	 	
	 	return ($lastId) ?array('status' => 1,'info' => '退款','url' => U('')):array('status' => 0,'info' => '退款失败','url' => U(''));
	 }
	 /**
	 * 订单完成/作废
	 */
	 public function orderComplete(){
		$type = I('type');
		$order_id = I('id');
		$order['status'] = $type;
		$datas  = M('order')->where("order_id = '{$order_id}'")->find();
		$lastId  = M('order')->where("order_id = '{$order_id}'")->save($order);
		$sql = M('collection_doc')->getLastSql();
		if($type==5){
			$title = '完成';
			$val = 6;
		}else{
			$title = '作废';
			$val = 7;
			$orderData = M('order_product')->where('order_id = '.$order_id)->field('goods_id,product_id,goods_nums')->select();
			foreach($orderData as $key =>$val){
				$this->upStockNums($val['goods_id'],$val['product_id'],intval(-$val['goods_nums']));
			}
		}
		if($lastId){
			$log = '(订单号：'.$datas['order_no'].')后台'.$title;
			$this->sql_log($log,$sql,2,$val);
		}
		return ($lastId) ?array('status' => 1,'info' => '完成/作废','' => U('')):array('status' => 0,'info' => '完成/作废失败','url' => U(''));
	 }
	  /**
	 * 插入收款单
	 */
	 public function addCollectiondoc($orderId,$datas){

		$orders = $this->getOrder($orderId);
		$member_code = D('Members')->where('member_id ='.$orders['member_id'])->getField('member_code');
		$adminInfo = session('UserInfo');
		$datas['order_id'] = $orderId;
		$datas['create_time'] = time();
		if($datas['pay_type']=='0'){
			$datas['pay_status'] = 0;
		}else{
			$datas['pay_status'] = 1;
		}
		unset($datas['pay_type']);
		$datas['is_del'] = $orders['is_del'];
		$datas['order_no'] = $orders['order_no'];
		$datas['trade_no'] = 0;
		$datas['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		$datas['seller_name'] = $adminInfo['user_code']? $adminInfo['user_code'] : 0;
		$status = M('collection_doc')->add($datas);
		$sql = M('collection_doc')->getLastSql();
		if($status){
			$log = '(订单号：'.$datas['order_no'].')后台付款给会员'.$member_code;
			$this->sql_log($log,$sql,2,8);
		}
	 }
	  /**
	 * 插入发货单
	 */
	 public function addDeliverydoc($order_id,$datas){
		$orders = $this->getOrder($order_id);
		$member_code = D('Members')->where('member_id ='.$orders['member_id'])->getField('member_code');
		$adminInfo = session('UserInfo');
		$datas['order_id'] = $order_id;
		$datas['time'] = time();
		$deliveryId = $datas['shipping_id'];
		unset( $datas['shipping_id']);
		$delivery = M('delivery')->where("id = '{$deliveryId}'")->field('name')->find();
		$datas['delivery_type'] = $delivery['name'];
		$datas['is_del'] = $orders['is_del'];
		$datas['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		//dump($datas);
		$status = M('delivery_doc')->add($datas);
		$sql = M('delivery_doc')->getLastSql();
		if($status){
			$log = '(订单号：'.$orders['order_no'].')后台发货给会员：'.$member_code.'物流号:'.$datas['delivery_code'];
			$this->sql_log($log,$sql,2,9);
		}
	 }
	  /**
	 * 插入退款单
	 */
	 public function addRefundmentdoc($order_id,$data){
		$orders = $this->getOrder($order_id);
		$adminInfo = session('UserInfo');
		$data['order_id'] = $order_id;
		$data['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		$data['is_del'] = $orders['is_del'];
		M('members')->where('member_id = '.$orders['member_id'])->setInc('my_account',$data['amount']);
		$account['account_type'] = 0;
		$account['member_id'] = $orders['member_id'];
		$account['type'] = 0;
		$account['event'] = 6;
		$account['time'] = time();
		$account['amount'] = $data['amount'];
		$account['amount_log'] = M('members')->where('member_id = '.$orders['member_id'])->getField('my_account');
		
		$account['note'] = '订单 '.$orders['order_no'].'退款,会员：'.M('members')->where('member_id = '.$orders['member_id'])->getField('member_code');
		M('account_log')->add($account);
		
		$data['content'] = $data['content']?$data['content']:'后台退款';
		$data['dispose_idea'] = time();
	 	$refundment = M('refundment_doc')->where('order_id = '.$order_id)->find();
	 	if($refundment)
	 	{

	 		M('refundment_doc')->save($data);
	 	}
	 	else
	 	{
	 		$data['time'] = time();
			$data['pay_status'] = '2';
			$data['dispose_idea'] = '退款成功';
	 		M('refundment_doc')->add($data);
			$sql = M('delivery_doc')->getLastSql();
			if($status){
				$log = '(订单号：'.$orders['order_no'].')后台更改支付状态为退款：';
				$this->sql_log($log,$sql,2,10);
			}
	 	}


	 }
	 /**
	 * 收款单列表
	 */
	 public function orderCollectionlist($data){
		$is_del = $data['is_del']?$data['is_del']:0;
		$list = M('collection_doc')->where("`is_del` = '{$is_del}'")->select();

		foreach ($list as $key => $val){
			$orderId = $val['order_id'];
			$Payment = M('Payment')->where(array('id'=>$val['payment_id']))->field('name')->find();
			
			
			if($val['admin_id']==null){
				$list[$key]['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';
				$Members = M('Members')->where(array('member_id'=>$val['member_id']))->field('member_code')->find();
				$list[$key]['accept_name'] = $Members['member_code'];
			}else{
				$list[$key]['payTypeName'] = '后台支付';
				$Users = M('users')->where(array('uid'=>$val['admin_id']))->field('user_code')->find();
				$list[$key]['accept_name'] = $Users['user_code'];
			}
		}
		return $list;

	 }

	 /**
	 * 收款单删除
	 */
	 public function collectionDel($Id){
		$collectionDoc = M("collection_doc");
		$data['is_del'] = 1;
		$info = $collectionDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '订单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
		return $info;

	 }
	 /**
	  * 收款单回收站恢复
	  */
	 public function collectionRegain($Id){
	 	$collectionDoc = M("collection_doc");
	 	$data['is_del'] = 0;
	 	$info = $collectionDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '订单 已经成功恢复', 'url' => "") : array('status' => 0, 'info' => '订单  恢复失败');
	 	return $info;

	 }
	 /**
	 * 收款单回收站删除
	 */
	 public function collectionRecycledel($Id){
		$collectionDoc = M("collection_doc");
		$info = $collectionDoc->where("id = '{$Id}'")->delete()? array('status' => 1, 'info' => '订单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
		return $info;

	 }
	 /**
	 * 收款单查看
	 */
	 public function collectionShow(){
		$Id = I('id');
		$data = D('collection_doc')->where("`id` = '{$Id}'")->find();
		//用户名
		$orderId = $data['order_id'];
		$Order = $this->where('order_id = '.$orderId)->find();
		//订单信息
		$data['order_no'] = $Order['order_no'];
		$data['accept_name'] = $Order['accept_name'];
		//支付信息
		$Payment = M('payment')->where(array('id'=>$data['payment_id']))->field('name,type')->find();
		$data['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';
		$data['pay_type'] = $Payment['type'];
		if($data['admin_id']){
			$data['pay_type'] = 2;
		}
		//管理员
		$adminId = $data['admin_id'];
		$users = M('users')->where("`uid`= '{$adminId}'")->field('user_code')->find();
		$data['admin'] = $users['user_code'];
		return $data;

	 }
	 /**
	  * 退款单列表
	  */
	 public function orderRefundmentlist($data=''){
	 	$where['is_del'] = $data['is_del']?$data['is_del']:0;
	 	if(isset($data['pay_status']))//退款申请
	 		$where['pay_status'] = $data['pay_status'];
	 	$list = M('refundment_doc')->where($where)->select();
	 	foreach ($list as $key => $val){
	 		$orderId = $val['order_id'];
	 		$Order = $this->getOrder($orderId);
	 		$Payment = M('Payment')->where(array('id'=>$val['payment_id']))->field('name')->find();
	 		$list[$key]['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';
	 		$list[$key]['username'] = $Order['username'];
	 	}
	 	return $list;

	 }
	 /**
	  * 退款单删除
	  */
	 public function refundmentDel($Id){
	 	$refundmentDoc = M("refundment_doc");
	 	$data['is_del'] = 1;
	 	$info = $refundmentDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '退款单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
	 	return $info;

	 }
	 /**
	  * 退款单恢复
	  */
	 public function refundmentRegain($Id){
	 	$refundmentDoc = M("refundment_doc");
	 	$data['is_del'] = 0;
	 	$info = $refundmentDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '退款单 已经成功恢复', 'url' => "") : array('status' => 0, 'info' => '订单  恢复失败');
	 	return $info;

	 }
	 /**
	  * 退款单回收站删除
	  * @param unknown $Id
	  * @return multitype:number string
	  * @author  haichao
	  */

	 public function refundmentRecycledel($Id){
	 	$refundmentDoc = M("refundment_doc");
	 	$info = $refundmentDoc->where("id = '{$Id}'")->delete()? array('status' => 1, 'info' => '退款单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '订单  删除失败');
	 	return $info;

	 }
	 /**
	  * 退款单查看
	  */
	 public function refundmentShow($Id=''){
	 	if($Id=='')
	 		$Id = I('id');
	 	$data = D('refundment_doc')->where("`id` = '{$Id}'")->find();
	 	//用户名
	 	$orderId = $data['order_id'];
	 	$Order = $this->getOrder($orderId);
	 	//用户名

	 	$data['username'] = $Order['username'];
	 	//订单信息
	 	$data['pay_status'] = $Order['pay_status'];
	 	$data['accept_name'] = $Order['accept_name'];
	 	$data['create_time'] = $Order['create_time'];
	 	//支付信息
	 	$Payment = M('payment')->where(array('id'=>$data['payment_id']))->field('name,type')->find();
	 	$data['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';
	 	$data['pay_type'] = $Payment['type'];
	 	//管理员
	 	$adminId = $data['admin_id'];
	 	$users = M('users')->where("`uid`= '{$adminId}'")->field('user_code')->find();
	 	$data['admin'] = $users['user_code'];
	 	return $data;

	 }
	 /**
	  * 发货单列表
	  */
	 public function orderDeliveryList($data){
	 	$is_del = $data['is_del']?$data['is_del']:0;
	 	$list = M('delivery_doc')->where("`is_del` = '{$is_del}'")->select();

	 	foreach ($list as $key => $val){
	 		$orderId = $val['order_id'];
	 		$Order = $this->getOrder($orderId);
	 		$deliveryId = $Order['shipping_id'];
	 		$Payment = M('Payment')->where(array('id'=>$val['payment_id']))->field('name')->find();
	 		$delivery = M('delivery')->where(array('id'=>$deliveryId))->field('name')->find();
	 		$list[$key]['payTypeName'] = ($Payment['name']!='') ? $Payment['name']:'货到付款';
	 		$list[$key]['username'] = $Order['username'];
	 		$list[$key]['order_no'] = $Order['order_no'];
	 		$list[$key]['pname'] = $delivery['name'];
	 	}
	 	return $list;

	 }
	 /**
	  * 发货单删除
	  */
	 public function DeliveryDel($Id){
	 	$deliveryDoc = M("delivery_doc");
	 	$data['is_del'] = 1;
	 	$info = $deliveryDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '退款单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '退款单  删除失败');
	 	return $info;

	 }
	 /**
	  * 发货单恢复
	  */
	 public function DeliveryRegain($Id){
	 	$deliveryDoc = M("delivery_doc");
	 	$data['is_del'] = 0;
	 	$info = $deliveryDoc->where("id = '{$Id}'")->save($data)? array('status' => 1, 'info' => '退款单 已经成功恢复', 'url' => "") : array('status' => 0, 'info' => '退款单  恢复失败');
	 	return $info;

	 }
	 /**
	  * 发货单回收站删除
	  * @param unknown $Id
	  * @return multitype:number string
	  * @author  haichao
	  */

	 public function DeliveryRecycledel($Id){
	 	$deliveryDoc = M("delivery_doc");
	 	$info = $deliveryDoc->where("id = '{$Id}'")->delete()? array('status' => 1, 'info' => '退款单 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '退款单  删除失败');
	 	return $info;

	 }
	 /**
	  * 发货单查看
	  */
	 public function DeliveryShow(){
	 	$Id = I('id');
	 	$data = D('delivery_doc')->where("`id` = '{$Id}'")->find();
	 	//用户名
	 	$orderId = $data['order_id'];
	 	$Order = $this->getOrder($orderId);

	 	$data['username'] = $Order['username'];
	 	$data['order_no'] = $Order['order_no'];
	 	//订单信息
	 	$data['pay_status'] = $Order['pay_status'];
	 	$data['accept_name'] = $Order['accept_name'];
	 	//支付信息
	 	$delivery = M('delivery')->where(array('id'=>$Order['shipping_id']))->field('name')->find();
	 	$data['deliveryName'] = $delivery['name'];
	 	$data['pay_type'] = $Payment['pay_type'];
	 	//管理员
	 	$adminId = $data['admin_id'];
	 	$users = M('users')->where("`uid`= '{$adminId}'")->field('user_code')->find();
	 	$data['admin'] = $users['user_code'];
	 	//商品信息
	 	$orderProductData = $this->getOrderProductdata($orderId);
	 	$data['product'] = $orderProductData;
	 	//地址
		$province = M('Areas')->where(array("area_id" => $data['province']))->field('area_name')->find();
		$city = M('Areas')->where(array("area_id" => $data['city']))->field('area_name')->find();
		$area = M('Areas')->where(array("area_id" => $data['area']))->field('area_name')->find();
		$data['provinceName'] = $province['area_name'];
		$data['cityName'] = $city['area_name'];
		$data['areaName'] = $area['area_name'];
	 	return $data;

	 }
	 /**
	  *
	  * @param 商品ID组   $product_id
	  * @author  haichao
	  */
	 public function count($buyInfo,$userId,$dataArray,$order){
		//dump($buyInfo);
		//开始算账
	 	import("Common.Extend.CountSum");
	 	$countSumObj = new \CountSum($userId);
		//dump($dataArray);
	 	$goodsResult = $countSumObj->goodsCount($buyInfo);
	 	$orderFee    = $countSumObj->countOrderFee($goodsResult['sum'],$goodsResult['final_sum'],$goodsResult['weight'],$dataArray['province'],$order['shipping_id'],$order['pay_type'],$goodsResult['freeFreight'],$dataArray['is_insured'],$dataArray['invoice'],$dataArray['discount']);
	 	//获取原价的运费
		//dump($goodsResult);dump($orderFee);
	 	$fee['goods'] = $goodsResult;
	 	$fee['order'] = $orderFee;
	 	return $fee;
	 }
	 //修改库存数量
	public function upStockNums($goodsId,$productId,$value){
		if($productId != 0){
			if($value>=0)
				$me = M('product_stocks')->where('product_id = '.$productId)->setDec('store_nums',$value);
			else
				$me = M('product_stocks')->where('product_id = '.$productId)->setInc('store_nums',-$value);
		}
		if($value>=0)
			$mes = M('product')->where('product_id = '.$goodsId)->setDec('store_nums',$value);
		else
			$mes = M('product')->where('product_id = '.$goodsId)->setInc('store_nums',-$value);
		
	}
	 
}