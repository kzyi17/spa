<?php
namespace Index\Model;
use Think\Model;

class AttributeModel extends Model{

	public $attr_name;

	public $cat_id;

	public $attr_type;

	public $attr_values;

	public $attr_group;

	public $is_search;

	public $is_linked;

	public $sort;

	//获取属性
	public function get_lists_by_cate($cate_id){
		return $this->where(array("cat_id"=>$cate_id))->select();
	}

	/**
	 * 插入数据
	 *
	 * @return Int //如果插入数据成功返回行号
	 */
	public function insert(){

		$data = array();
		$data['attr_name'] = $this->attr_name;
		$data['cat_id'] = $this->cat_id;
		$data['attr_type'] = $this->attr_type;
		$data['attr_values'] = $this->attr_values;

		if(!empty($this->is_search))
			$data['is_search'] = $this->is_search;
		if(!empty($this->is_linked))
			$data['is_linked'] = $this->is_linked;
		if(!empty($this->attr_group))
			$data['attr_group'] = $this->attr_group;
		if(!empty($this->sort))
			$data['sort'] = $this->sort;

		return $this->add($data);
	}

	/**
	 * 删除属性分组
	 *
	 * @return boolean
	 */
	public function delete_by_cate($cateid){
		return $this->where(array("cat_id"=>$cateid))->delete();
	}

	/**
	 * 查询句柄
	 * --私有函数
	 * @param string $options
	 */
	private function _query($options = null){
		return $this;
	}
	
	//////////////////////////////////////////////////////
	
	
	
	
	
	
	

//////////////////////////////////////////////////////
// 	private $MC;
// 	private $M;

// 	public function __construct()
// 	{
// 		$this->MC = M("attribute_cate");
// 		$this->M = M("attribute");
// 	}

// 	//添加属性分组
// 	public function attr_cate_add($name,$status = 1,$attr_group = null,$spec_ids = null)
// 	{
// 		$data = array('cate_name'=>$name,
// 					'status'=>$status,
// 					'attr_group'=>$attr_group,
// 					'spec_ids'=>$spec_ids);

// 		return ($this->MC->add($data)) ? TRUE : FALSE;
// 	}

// 	//修改属性分组
// 	public function attr_cate_update($data)
// 	{
// // 		$data = array('cate'=>$id);
// 		return ($this->MC->save($data)) ? TRUE : FALSE;
// 	}

// 	//删除属性分组
// 	public function attr_cate_delete($id)
// 	{
// 		$data = array('cate'=>$id);
// 		return ($this->MC->where($data)->delete()) ? TRUE : FALSE;
// 	}

// 	//获取属性分组列表
// 	public function get_cate_list(){
// 		return $this->MC->select();
// 	}

// 	public function get_cate_by($id){
// 		$data = array('cate_id'=>$id);
// 		return $this->MC->where($data)->find();
// 	}

// 	public function test(){
// 		return D('Arribute','Service')->test();;
// 	}

}