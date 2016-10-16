<?php
namespace Index\Model;
use Think\Model;

/**
 * 商品套餐模型
 * @author Muke
 *
 */
class PackageModel extends Model{
	
	private $_model_pg;
	
	public function _initialize() {
		$this->_model_pg = M('package_goods');
	}
	
	//获取套餐列表
	public function getPackageList($where,$limit,$offset,$order='create_time desc',$select=""){
		
		$result = $this->field($select)->where($where)->limit($limit,$offset)->order($order)->select();
		
		foreach ($result as $k=>$v){
			$tmp_goods = $this->getPackageGoods($v['package_id']);
			$total_price = 0;
			foreach ($tmp_goods as $v){
				$total_price += $v['sell_price'];
			}
			$result[$k]['goods'] = $tmp_goods;
			$result[$k]['total_price'] = sprintf("%.2f", (float)$total_price);
		}
		
		return $result;
	}
	
	//获取套餐总数
	public function getPackageCount($where){
	
		return $this->where($where)->count();
	}
	
	//获取套餐详情
	public function getPackageInfo($package_id){
		$result = $this->where('package_id='.$package_id)->find();
		$distributionPrice = M('package_group_price')->where('package_id='.$package_id)->select();
		$level = M('distribution_level')->select();
		foreach($level as $key => $val){
			$where['distribution_level_id'] = $val['level_id'];
			$where['package_id'] = $package_id;
			$level[$key]['price'] = M('package_group_price')->where($where)->getField('price');
			$level[$key]['id'] = M('package_group_price')->where($where)->getField('id');
		}
		$result['levelPrice'] = $level;
		if($result){
			
			$result['goods'] = $this->getPackageGoods($result['package_id']);
			return $result;
		}
		
		return null;
	}
	
	//新增套餐
	public function addPackage($package_data,$goods_data){
		
		$package_id = $this->add($package_data);
		
		if($package_id){
			foreach ($goods_data as $v){
				$v['package_id'] = $package_id;
				$this->_model_pg->add($v);
			}
			return true;
		}
		return false;
	}
	
	//修改套餐
	public function updatePackage($package_id,$package_data,$goods_data){
		$this->where('package_id='.$package_id)->save($package_data);
		$this->_model_pg->where('package_id='.$package_id)->delete();
		foreach ($goods_data as $v){
			$v['package_id'] = $package_id;
			$this->_model_pg->add($v);
		}
		return true;
	}
	
	//删除套餐（入回收站）
	public function delPackage($package_id){
		return $this->where('package_id='.$package_id)->setField('is_del',1);
	}
	
	//获取套餐内商品
	public function getPackageGoods($package_id){
		
// 		$ids = $this->_model_pg->where('package_id='.$package_id)->getField('goods_id',true);
		$return = array();
		$where['package_id']=$package_id;
		
		foreach ($this->_model_pg->where($where)->select() as $k=>$v){
			$return[$k]=$v;
			$field['CONCAT(product_photo.folder,product_photo.name,"_s.",product_photo.type)']= 'photo';
			$field[] = 'product.name';
			$goods = M('product')
						->field($field)
						->where('product.product_id='.$v['goods_id'])
						->join('product_photo on product_photo.photo_id = product.photo_id','LEFT')
						->find();
			$return[$k]['goods_photo'] = $goods['photo'];
			$return[$k]['goods_name'] = $goods['name'];
		}
		
		return $return;
		
// 		if($ids){
// 			$where['product_id'] = array('IN',$ids);
// 			return D('product')->goodsList($where,0,0,'','product_id,name,sell_price');
// 		}
		
// 		return array();
	}
}
