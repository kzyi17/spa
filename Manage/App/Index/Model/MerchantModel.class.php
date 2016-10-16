<?php
namespace Index\Model;
use Think\Model;

class MerchantModel extends Model{
	
    
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
    
        //$field['CONCAT(product_photo.folder,product_photo.name,"_s.",product_photo.type)']= 'photo';
//         if($select){
//             foreach(explode(',', $select) as $v){
//                 $field[] = 'product.'.$v;
//             }
//         }else{
//             $field[] = 'product.*';
//         }
        $this->field("merchant_id,merchant_name,address,phone");
        if($where) $this->where($where);
        if($offset) $this->limit($limit,$offset);
        $this->order($order);
    
        //$this->join('product_photo on product_photo.photo_id = product.photo_id','LEFT');
    
        return $this->select();
    }
    
    /**
     * 获取总数
     * @author Muke
     * @param unknown $where
     */
    public function getCount($where){
        return M("Merchant")->where($where)->count();
    }
    
    
	/**
	 * 添加记录
	 * @author Muke
	 * @param Array $data
	 * @return boolean
	 */
	public function addOne($data){
		if (is_array($data) && !empty($data)) {
			//return $this->add($data);
			
		    $model = M('merchant');
		    
			if($mid=$model->add($data)){
	            $data = array(
	                '_name' => $data['merchant_name'],
	                '_address' => $data['address'],
	                'merchant_id'=>$mid
	            );
	            $mapData = D('MapApi')->createOne($data);
	            $mapData = json_decode($mapData,true);
	            
	            //return $mapData;
	            
			    if($mapData['status']==1){
                    $map_id = $mapData['_id'];
                    $model->where(array("merchant_id"=>$mid))->save(array('map_id'=>$map_id));//
                    
                    return $mid;
                }else{
                    $model->where(array("merchant_id"=>$mid))->delete();
                    return FALSE;
                }
			}
			
			
		}
		return FALSE;
	}
	
	/**
	 * 加载商家信息
	 * @author Muke
	 * @param int $cid
	 * @return Array
	 */
	public function getInfo($id){
		if($id){
			return $this->where(array('merchant_id'=>$id))->find();
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
			return $this->where(array("merchant_id"=>$mid))->save($data);
		}
		return FALSE;
	}
	
	/**
	 * 获取项目相册
	 *
	 * @author kezhen.yi
	 * @date 2016年1月14日 上午11:29:50
	 *
	 */
	public function getGallery($merchant_id){
	    return M('merchant_gallery')->join('images on images.img_id=merchant_gallery.img_id')->where(array('merchant_id'=>$merchant_id))->select();
	}
    
	/**
	 * 删除项目相册
	 * @author Muke
	 * @param int $goodsid 商品ID
	 * @return boolean
	 */
	public function delPhoto($merchant_id){
	    if($merchant_id){
	        return M("merchant_gallery")->where(array('merchant_id'=>$merchant_id))->delete();
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
	        return M("merchant_gallery")->add($data);
	    }
	    return 0;
	}
	
	/**
	 * 删除商家
	 * 
	 * @author kezhen.yi                  
	 * @date 2016年3月4日 下午2:56:58        
	 *
	 */
	public function delone($merchant_id){
	    if($merchant_id){
	        return M("merchant")->where(array('merchant_id'=>$merchant_id))->delete();
	    }
	    return FALSE;
	}

}

