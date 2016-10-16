<?php
namespace Index\Model;
use Think\Model;
use Common\Extend\CCPRestSDK;

class SmscodeModel extends Model{
	/**
	 * 获取短信配置信息
	 * @author homter
	 * @return multitype:Ambigous <mixed, void, NULL> Ambigous <mixed, void, NULL, multitype:>
	 */
	protected  function sendSMSconfig(){
		//主帐号
		$accountSid = C('ACCOUNT_SID');
		//主帐号Token
		$accountToken = C('ACCOUNT_TOKEN');
		//应用Id
		$appId = C('APP_ID');
		//请求地址
		$serverIP = C('SERVER_IP');
		//请求端口
		$serverPort = C('SERVER_PORT');
		//REST版本号
		$softVersion = C('SOFT_VERSION');
		
		return array('accountSid' => $accountSid,
				     'accountToken' => $accountToken,
				     'appId' => $appId,
				     'serverIP' => $serverIP,
				     'serverPort' => $serverPort,
				     'softVersion' => $softVersion,
					);
	}
	
	public function sendCode($params){
		if(empty($params['mobile'])){
			return  array("flag"=>1001,"msg"=>"缺少参数","data"=>null);
		}
		//$checkResult = D("Members")->where(array('member_mobile'=>$params['mobile'])->find());
		// if($checkResult['status']){
			// return  array("flag"=>1008,"msg"=>"该手机号不存在，请重新输入！","data"=>null);
		// }
		$config = $this->sendSMSconfig();
		import("Common.Extend.CCPRestSDK");
		$rest = new \CCPRestSDK($config['serverIP'],$config['serverPort'],$config['softVersion']);
		$rest->setAccount($config['accountSid'],$config['accountToken']);
		$rest->setAppId($config['appId']);
	
		$CheckCode= rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	
		$data = array();
		$data['code'] = $CheckCode;
	
		// 发送模板短信
		$result = $rest->sendTemplateSMS($params['mobile'],array($params['member_code'],$params['delivery_code']),$params['tpl_id']);
	
		if($result == NULL ) {
			return  array("flag"=>1011,"msg"=>"短信验证码出错","data"=>null);
	
		}else {
			if($result->statusCode!=0) {
				//TODO 添加错误处理逻辑
				return  array("flag"=>1011,"msg"=>"errcode：".$result->statusCode."。errmsg：$result->statusMsg","data"=>null);
			}else{
				//TODO 添加成功处理逻辑
				$this->_data = $data;
				//D('smscode')->apply($params['mobile'],$CheckCode);
				return  array("flag"=>200,"msg"=>"errcode：".$result->statusCode."。errmsg：$result->statusMsg","data"=>null);
			}
		}
	
	
	
		return  $this->_response();
	}
}