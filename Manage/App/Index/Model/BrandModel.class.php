<?php
namespace Index\Model;
use Think\Model;

class BrandModel extends  Model{
	/**
	 * 品牌列表
	 * @param unknown_type $cateId
	 * @return unknown
	 */
	public function brandList($cateId = '0',$data = ''){
		if($cateId > 0){
			$condition = array('cate_id' => $cateId);
		}
		else{
			$condition = array();
		}
		//搜索条件
		$brand_name = $data['brand_name'];
		if(!empty($brand_name)){
			$where['brand_name'] = array('like', "%$brand_name%");
		}
		$condition = array_merge($condition,$where);
		$count = $this->where($condition)->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, 25);  //载入分页类
		$showPage = $page->show();
		$list = $this->where($condition)->limit("$page->firstRow, $page->listRows")->select();
// 		$M = new Model();
		foreach ($list as $k => $v) {
			$cate_arr = explode(',',$v['category_ids']);
			$list2 = array();

			foreach($cate_arr as $k2 => $v2){
				$cate = M("BrandCate")->where("cate_id =".$cate_arr[$k2])->field('cate_name')->find();
				$list2[$k2] = $cate['cate_name'];
			}

			$str_name =  implode(',', $list2);
			$list[$k]['cate_name']  = $str_name;
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);
		return $result;

	}
	public function add($data){
		$M = M("Brand");
		$data['category_ids'] = implode(',',$data['category_ids']);
		if (!$this->_check_brand($data)) {
			return ($M->add($data)) ? array('status' => 1, 'info' => '品牌 ' . $data['brand_name'] . ' 已经成功添加到系统中', 'url' => "") : array('status' => 0, 'info' => '品牌 ' . $data['brand_name'] . ' 添加失败');
		} else {
			return array('status' => 0, 'info' => '系统中已经存在品牌' . $data['brand_name']);
		}
	}
	
	/**
	 * 删除商品品牌
	 **/
	public function del($brandid){
		$M = M("Brand");
		

		return ($M->where("brand_id = '{$brandid}'")->delete()) ? array('status' => 1, 'info' => '品牌 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '品牌  删除失败');
	}

	/**
	 * 修改商品品牌
	**/
	public function brandEdit($datas){		    
		$M = M("Brand");
		
		
		/*添加验证*/
		if (empty($datas["brand_name"])) {
			return array('status' => 0, 'info' => "品牌名称不能为空哦");
		}
		
		
		$datas['category_ids'] = implode(',',$datas['category_ids']);
		if(!empty($datas['brand_id']) || intval($datas['brand_id']) > 0){
			
			$status1 = $this->where("`brand_id`= ".$datas['brand_id'])->save($datas);
		}
		else {
			$status2 = $this->add($datas);
		}
		
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U(''));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U(''));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息");
		}
	}
	
	/**
	 * 获取品牌信息
	 **/
	public function getBrand($brand_id){
		
		$data = $this->where("brand_id = '{$brand_id}'")->find();
		$data['category_ids'] = explode(',',$data['category_ids']);
		
		return $data;
	}
	/**
	 * 获取品牌信息
	 **/
	private function _check_brand($data){

		$brand_name = $data['brand_name'];
		$data = $this->where("brand_name = '{$brand_name}'")->field('brand_name')->find();
		
		return $data;
	}
	
}

?>