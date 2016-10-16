<?php
namespace Index\Model;
use Think\Model;

/**
 * 返利模型
 * @author Muke
 *
 */
class SupplierModel extends  Model{
	//供应商列表
	//* @param int $type 0分页，1全部（用于导入）
	public function getList($type='0'){
		if(I('type_sn')){
			$where['type_sn'] = I('type_sn');
		}
		if(I('supplier_type')){
			$where['supplier_type'] = I('supplier_type');
		}
		if($where){
			$count      = $this->where($where)->count();// 查询满足要求的总记录数
		}else{
			$count      = $this->count();// 查询满足要求的总记录数
		}
		if($type=='0'){
			$Page = new \Think\Page($count, C('PAGE_SIZE'));  //载入分页类
			$show       = $Page->show();// 分页显示输出
			if($where){
				$list       = $this->order('create_time DESC')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
			}else{
				$list       = $this->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
			}
		}else{
			if($where){
				$list       = $this->order('create_time DESC')->where($where)->select();
			}else{
				$list       = $this->order('create_time DESC')->select();
			}
			
		}
		foreach ($list as $key => $val){
			$province = M('Areas')->where('area_id = '.$val['province'])->field('area_name')->find();
			$city = M('Areas')->where('area_id = '.$val['city'])->field('area_name')->find();
			$area = M('Areas')->where('area_id = '.$val['area'])->field('area_name')->find();
			$list[$key]['areas'] = $province['area_name'].' '.$city['area_name'].' '.$area['area_name'].' ';
			$list[$key]['type_name'] = M('supplier_type')->where(array('type_sn'=>$val['type_sn'],'type'=>1))->getField('supplier_typename');
			$list[$key]['supplier_type'] = M('supplier_type')->where(array('type_sn'=>$val['supplier_type'],'type'=>2))->getField('supplier_typename');
		}
		$lists = array('list'=>$list, 'page'=>$show);
		return $lists;
	}
	//删除供应商
	public function supplierDel($id){
		$id = intval($id) ? intval($id) :0;
		$status = $this->where(array('id'=>$id))->delete();
		if($status){
			return array('status' => 1, 'info' => "删除成功。",'url'=>U('Supplier/supplier'));
		}else{
			return array('status' => 0, 'info' => "删除失败。",'url'=>U('Supplier/supplier'));
		}
	}
	
	public function getSupplierTypeList($type){
		$list       = M('supplier_type')->where('type = '.$type)->order('sort')->select();
		return $list;
	}
	public function supplierTypeEdit($type){
		/*添加验证*/
		$data['supplier_typename'] = I("supplier_typename");
		$data['type_sn'] = I('type_sn');
		$data['desc'] = I('desc');
		$data['sort'] = I('sort');
		$where['type'] = $type;
		
		$id = I('id');
		if (empty($data['supplier_typename'])) {
			return array('status' => 0, 'info' => "类型姓名不能为空哦");
		}
		if(!empty($id) || intval($id) > 0){
			$where['supplier_typename'] = $data['supplier_typename'];
			$where['id'] = array('neq',$id);
		}else{
			$where['supplier_typename'] = $data['supplier_typename'];
		}
		$result = M('supplier_type')->where($where)->find();
		if ($result) {
			return array('status' => 0, 'info' => "类型姓名已存在");
		}
		if (empty($data['type_sn'])) {
			return array('status' => 0, 'info' => "类型编号");
		}
		$wheres['type'] = $type;
		if(!empty($id) || intval($id) > 0){
			$wheres['type_sn'] = $data['type_sn'];
			$wheres['id'] = array('neq',$id);
		}else{
			$wheres['type_sn'] = $data['type_sn'];
		}
		$result1 = M('supplier_type')->where($wheres)->find();
		if ($result1) {
			return array('status' => 0, 'info' => "类型编号已存在");
		}
		if(!empty($id) || intval($id) > 0){
			$status1 = M('supplier_type')->where("`id`= ".I('id'))->save($data);
		}
		else {
			$data['type'] = $type;
			$status2 = M('supplier_type')->add($data);
		}
		if($type==1){
			$myaction = 'supplierType';
		}else{
			$myaction = 'type';
		}
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U("Supplier/$myaction"));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U("Supplier/$myaction"));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U("Supplier/$myaction"));
		}
		return $data;
	}
	
}