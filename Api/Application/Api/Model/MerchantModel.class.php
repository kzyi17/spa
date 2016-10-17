<?php
namespace Api\Model;
/**
 * 商家模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
use Think\Model;
class MerchantModel extends Model{
    
    /**
     * 获取商家详情
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午9:06:16        
     *
     */
    public function getInfo($merchantId){
        if(!$merchantId){
            return null;
        }
        
        $field = 'merchant.*';
        $field.= ',images.folder as cover_folder';
        $field.= ',images.name as cover_name';
        $field.= ',images.type as cover_type';
        $this->field($field);
        
        $this->where("merchant_id=$merchantId");
        $this->join('images on images.img_id = merchant.merchant_cover','LEFT');
        
        $return = $this->find();
        
        if($return){
            if($return['merchant_cover']){
                $return['cover_s'] = C('WEB_STATICS').$return['cover_folder'].$return['cover_name'].'_s.'.$return['cover_type'];
                $return['cover_m'] = C('WEB_STATICS').$return['cover_folder'].$return['cover_name'].'_m.'.$return['cover_type'];
                $return['cover_l'] = C('WEB_STATICS').$return['cover_folder'].$return['cover_name'].'_l.'.$return['cover_type'];
            }else{
                $return['cover_s'] = null;
                $return['cover_m'] = null;
                $return['cover_l'] = null;
            }
            unset($return['cover_folder']);
            unset($return['cover_name']);
            unset($return['cover_type']);
        }
        
        
        
        return $return;
        
    }
    
    /**
     * 获取商家相册
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午10:30:58        
     *
     */
    public function getGallery($merchantId){
        if(!$merchantId){
            return null;
        }
        
        $model = M('merchant_gallery');
        $model->where("merchant_id=$merchantId");
        $model->join('images on images.img_id = merchant_gallery.img_id','LEFT');
        
        $result = array();
        
        foreach ($model->select() as $k=>$v){
            $result[]=array(
                'img_id'=>$v['img_id'],
                'merchant_id'=>$v['merchant_id'],
                'img_desc'=>$v['img_desc'],
                'sort'=>$v['sort'],
                'img_s'=>C('WEB_STATICS').$v['folder'].$v['name'].'_s.'.$v['type'],
                'img_m'=>C('WEB_STATICS').$v['folder'].$v['name'].'_m.'.$v['type'],
                'img_l'=>C('WEB_STATICS').$v['folder'].$v['name'].'_l.'.$v['type'],
            );
        }
        
        return $result;
    }
    
    /**
     * 获取商家美容项目
     * 
     * @author kezhen.yi                  
     * @date 2015年12月27日 下午12:11:00        
     *
     */
    public function getSpaList($merchantId){
        if(!$merchantId){
            return null;
        }
        
        return M('spa')->where("merchant_id=$merchantId")->select();
        
    }
    
} 