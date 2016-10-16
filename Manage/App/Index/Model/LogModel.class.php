<?php
namespace Index\Model;
use Think\Model;

/**
 * Log日志模型
 * ---账户Log日志
 * ---积分Log日志
 * @author Muke
 *
 */
class LogModel extends Model{
	
	
	/**
	 * 预存款日志
	 * @author Muke
	 * @param int $memberID
	 * @param int $type
	 * @param int $event
	 * @param float $amount
	 * @param string $note
	 */
	public function accountLog($memberID,$type,$event,$amount,$note=''){
		$data['member_id'] = $memberID;
		$data['type'] = $type?1:0;
		$data['event'] = $event;
		$data['account_type'] = 0;
		$data['amount'] = $amount;
		$data['note'] = $note;
		$data['time'] = time();
		
		return M('account_log')->add($data);
	}
	
	/**
	 * 返利账户日志
	 * @author Muke
	 * @param int $memberID
	 * @param int $type
	 * @param int $event
	 * @param float $amount
	 * @param string $note
	 */
	public function distributionLog($memberID,$type,$event,$amount,$note=''){
		$data['member_id'] = $memberID;
		$data['type'] = $type?1:0;
		$data['event'] = $event;
		$data['account_type'] = 1;
		$data['amount'] = $amount;
		$data['note'] = $note;
		$data['time'] = time();
		
		return M('account_log')->add($data);
	}
	
	/**
	 * 等级积分日志
	 * @author Muke
	 * @param int $memberID
	 * @param int $value
	 * @param string $note
	 */
	public function pointsLog($memberID,$value,$note=''){
		$data['member_id'] = $memberID;
		$data['value'] = $value;
		$data['note'] = $note;
		$data['time'] = time();
		return M('points_log')->add($data);
	}
	
	
}