<?php
namespace Index\Model;
use Think\Model;
class DeliveryModel extends CommonModel {

	//首重重量
	private static $firstWeight  = 0;

	//次重重量
	private static $secondWeight = 0;

	/**
	 * 重量等级列表
	 * @return 单位:克
	 * @author  haichao
	 */
	public function getWeightLevel(){
		return array('500','1000','1200','2000','5000','10000','20000','50000');
	}
	/**
	 * 根据重量计算给定价格
	 * @param $weight float 总重量
	 * @param $firstFee float 首重费用
	 * @param $second float 次重费用
	 */
	private static function getFeeByWeight($weight,$firstFee,$secondFee)
	{
		$weight = $weight *1000;
		//当商品重量小于或等于首重的时候
		if($weight <= self::$firstWeight)
		{
			return $firstFee;
		}

		//当商品重量大于首重时，根据次重进行累加计算
		$num = ceil(($weight - self::$firstWeight)/self::$secondWeight);
		return $firstFee + $secondFee * $num;
	}
	/**
	 * 配送方式列表
	 *
	 * @author  haichao
	 */
	public function deliveryList($data=''){
		$data['is_delete'] = $data['is_delete'] ? $data['is_delete'] :0;
		$count      = $this->where($data)->count();;// 查询满足要求的总记录数
		$Page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($data)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key => $val){
			$freight_id = $val['freight_id'];
			$data  = M('freight_company')->where("id = '{$freight_id}'")->select();
			$list[$key]['freightName'] = $data['freight_name'];
		}
		$list_arr = array('list'=>$list, 'page'=>$show);
		return $list_arr;
	}
	/**
	 * 获取指定配送方式信息
	 * @param 配送方式 $id
	 * @author  haichao
	 */
	public function getDelivery($id){
		$data = $this->where("id = '{$id}'")->find();
		return $data;
	}
	/**
	 * 添加编辑配送方式
	 * @param post $data
	 * @return multitype:number string |multitype:number string Ambigous <string, mixed>
	 * @author  haichao
	 */
	public function Edit($data){
		$data['area_groupid']= serialize($data['area_groupid']);
		$data['firstprice']= serialize($data['firstprice']);
		$data['secondprice']= serialize($data['secondprice']);
		$data['is_save_price'] = $data['is_save_price'] ? $data['is_save_price']:0;
		$data['open_default'] = $data['open_default'] ? $data['open_default']:0;
		/*添加验证*/
		if (empty($data["name"])) {
			return array('status' => 0, 'info' => "配送方式名称不能为空哦");
		}



		if(!empty($data['id']) || intval($data['id']) > 0){

			$name = $this->where("`id`= ".$data['id'])->getField('name');
			$status1 = $this->where("`id`= ".$data['id'])->save($data);
			$sql = $this->getLastSql();
			if($status1>0){
				$log = '编辑配送方式'.$name;
				$this->sql_log($log,$sql,6,2);
			}
		}
		else {
			$status2 = $this->add($data);
			$sql = $this->getLastSql();
			if($status2>0){
				
				$log = '添加配送方式'.$data["name"];
				$this->sql_log($log,$sql,6,3);
			}
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('System/deliveryList'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('System/deliveryList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/deliveryList'));
		}
	}
	/**
	 * 删除配送方式
	 * @param unknown $member_id
	 * @return multitype:number string
	 * @author  haichao
	 */
	public function del($id){
		$data['is_delete'] = 1;
		$datas = $this->where("id = '{$id}'")->find();
		$status = $this->where("id = '{$id}'")->save($data);
		$sql = $this->getLastSql();
		if($status>0){
			$log = '配送方式'.$datas["name"].'移到回收站';
			$this->sql_log($log,$sql);
		}
		return ($status) ? array('status' => 1, 'info' => '配送方式 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '配送方式  删除失败');
	}
	/**
	 * 回收站 恢复配送方式
	 **/
	public function regainDelivery($id){
		$data['is_delete'] = 0;
		$datas = $this->where("id = '{$id}'")->find();
		$status = $this->where("id = '{$id}'")->save($data);
		$sql = $this->getLastSql();
		if($status>0){
			$log = '配送方式'.$datas["name"].'恢复';
			$this->sql_log($log,$sql);
		}
		return ($status) ? array('status' => 1, 'info' => '配送方式已经成功恢复', 'url' => "") : array('status' => 0, 'info' => '配送方式  删除恢复');
	}
	/**
	 * 回收站 配送方式删除
	 **/
	public function recycleDel($id){
		$datas = $this->where("id = '{$id}'")->find();
		$status = $this->where("id = '{$id}'")->delete();
		$sql = $this->getLastSql();
		if($status>0){
			$log = '配送方式'.$datas["name"].'删除';
			$this->sql_log($log,$sql);
		}
		return ($status) ? array('status' => 1, 'info' => '配送方式 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '配送方式  删除失败');
	}

	/**
	 * @param $province string 省份的id
	 * @param $weight int 货物的重量
	 * @param $goodsSum float 商品总价格
	 * @return haichao
	 * @brief 配送方式计算管理模块
	 */
	public function getDeliverys($province,$weight = 0,$goodsSum = 0)
	{
		$data = array();

		//获得配送方式表的对象

		//获取配送方式列表
		$where = 'is_delete = 0 and status = 1';
		$list = $this->where($where)->order('sort asc')->select();

		//循环各个配送方式
		foreach($list as $value)
		{
			//设置首重和次重
			self::$firstWeight  = $value['first_weight'];
			self::$secondWeight = $value['second_weight'];

			$data[$value['id']]['id']          = $value['id'];
			$data[$value['id']]['name']        = $value['name'];
			$data[$value['id']]['type']        = $value['type'];
			$data[$value['id']]['description'] = $value['description'];
			$data[$value['id']]['if_delivery'] = '0';

			//当配送方式是统一配置的时候，不进行区分地区价格
			if($value['price_type'] == 0)
			{
				$data[$value['id']]['price'] = self::getFeeByWeight($weight,$value['first_price'],$value['second_price']);
			}
			//当配送方式为指定区域和价格的时候
			else
			{
				$matchKey = '';
				$flag     = false;

				//每项都是以';'隔开的省份ID
				$area_groupid = unserialize($value['area_groupid']);
				foreach($area_groupid as $key => $result)
				{
					//匹配到了特殊的省份运费价格
					if(strpos($result,';'.$province.';') !== false)
					{
						$matchKey = $key;
						$flag     = true;
						break;
					}
				}

				//匹配到了特殊的省份运费价格
				if($flag)
				{
					//获取当前省份特殊的运费价格
					$firstprice  = unserialize($value['firstprice']);
					$secondprice = unserialize($value['secondprice']);
					$data[$value['id']]['price'] = self::getFeeByWeight($weight,$firstprice[$matchKey],$secondprice[$matchKey]);
				}
				else
				{
					//判断是否设置默认费用了
					if($value['open_default'] == 1)
					{
						$data[$value['id']]['price'] = self::getFeeByWeight($weight,$value['first_price'],$value['second_price']);
					}
					else
					{
						$data[$value['id']]['price']       = '0';
						$data[$value['id']]['if_delivery'] = '1';
					}
				}
			}

			//计算保价
			if($value['is_save_price'] == 1)
			{
				$tempProtectPrice = $goodsSum * ($value['save_rate']);
				$data[$value['id']]['protect_price'] = ($tempProtectPrice <= $value['low_price']) ? $value['low_price'] : $tempProtectPrice;
			}
			else
			{
				$data[$value['id']]['protect_price'] = 0;
			}
		}
		return $data;
	}

}