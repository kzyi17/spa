<?php
namespace Index\Model;
use Think\Model;

class FreightCompanyModel extends Model{
	
	/**
	 * 添加物流公司 资料
	 * @author Muke
	 * @param array $data
	 * @return boolean
	 */
	public function addData($data){
		if(empty($data)||!is_array($data)){
			return false;
		}
		
		return $this->add($data);
	}
	
	/**
	 * 修改物流公司
	 * @author Muke
	 * @param int $id
	 * @param array $data
	 * @return boolean
	 */
	public function editData($id,$data){
		if(!$id||empty($data)||!is_array($data)){
			return false;
		}
		
		return $this->where('id='.$id)->save($data);
	}
	
	/**
	 * 查询物流公司资料
	 * @author Muke
	 * @param int $id
	 */
	public function loadData($id){
		if($id){
			return $this->where(array("id"=>$id))->find();
		}
		return array();
	}
	
	
///////////////////////////////////////////////////////////////////////////////////////////	
	/**
	 * 物流公司列表
	 * @return multitype:string
	 * @author  homter
	 */
	public function getFreightList(){
		return array(
				array('type' => 'CNEMS' , 'name' => '中国邮政EMS'),
				array('type' => 'CNST' , 'name' => '申通快递'),
				array('type' => 'CNTT' , 'name' => '天天快递'),
				array('type' => 'CNYT' , 'name' => '圆通速递'),
				array('type' => 'CNSF' , 'name' => '顺丰速运'),
				array('type' => 'CNYD' , 'name' => '韵达快递'),
				array('type' => 'CNZT' , 'name' => '中通速递'),
				array('type' => 'CNLB' , 'name' => '龙邦物流'),
				array('type' => 'CNZJS' , 'name' => '宅急送'),
				array('type' => 'CNQY' , 'name' => '全一快递'),
				array('type' => 'CNHT' , 'name' => '汇通速递'),
				array('type' => 'CNMH' , 'name' => '民航快递'),
				array('type' => 'CNYF' , 'name' => '亚风速递 '),
				array('type' => 'CNKJ' , 'name' => '快捷速递 '),
				array('type' => 'DDS' , 'name' => '快递'),
				array('type' => 'CNHY' , 'name' => '华宇物流'),
				array('type' => 'CNZY' , 'name' => '中铁快运'),
				array('type' => 'FEDEX' , 'name' => 'FedEx'),
				array('type' => 'UPS' , 'name' => 'UPS '),
				array('type' => 'DHL' , 'name' => 'DHL'),
				array('type' => 'OTHER' , 'name' => '其它')

			);
	}
	public function getFreightCompanyList(){

		$result = array();
		$where = array('is_del' => 0);
		$count = $this->where($where)->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
		$showPage = $page->show();
		$list = $this->where($where)->order("sort")->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['statusTxt'] = $v['status'] == 1 ? '启用' : "<font color='red'>禁用</font>";
			$list[$k]['chStatusTxt'] = $v['status'] == 0 ? "启用" : "禁用";
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);
		return $result;

	}
	/**
	 * 编辑物流公司
	 * @param unknown_type $datas
	 * @return multitype:number string |multitype:number string Ambigous <string, mixed>
	 * @author  homter
	 */
	public function Edit($datas){
		if (empty($datas["freight_name"])) {
			return array('status' => 0, 'info' => "物流公司名称不能为空哦");
		}

		if(!empty($datas['id']) || intval($datas['id']) > 0){

			$status1 = $this->where("`id`= ".$datas['id'])->save($datas);
		}
		else {
			$status2 = $this->add($datas);
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('System/freightList'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('System/freightList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息");
		}
	}

	/**
	 * 获取物流公司信息
	 * @param unknown_type $id
	 * @author  homter
	 */
	public function getFreightCompany($id = 0){
		$result = array();
		$result = $this->where("`id`=".$id)->find();

		return $result;
	}
	/**
	 * 获取物流公司名称通过名称类型玛
	 * @param unknown_type $id
	 * @author  homter
	 */
	public function getFreighName($type){
		$result = $this->where("`code`= '".$type."'")->getField('name');
		return $result;
	}
}