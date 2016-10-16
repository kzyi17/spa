<?php
namespace Index\Model;
use Think\Model;

class AdgoodsModel extends Model{
	
    
    /**
     * 获取列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getList($where,$limit,$offset,$order){
    
        $field = 'adgoods.*';
        $field.= ',merchant.merchant_name';
        
        $this->field($field);
        
        if($where) $this->where($where);
        if($offset) $this->limit($limit,$offset);
        $this->order($order);
    
        $this->join('merchant on merchant.merchant_id = adgoods.merchant_id','LEFT');
    
        return $this->select();
    }
    
    /**
     * 获取总数
     * @author Muke
     * @param unknown $where
     */
    public function getCount($where){
        return $this->where($where)->count();
    }
    
    /**
     * 加载项目信息
     * @author Muke
     * @param int $cid
     * @return Array
     */
    public function getInfo($id){
        if($id){
            return $this->where(array('adgoods_id'=>$id))->find();
        }
        return null;
    }
    
    /**
     * 添加记录
     * @author Muke
     * @param Array $data
     * @return boolean
     */
    public function addOne($data){
        if (is_array($data) && !empty($data)) {
            if($mid=$this->add($data)){
                return $mid;
            }
            	
        }
        return FALSE;
    }
    
    /**
     * 修改记录
     * @author Muke
     * @param int $mid
     * @param Array $data
     * @return boolean
     */
    public function updateOne($mid,$data){
        if($mid && is_array($data)){
            return $this->where(array("adgoods_id"=>$mid))->save($data);
        }
        return FALSE;
    }


}

