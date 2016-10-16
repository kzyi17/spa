<?php
namespace Index\Model;
use Think\Model;

/**
 * 用户账户日志模型
 * @author Muke
 *
 */
class AccountLogModel extends Model{
	
	/**
	 * 获取日志列表
	 * @author Muke
	 */
	public function getList(){
	
	}
	
	/**
	 * 写入日志
	 * @author Muke
	 */
	public function log($data){
		if(is_array($data)){
			return false;
		}
		
		return $this->add($data);
	}
	
}