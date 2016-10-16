<?php
namespace Index\Model;
use Think\Model;

/**
 * 分销等级模型
 * @author Muke
 *
 */
class CommonModel extends  Model{
	
	/**
	 * 后台操作记录
	 * @author haichao
	*/
    protected function admin_log($log){
		$data['log'] = $log;
		$data['ip'] = get_client_ip();
		$adminInfo = session('UserInfo');
		$data['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		$data['create_time'] = time();
		M('admin_log')->add($data);
	}
	//
	protected function sql_log($log,$sql,$type=0,$val=0){
		$statuss = C('LOG_STATUS');
		$status = true;
		if($statuss[$type]){
			if($statuss[$type]['status']=='0'){
				$status = false;
			}else{
				if($statuss[$type]['val_status'][$val]=='0'){
					$status = false;
				}
			}
		}
		if(strpos($sql,"SELECT")===false && $status){
			$data['log'] = $log;
			$data['ip'] = get_client_ip();
			$adminInfo = session('UserInfo');
			$data['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
			$data['create_time'] = time();
			$logs = C('LOG');
			$data['log_type'] = $type;
			$data['log_type_val'] = $val;
			$data['sql_log'] = $sql;
			M('admin_log')->add($data);
		}
	}
}