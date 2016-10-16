<?php
namespace Index\Model;
use Think\Model;
class PaymentModel extends CommonModel {


	/**
	 * 编辑添加支付方式
	 * @param unknown $data
	 * @return multitype:number string |multitype:number string NULL
	 * @author  haichao
	 */
	public function Edit($data){
		$data['note'] = I('content');
		/*添加验证*/
		if (empty($data["name"])) {
			return array('status' => 0, 'info' => "支付方式名称不能为空哦");
		}

		if(!empty($data['id']) || intval($data['id']) > 0){
			$name = $this->where("`id`= ".$data['id'])->getField('name');
			$status1 = $this->where("`id`= ".$data['id'])->save($data);
			$sql = $this->getLastSql();
			if($status1>0){
				$log = '编辑支付方式'.$name;
				$this->sql_log($log,$sql,6,1);
			}
		}
		else {
			$status2 = $this->add($data);
			$sql = $this->getLastSql();
			if($status2>0){
				
				$log = '添加支付方式'.$data["name"];
				$this->sql_log($log,$sql);
			}
		}
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('System/paymentList'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('System/paymentList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/paymentList'));
		}
	}

	public function getPayment($Id){
		return $this->where("`id`= ".$Id)->find();
	}
	public function getType($payment_id){
		$paymentData = $this->where(array('id' => $payment_id))->field('type')->find();
		return $paymentData['type'];
	}

}