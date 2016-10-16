<?php
namespace Index\Model;
use Think\Model;

/**
 * 用户账户模型
 * ---预存款
 * ---等级积分
 * ---返利积分
 * ---累积消费
 * @author Muke
 *
 */
class AccountModel extends CommonModel{
	
	/**
	 * 充值预存款
	 * @author Muke
	 * @param int $memberID
	 * @param float $amount
	 * @return boolean
	 */
	public function addAccount($memberID,$amount){
		
		//修改预存款
		if(M('members')->where('member_id='.$memberID)->setInc('my_account',$amount)){
			//LOG
			$sql = D('Members')->getLastSql();
			$member_code = M('members')->where('member_id='.$memberID)->getField('member_code');
			$log = '增加会员'.$member_code.'的预存款值：'.$amount;
			$this->sql_log($log,$sql,4,8);
			D('log')->accountLog($memberID,0,1,$amount,'');
			return true;
		}else{
			return false;
		}
		
	}
	
	/**
	 * 减少预存款(消费)
	 * @author Muke
	 * @param int $memberID
	 * @param float $amount
	 * @return boolean
	 */
	public function reduceAccount($memberID,$amount){
	
		//修改预存款
		if(M('members')->where('member_id='.$memberID)->setDec('my_account',$amount)){
			//LOG
			$sql = D('Members')->getLastSql();
			$member_code = M('members')->where('member_id='.$memberID)->getField('member_code');
			$log = '减少会员'.$member_code.'的预存款值：'.$amount;
			$this->sql_log($log,$sql,4,9);
			D('log')->accountLog($memberID,1,2,$amount,'');
			return true;
		}else{
			return false;
		}
	
	}
	
	/**
	 * 增加等级积分
	 * @author Muke
	 * @param int $memberID
	 * @param float $points
	 * @return boolean
	 */
	public function addPoints($memberID,$points){
	
		//修改等级积分
		if(M('members')->where('member_id='.$memberID)->setInc('my_points',$points)){
			//LOG
			D('log')->pointsLog($memberID,$points,'');
			return true;
		}else{
			return false;
		}
	
	}
	
	/**
	 * 增加累积消费
	 * @author Muke
	 * @param int $memberID
	 * @param int $amount
	 * @return boolean
	 */
	public function addConsume($memberID,$amount){
		//修改累积消费
		if(M('members')->where('member_id='.$memberID)->setInc('consume',$amount)){
			//LOG
			return true;
		}else{
			return false;
		}
	}
	
	//增加返利积分
	public function addDistribution($memberID,$order_sn){
		//处理带返利记录
		$model_distribution = M('distribution');
		$where = array('order_sn'=>$order_sn);
		$distribution = $model_distribution->where($where)->find();
		if($distribution){
			M('distribution')->where(array('order_sn'=>$order_sn))->setField('status',2);
			//增加返利积分
			M('members')->where('member_id='.$memberID)->setInc('my_distribution',$distribution['amount']);
			//返利积分Log
			D('log')->distributionLog($memberID,0,5,$distribution['amount'],$note='');
			return true;
		}
		
		return false;
		
	}
	
	//预存款记录
	public function accountList($memberID){
		
		$list = M('account_log')->where('member_id='.$memberID)->select();
		foreach($list as $key =>$val){
			$memberData = M('members')->where(array('member_id'=>$val['member_id']))->field('member_code')->find();
			$list[$key]['member_code'] = $memberData['member_code'];
			
		}
		return $list;
	
	}
	
	
	
	
	//减少返利积分
	
	//减少等级积分
	
	
	
	
}