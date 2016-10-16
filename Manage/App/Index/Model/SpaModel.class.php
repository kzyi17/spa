<?php
namespace Index\Model;
use Think\Model;

class SpaModel extends Model{
	
    
    /**
     * 获取列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getList($where,$limit,$offset,$order,$select=""){
    
        $field = '';
        $field.= 'spa.*';
        $field.= ',spa_cate.cate_name';
        $field.= ',merchant.merchant_name';
        
        $this->field($field);
        if($where) $this->where($where);
        if($offset) $this->limit($limit,$offset);
        $this->order($order);
    
        $this->join('spa_cate on spa_cate.cate_id = spa.cate_id','LEFT');
        $this->join('merchant on merchant.merchant_id = spa.merchant_id','LEFT');
    
        return $this->select();
    }
    
    /**
     * 获取总数
     * @author Muke
     * @param unknown $where
     */
    public function getCount($where){
        return M("Spa")->where($where)->count();
    }
    
    
	/**
	 * 添加记录
	 * @author Muke
	 * @param Array $data
	 * @return boolean
	 */
	public function addOne($data){
		if (is_array($data) && !empty($data)) {
		    $model = M('spa');
			if($mid=$model->add($data)){
			    return $mid;
			}   
			
		}
		return FALSE;
	}
	
	/**
	 * 加载项目信息
	 * @author Muke
	 * @param int $cid
	 * @return Array
	 */
	public function getInfo($id){
		if($id){
			return $this->where(array('spa_id'=>$id))->find();
		}
		return null;
	}
	
	/**
	 * 修改商家
	 * @author Muke
	 * @param int $mid
	 * @param Array $data
	 * @return boolean
	 */
	public function updateOne($mid,$data){
		if($mid && is_array($data)){
			return $this->where(array("spa_id"=>$mid))->save($data);
		}
		return FALSE;
	}
	
	/**
	 * 删除项目
	 *
	 * @author kezhen.yi
	 * @date 2016年3月4日 下午2:56:58
	 *
	 */
	public function delone($spa_id){
	    if($spa_id){
	        return $this->where(array('spa_id'=>$spa_id))->delete();
	    }
	    return FALSE;
	}
	
	
	/**
	 * 获取美容类目列表
	 * 
	 * @author kezhen.yi                  
	 * @date 2016年1月13日 下午7:16:10        
	 *
	 */
	public function getCateList(){
	    return M('spa_cate')->select();
	}
	
	/**
	 * 获取项目相册
	 * 
	 * @author kezhen.yi                  
	 * @date 2016年1月14日 上午11:29:50        
	 *
	 */
	public function getGallery($spa_id){
	    return M('spa_gallery')->join('images on images.img_id=spa_gallery.img_id')->where(array('spa_id'=>$spa_id))->select();
	}
	
	/**
	 * 删除项目相册
	 * @author Muke
	 * @param int $goodsid 商品ID
	 * @return boolean
	 */
	public function delPhoto($spa_id){
	    if($spa_id){
	        return M("spa_gallery")->where(array('spa_id'=>$spa_id))->delete();
	    }
	    return FALSE;
	}
	
	/**
	 * 新增商品相册
	 * @author Muke
	 * @param array $data
	 * @return number
	 */
	public function addPhoto($data){
	    if (is_array($data) && !empty($data)) {
	        return M("spa_gallery")->add($data);
	    }
	    return 0;
	}


}

