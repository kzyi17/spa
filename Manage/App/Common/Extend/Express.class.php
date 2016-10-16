<?php
/**
 * @copyright (c) 2011 [group]
 * @file expresswaybill.php
 * @brief ��ݵ�������
 * @author chendeshan
 * @date 2011-6-15 14:58:39
 * @version 0.6
 */
class Express
{
	public static $itemData = array(
		'ship_name'=>'�ջ���-����',
		'ship_area_0'=>'�ջ���-����1��',
		'ship_area_1'=>'�ջ���-����2��',
		'ship_area_2'=>'�ջ���-����3��',
		'ship_addr'=>'�ջ���-��ַ',
		'ship_tel'=>'�ջ���-�绰',
		'ship_mobile'=>'�ջ���-�ֻ�',
		'ship_zip'=>'�ջ���-�ʱ�',
		'ship_detail_addr'=>'�ջ���-����+��ϸ��ַ',
		'dly_name'=>'������-����',
		'dly_area_0'=>'������-����1��',
		'dly_area_1'=>'������-����2��',
		'dly_area_2'=>'������-����3��',
		'dly_address'=>'������-��ַ',
		'dly_tel'=>'������-�绰',
		'dly_mobile'=>'������-�ֻ�',
		'dly_zip'=>'������-�ʱ�',
		'date_y'=>'��ǰ����-��',
		'date_m'=>'��ǰ����-��',
		'date_d'=>'��ǰ����-��',
		'order_id'=>'����-������',
		'order_price'=>'�����ܽ��',
		'order_weight'=>'������Ʒ������',
		'order_count'=>'����-��Ʒ����',
		'order_memo'=>'����-��ע',
		'ship_time'=>'����-�ͻ�ʱ��',
		'shop_name'=>'��������',
		'tick'=>'�� - �Ժ�',
	);

	//����ת��
	public function conver($expressConfig,$order_id)
	{
		$resultArray = array(); //������������
		$wholeData   = array(); //ʵ�ʵ�����
		$areaArray   = array(); //����id����,����һ���Բ�ѯ

		//��ȡ������Ϣ
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
		$wholeData['tick']         = $orderData['invoice'] ? '��' :'';

		//���������滻
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