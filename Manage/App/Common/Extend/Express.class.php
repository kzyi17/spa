<?php
/**
 * @copyright (c) 2011 [group]
 * @file expresswaybill.php
 * @brief 快递单处理类
 * @author chendeshan
 * @date 2011-6-15 14:58:39
 * @version 0.6
 */
class Express
{
	public static $itemData = array(
		'ship_name'=>'收货人-姓名',
		'ship_area_0'=>'收货人-地区1级',
		'ship_area_1'=>'收货人-地区2级',
		'ship_area_2'=>'收货人-地区3级',
		'ship_addr'=>'收货人-地址',
		'ship_tel'=>'收货人-电话',
		'ship_mobile'=>'收货人-手机',
		'ship_zip'=>'收货人-邮编',
		'ship_detail_addr'=>'收货人-地区+详细地址',
		'dly_name'=>'发货人-姓名',
		'dly_area_0'=>'发货人-地区1级',
		'dly_area_1'=>'发货人-地区2级',
		'dly_area_2'=>'发货人-地区3级',
		'dly_address'=>'发货人-地址',
		'dly_tel'=>'发货人-电话',
		'dly_mobile'=>'发货人-手机',
		'dly_zip'=>'发货人-邮编',
		'date_y'=>'当前日期-年',
		'date_m'=>'当前日期-月',
		'date_d'=>'当前日期-日',
		'order_id'=>'订单-订单号',
		'order_price'=>'订单总金额',
		'order_weight'=>'订单物品总重量',
		'order_count'=>'订单-物品数量',
		'order_memo'=>'订单-备注',
		'ship_time'=>'订单-送货时间',
		'shop_name'=>'网店名称',
		'tick'=>'√ - 对号',
	);

	//数据转换
	public function conver($expressConfig,$order_id)
	{
		$resultArray = array(); //函数返回数据
		$wholeData   = array(); //实际的数据
		$areaArray   = array(); //区域id集合,便于一次性查询

		//获取订单信息
		$id       = intval($order_id);
		$orderObj = D('order');
		$orderRow = $orderObj->where('id = '.$id)->find();
		$orderData = D('Order_data')->where('order_id = '.$orderRow['order_id'])->find();
		$member = D('Members')->where('member_id='.$orderRow['member_id'])->find();
		$wholeData['ship_name']   = ($orderRow['accept_name']!='') ? $orderRow['accept_name'] : $member['member_name'];
		$province = D('Areas')->where('area_id='.$orderRow['province'])->getField('area_name');
		$city = D('Areas')->where('area_id='.$orderRow['city'])->getField('area_name');
		$area = D('Areas')->where('area_id='.$orderRow['area'])->getField('area_name');
		$wholeData['ship_area_0'] = $province ? $province : '';
		$wholeData['ship_area_1'] = $city     ? $city     : '';
		$wholeData['ship_area_2'] = $area     ? $area     : '';
		$wholeData['ship_addr']   = $orderRow['address'];
		$wholeData['ship_tel']    = $orderData['telphone'];
		$wholeData['ship_mobile'] = $orderData['mobile'];
		$wholeData['ship_zip']    = $orderData['postcode'];
		$wholeData['ship_detail_addr'] = $wholeData['ship_area_0'].$wholeData['ship_area_1'].$wholeData['ship_area_2'].$orderRow['address'];
		$provinced = D('Areas')->where('area_id='.$member['province'])->getField('area_name');
		$cityd = D('Areas')->where('area_id='.$member['city'])->getField('area_name');
		$aread = D('Areas')->where('area_id='.$member['area'])->getField('area_name');
		$wholeData['dly_name']    = $member['member_name'];
		$wholeData['dly_area_0']  = $provinced ? $provinced : '';
		$wholeData['dly_area_1']  = $cityd ? $cityd : '';
		$wholeData['dly_area_2']  = $aread ? $aread : '';
		$wholeData['dly_address'] = $member['address'];
		$wholeData['dly_tel']     = $member['telphone'];
		$wholeData['dly_mobile']  = $member['member_mobile'];
		$wholeData['dly_zip']     = $member['postcode'];

		$wholeData['date_y']      = date('Y');
		$wholeData['date_m']      = date('m');
		$wholeData['date_d']      = date('d');
		$orderProduct = D('order_product')->where('order_id = '.$orderRow['order_id'])->select();
		$weight = 0;
		foreach($orderProduct as $val){
			$weight += floatval($val['goods_weight']);
		}
		$wholeData['order_id']    = $orderRow['order_no'];
		$wholeData['order_price'] = $orderRow['order_amount'];
		$wholeData['order_weight'] = $weight ? $weight : '';
		$wholeData['order_count']  = count($orderProduct);
		$wholeData['order_memo']   = $orderRow['note'];
		$wholeData['ship_time']    = $orderRow['accept_time'];
		$wholeData['shop_name']    = C('name');
		$wholeData['tick']         = $orderData['invoice'] ? '√' :'';

		//进行数据替换
		foreach($expressConfig as $key => $val)
		{
			$item_tmp             = json_decode($val,true);
			$item_tmp['typeText'] = isset($wholeData[$item_tmp['typeId']]) ? $wholeData[$item_tmp['typeId']] : '';
			$resultArray[]        = json_encode($item_tmp);
		}
		
		return $resultArray;
	}
}
?>