<?php
namespace Api\Model;
/**
 * 广告模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
class AdModel{

    /**
     * 广告列表
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 下午2:36:15        
     *
     */
    public function getAdList($param){
        $model = M('ad');
        
        $field = 'ad.*';
        $field .= ",'".C('WEB_STATICS')."' as _url";
        
        $model->field($field);
        
        //查询条件
        if($param['where']){
            $model->where($param['where']);
        }
        
        //排序
        if($param['order']){
            $model->order($param['order']);
        }
        
        //分页
        if($param['page']){
            $model->page($param['page'],$param['limit']);
        }
        
        return $model->select();
    }
    
    /**
     * 获取广告位ID
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 下午2:40:03        
     *
     */
    public function getAdPos($adPosCode){
        $model = M('ad_pos');
        return $model->where(array('code'=>$adPosCode))->find();
    }
    
    
    
} 