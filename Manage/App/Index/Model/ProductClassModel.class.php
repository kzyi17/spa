<?php
namespace Index\Model;
use Think\Model;

class ProductClassModel extends Model{
	
	/**
	 * 加载分类信息
	 * @author Muke
	 * @param int $cid
	 * @return Array
	 */
	public function loadCate($cid){
		if($cid){
			return $this->where(array('class_id'=>$cid))->find();
		}
		return array();
	}
	
	/**
	 * 获取分类列表
	 * @author Muke
	 */
	public function cateLlist(){
		
	}
	
	/**
	 * 添加分类
	 * @author Muke
	 * @param Array $data
	 * @return boolean
	 */
	public function addCate($data){
		if (is_array($data) && !empty($data)) {
			return $this->add($data);
		}
		return FALSE;
	}
	
	/**
	 * 修改分类
	 * @author Muke
	 * @param int $cid
	 * @param Array $data
	 * @return boolean
	 */
	public function updateCate($cid,$data){
		if($cid && is_array($data)){
			return $this->where(array("class_id"=>$cid))->save($data);
		}
		return FALSE;
	}
	
	/**
	 * 删除分类
	 * @author Muke
	 */
	public function delCate($cid){
		
	}
	
//--------------------华丽的分割线-----------------------------------------------------------------------------------
	public function category() {
		if (IS_POST) {
			$act = I('act');
			$data = I('data');
			$data['classname'] = addslashes($data['classname']);
			$data['UserCode'] = $_SESSION['my_info']['UserCode'];
			$data['Role'] = 1;
			$M = M("ProductClass");
			if ($act == "add") { //添加分类
				unset($data[ClassID]);
				if ($M->where($data)->count() == 0) {
					return ($M->add($data)) ? array('status' => 1, 'info' => '分类 ' . $data['classname'] . ' 已经成功添加到系统中', 'url' => "") : array('status' => 0, 'info' => '分类 ' . $data['classname'] . ' 添加失败');
				} else {
					return array('status' => 0, 'info' => '系统中已经存在分类' . $data['classname']);
				}
			} else if ($act == "edit") { //修改分类
				if (empty($data['classname'])) {
					//unset($data['classname']);
					return array('status' => 0, 'info' => '没有更新');
				}
				if ($data['Pid'] == $data['ClassID']) {
					unset($data['Pid']);
				}
				$oldArticleclassname=$M->where("ClassID='".$data['ClassID']."'")->getField("classname");
				return ($M->save($data)) ? array('status' => 1, 'info' => '分类 ' . $oldArticleclassname . ' 已经成功更新', 'url' => "") : array('status' => 0, 'info' => '分类 ' . $oldArticleclassname . ' 更新失败');
			} else if ($act == "del") { //删除分类
				unset($data['Pid'], $data['classname']);
				$result = '';
				$childArticle =M("Article")->where("ClassID='".$data['ClassID']."'")->count();
				$result = $M->where("Pid='".$data['ClassID']."'")->select();
				if (!empty($result)){
					return array('status' => 0, 'info' => '该分类下还有其他分类请先删除子分类， 删除失败');
				}
				if ($childArticle>0){
					return array('status' => 0, 'info' => '该分类下存在文章， 删除失败');
				}
				return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 ' . $data['classname'] . ' 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '分类 ' . $data['classname'] . ' 删除失败');
			}
		} else {
			import("Common.Extend.Category");
			$cat = new \Category('ProductClass', array('ClassID', 'Pid', 'classname', 'fullname'));
			return $cat->getList();               //获取分类结构
		}
	}


	//获取分类列表
	public function getList(){
		import("Common.Extend.Category");
		$cat = new \Category('ProductClass', array('class_id', 'pid', 'class_name', 'fullname'));
		return $cat->getList();
	}

	//添加商品分类
	public function add($data){
		$M = M("ProductClass");
		if (!$this->_check_cate($data)) {
			return ($M->add($data)) ? array('status' => 1, 'info' => '分类 ' . $data['class_name'] . ' 已经成功添加到系统中', 'url' => "") : array('status' => 0, 'info' => '分类 ' . $data['classname'] . ' 添加失败');
		} else {
			return array('status' => 0, 'info' => '系统中已经存在分类' . $data['class_name']);
		}
	}

	//删除商品分类
	public function del($classid){
		$M = M("ProductClass");
		$data = array("class_id"=>$classid);
		$result = $M->where("pid='".$classid."'")->select();
		//检查是否存在子分类
		if(!empty($result)){
			return array('status' => 0, 'info' => '该分类下还有其他分类请先删除子分类， 删除失败');
		}

		return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '分类  删除失败');
	}

	//修改商品分类
	public function edit($data){
		$M = M("ProductClass");

		if(empty($data['class_name'])){
			unset($data['class_name']);
		}

		if($this->_check_cate(array("pid"=>$data['pid'],"class_name"=>$data['class_name']))){
			return array('status' => 0, 'info' => '系统中已经存在分类' . $data['class_name']);
		}

// 		return array('status' => 0, 'info' => json_encode($data));

		return ($M->save($data)) ? array('status' => 1, 'info' => '分类 已经成功更新', 'url' => "") : array('status' => 0, 'info' => '分类 更新失败');

	}

	/**
	 * 检测是否存在分类
	 */
	private function _check_cate($data){
		$M = M("ProductClass");
		if($M->where($data)->count() == 0){
			return false;
		}
		return true;
	}

}

