<?php
namespace Index\Model;
use Think\Model;

class AreasModel extends  Model{


	/**
	 * 同类地址的数据组(省市区 同类组)
	 * @param unknown $cityId
	 * @param unknown $areaId
	 * @param string $provinceId
	 * @return unknown
	 * @author  haichao
	 */
	public function getOtherAreasdata($cityId,$areaId,$provinceId='0')
	{
		$areas['province']= $cityData  = M('areas')->where("parent_id = '{$provinceId}'")->select();
		if($cityId !=null)
			$areas['city']  = $this->getAreasdata($cityId);
		if($areaId !=null)
		$areas['area']  = $this->getAreasdata($areaId);
		return $areas;
	}
	/**
	 * 通过area_id获取其他同类地区组
	 * @param area_id $Id
	 * @return unknown
	 * @author  haichao
	 */
	 public function getAreasdata($Id){
		$data  = M('areas')->where("area_id = '{$Id}'")->find();
		$parent_id = $data['parent_id'];
		$areas  = M('areas')->where("parent_id = '{$parent_id}'")->select();
		return $areas;
	 }
	 /**
	  * 地区列表
	  * @param number $parent_id
	  * @return unknown
	  * @author  haichao
	  */
	 public function AreasList($parent_id = 0){
	 	$where['parent_id'] = $parent_id;
		$areaName = I('area_name');
		//搜索条件--地区名称
		if($areaName !=null && $areaName !=''){
			$where['area_name'] = array('like',"%$areaName%");
			$search['area_name'] = $areaName;
			unset($where['parent_id']);
		}
		$count      = $this->where($where)->count();;// 查询满足要求的总记录数
		$Page = new \Think\Page($count, C('PAGE_SIZE'),$search);//分页查询数据
		$list = $this->where($where)->order('sort desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		//分页跳转的时候保证查询条件（搜索条件为get）
		
		//分页显示
		$show = $Page->show();
	 	foreach ($list as $key => $val){
 			$area_id = $val['area_id'];
 			$data  = M('areas')->where("parent_id = '{$area_id}'")->select();
 			$list[$key]['child'] = $data? 1: 0;
 			$list[$key]['level'] = $this->areaLevel($area_id);
 			$parents = explode(' ',$this->parentIds($area_id));
 			$list[$key]['parents'] = join(' ',array_unique($parents));
	 	}
	 	$list_arr = array('list'=>$list, 'page'=>$show);
	 	return $list_arr;
	 }
	/**
	 * 地区的深度
	 * @param unknown $areaId
	 * @author  haichao
	 */
	 public function areaLevel($areaId,$level = 0){
	 	$data = $this->where("area_id = '{$areaId}'")->find();
	 	if($data['parent_id'] !=0)
	 		$level = $this->areaLevel($data['parent_id'],$level + 1);
	 	else
	 		return $level;
	 	return $level;
	 }
	 /**
	  * 获取父类 area_id 组
	  * @param 地区ID $areaId
	  * @author  haichao
	  */
	 public function parentIds($areaId,$parentId = ''){
	 	$data = $this->where("area_id = '{$areaId}'")->find();

	 	if($data){
	 		$parentId .= $data['parent_id'].' ';
	 		$parentId .= $this->parentIds($data['parent_id'],$parentId);
	 	}else{
	 		return ;
	 	}
	 	return $parentId;
	 }
	 /**
	  * 获取下级的全部 area_id 组
	  * @param 地区ID $areaId
	  * @author  haichao
	  */
	 public function childIds($parentId,$childId = ''){
	 	$data = $this->where("parent_id = '{$parentId}'")->select();

	 		if($data){
	 			foreach ($data as $val){
	 				$childId .= $val['area_id'].' ';
	 				$childId .= $this->childIds($val['area_id'],$childId);
	 			}
	 		}else{
	 			return ;
	 		}


	 	return $childId;
	 }
	 /**
	  * 删除地区
	  * @param unknown $areaId
	  * @author  haichao
	  */
	 public function  del($areaId){
	 	$data = $this->where("parent_id = '{$areaId}'")->find();
	 	if(!$data){
	 		$areaInfo = D('Areas')->where("area_id='{$areaId}'")->find();
	 		$lastId = D('Areas')->where("area_id='{$areaId}'")->delete();
	 		//判断父类是否有孩子
	 		$parentId = $areaInfo['parent_id'];
	 		$child = (D('Areas')->where("parent_id='{$parentId}'")->find())?1:0;

	 		return ($lastId?array('status' =>1,'info' => '删除成功','child' =>$child,'parent_id' =>$parentId):array('status' =>0,'info' => '删除失败'));
	 	}else{
	 		return array('status' =>0,'info' => '请先删除下级');
	 	}

	 }
	/**
	 * 更新添加地区
	 * @return
	 * @author  haichao
	 */
	 public function areaUpdate(){
	 	$area_id = I('get.area_id');
	 	$parent_id = I('get.parent_id');
	 	//添加
	 	if($parent_id !=null){
	 		$data['area_name'] = I('get.area_name');
	 		$data['parent_id'] = $parent_id;
	 		$data['sort'] = 99;
	 		$Area = $this->where(array('area_name'=>$data['area_name']))->find();
	 		if(!$Area){
		 		$lastId = D('Areas')->add($data);
		 		$info = array_merge($data,array('status' =>1,'area_id' => $lastId));
		 		return ($lastId ? $info : array('status' =>0,'area_id' => 0));
	 		}
	 		return (array('status' =>0,'area_id' => 0,'info'=>'已存在地区名称'));
	 	}
	 	//修改
	 	if($area_id !=null && $area_id!=0){
	 		$value = I('get.value');
	 		$name = I('get.name');
	 		$data[$name] = $value;
	 		if($name=='area_name'){
	 			$Area = $this->where(array('area_name'=>$value))->find();
	 		}
	 		if(!$Area){
		 		$lastId = D('Areas')->where("area_id='{$area_id}'")->save($data);
		 		return ($lastId?array('status' =>1,'info' => '修改成功'):array('status' =>0,'info' => '修改失败'));
	 		}
	 		return (array('status' =>0,'area_id' => 0,'info'=>'已存在地区名称'));

	 	}
	 }

	 public function  getProvince(){
	 	$data = $this->where("parent_id = '{$areaId}'")->find();
	 	if(!$data){
	 		$areaInfo = D('Areas')->where("area_id='{$areaId}'")->find();
	 		$lastId = D('Areas')->where("area_id='{$areaId}'")->delete();
	 		//判断父类是否有孩子
	 		$parentId = $areaInfo['parent_id'];
	 		$child = (D('Areas')->where("parent_id='{$parentId}'")->find())?1:0;

	 		return ($lastId?array('status' =>1,'info' => '删除成功','child' =>$child,'parent_id' =>$parentId):array('status' =>0,'info' => '删除失败'));
	 	}else{
	 		return array('status' =>0,'info' => '请先删除下级');
	 	}

	 }
	 //地址区域与详情的组合
	 public function getAddressDetail($provinceId,$cityId,$areaId,$address){
		$province = D('Areas')->where(array('area_id'=>$provinceId))->getField('area_name');
		$city = D('Areas')->where(array('area_id'=>$cityId))->getField('area_name');
		$area = D('Areas')->where(array('area_id'=>$areaId))->getField('area_name');
		return $province.$city.$area.$address;
	 }
}
?>