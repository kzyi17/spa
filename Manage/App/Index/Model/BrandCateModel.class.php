<?php 
namespace Index\Model;
use Think\Model;

class BrandCateModel extends  Model{
	/**
	 * 品牌分类列表
	 * @param unknown_type $cateId
	 * @return unknown
	 */
	public function brandCateList($cateId = '0'){
		if($cateId > 0){
			$condition = array('cate_id' => $cateId);
		}
		else{
			$condition = array();
		}
		$count = $this->where($condition)->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, 25);  //载入分页类
		$showPage = $page->show();
		$list = $this->where($condition)->limit("$page->firstRow, $page->listRows")->select();
		$Brand = D('Brand');
		foreach ($list as $k => $v) {
			//$catid = $v['id'];
			//$brandData = $Brand->where("cate_id = '{$catid}'")->select();
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);
		return $result;
	}
	/**
	 * 获取品牌分类添加
	 **/
	public function add($data){
		$M = M("BrandCate");
		if (!$this->_check_brandCate($data)) {
			if($M->add($data))
				return array('status' => 1, 'info' => '品牌分类已经成功添加到系统中', 'url' => "") ;
			else
				return array('status' => 0, 'info' => '品牌分类加入失败', 'url' => U('')) ;
		} else {
			return array('status' => 0, 'info' => '系统中已经存在品牌分类', 'url' => U(''));
		}
	}
	/**
	 * 获取品牌分类编辑
	 **/
	public function edit($data){
		$M = M("BrandCate");
		if (!$this->_check_brandCateEditName($data)) {
			if( $this->where("`cate_id`= ".$data['cate_id'])->save($data))
				return array('status' => 1, 'info' => '品牌分类已经成功保存到系统中', 'url' => "") ;
			else
				return array('status' => 0, 'info' => '品牌分类保存失败', 'url' => U('')) ;
		} else {
			return array('status' => 0, 'info' => '系统中已经存在品牌' , 'url' => U(''));
		}
	}
	/**
	 * 获取品牌分类删除
	 **/
	public function del($data){
		$M = M("BrandCate");
		$id = $data['cate_id'];
		if ($this->where("cate_id = '{$id}'")->delete()) {
			return array('status' => 1, 'info' => '品牌分类删除成功', 'url' => U('')) ;
		} else {
			return array('status' => 0, 'info' => '品牌分类删除失败' , 'url' => U(''));
		}
	}
	/**
	 * 获取品牌分类
	 **/
	private function _check_brandCate($data){
		$M = M("BrandCate");
		$cate_name = $data['cate_name'];
		$data = $M->where("cate_name = '{$cate_name}'")->field('cate_name')->find();
		
		return $data;
	}
	/**
	 * 获取修改品牌分类名称是否重复
	 **/
	private function _check_brandCateEditName($data){
		$M = M("BrandCate");
		$cate_name = $data['cate_name'];
		$cate_id = $data['id'];
		$data = $M->where("cate_name = '{$cate_name}' AND cate_id != '{$cate_id}'")->field('cate_name')->find();
		
		return $data;
	}
	
}

?>