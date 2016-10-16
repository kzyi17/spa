<?php
namespace Index\Model;
use Think\Model;

/**
 * 分销等级模型
 * @author Muke
 *
 */
class DistributionLevelModel extends  Model{
	
	/**
	 * 根据积分判断会员等级
	 * @author Muke
	 * @param int $point
	 */
	public function getLevel($points){
		$where['min_points'] = array('ELT',$points);
		$where['max_points'] = array('EGT',$points);
	
		return $this->where($where)->find();
	}
}