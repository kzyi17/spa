<?php
namespace Index\Ajax;

class AttributeAjax{

	public $return = array();//返回信息
	public $status = 0;     //0为失败，1为成功，默认0
	public $info = NULL;   //错误信息

	public function __construct(){
		$this->return = $this->_JSONFormat(array('status'=>$this->status,'info'=>$this->info));
	}


	/**
	 * JSON编码
	 * @param unknown $data
	 * @return JSONstring
	 */
	private function _JSONFormat($data){
		return json_encode($data);
	}


}