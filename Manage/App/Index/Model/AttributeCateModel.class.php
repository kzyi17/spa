<?php
namespace Index\Model;
use Think\Model;

class AttributeCateModel extends Model{

	public $cate_name;
	public $attr_group = NULL;
// 	public $spec_ids = NULL;

	/**
	 * 插入数据
	 *
	 * @return Int  //返回插入数据行号
	 */
	public function insert(){
		$data = array(
				'cate_name' => $this->cate_name,
				'attr_group' => $this->attr_group,
		);

		return $this->add($data);
	}

	/**
	 * 更新数据
	 *
	 * @return boolean
	 */
	public function update($id){
		$data = array(
				'cate_id' => $id,
				'cate_name' => $this->cate_name,
				'attr_group' => $this->attr_group,
		);

		return $this->save($data);
	}

	/**
	 * 获取数据列表
	 * @return array
	 */
	public function get_list(){
		return $this->_query()->select();
	}

	public function get_cate($id){
		return $this->_query()->find($id);
	}

	public function selete(){

	}

	/**
	 * 删除属性分组
	 *
	 * @return boolean
	 */
	public function delete_by_id($id){
		return $this->delete($id);
	}


	/**
	 * 删除商品属性分组及属性
	 *  **带事务
	 * @param int
	 * @return boolean
	 */
	public function delete_cate($id){
		$model_cate= M("attribute_cate");
		$model_attr= M("attribute");
		$model_attr->startTrans();

		$result_cate = $model_cate->where(array("cate_id"=>$id))->delete();
		$result_attr = $model_attr->where(array("cat_id"=>$id))->delete();
		if($result_cate){
			$model_attr->commit();
			return true;
		}else{
			$model_attr->rollback();
			return false;
		}
	}

	//需要属性分组及属性
	//带事务
	public function update_cate($cate,$attributes){
		$model= M();
		$flag = TRUE;
		$model->startTrans();

		if(empty($cate) or !is_array($cate))
		{
			return false;
		}

		$cate_id = $cate['cate_id'];
		unset($cate['cate_id']);
		if($model->table('attribute_cate')->where('cate_id='.$cate_id)->save($cate)=== FALSE)
			$flag = FALSE;

		//查询修改前属性，返回ID数组
		$oldattr_ids = M('attribute')->where('cat_id='.$cate_id)->getField("attr_id",true);
		//获取修改属性，返回ID数组
		$newattr_ids = $attributes['attr_id'];
// 		$up_ids = array_intersect($oldattr_ids,$newattr_ids);//计算交集，update
// 		$del_ids = array_diff($oldattr_ids,$newattr_ids);//计算差集，del
// 		$add_ids = array_diff($newattr_ids,$oldattr_ids);//计算差集，add

		//删除属性
		foreach (array_diff($oldattr_ids,$newattr_ids) as $v){
			if(!$model->table('attribute')->where('attr_id='.$v)->delete())
				$flag = FALSE;
		}

		foreach (array_format($attributes) as $k=>$v){
			//修改属性
			if(in_array($v['attr_id'],array_intersect($oldattr_ids,$newattr_ids))){
				if($model->table('attribute')->where('attr_id='.$v['attr_id'])->save($v) === FALSE)
					$flag = FALSE;
			//新增属性
			}else{
				$v['cat_id'] = $cate_id;
				if(!$model->table('attribute')->add($v))
					$flag = FALSE;
			}
		}

		if($flag){
			$model->commit();
			return true;
		}else{
			$model->rollback();
			return false;
		}

	}


	/**
	 * 查询句柄
	 * --私有函数
	 * @param string $options
	 */
	private function _query($options = null){
		return $this;
	}



}