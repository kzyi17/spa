<?php
namespace Api\Model;
/**
 * 项目模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
use Think\Model;
class SpaModel extends Model{
    
    
    /**
     * 获取美容项目列表集
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     * @param string&&array $condition
     * @param array $order array('field'=>'desc',....)
     * @param string $page ‘page,limit’
     *
     */
    public function getList($condition=null,$order=null,$page=null,$limit=null){
        
        $field = 'spa.*';
        $field.= ',images.folder as cover_folder';
        $field.= ',images.name as cover_name';
        $field.= ',images.type as cover_type';
        
        $this->field($field);
        
        //查询条件
        if($condition){
            $this->where($condition);
        }
    
        //排序
        if($order){
            $this->order($order);
        }
    
        //分页
        if($page){
            $this->page($page,$limit);
        }
        
        $this->join('images on images.img_id = spa.spa_cover','LEFT');
    
        $return = array();
        
        foreach ($this->select() as $k=>$v){
            $return[$k] = $v;
            if($v['spa_cover']){
                $return[$k]['cover_s'] = C('WEB_STATICS').$v['cover_folder'].$v['cover_name'].'_s.'.$v['cover_type'];
                $return[$k]['cover_m'] = C('WEB_STATICS').$v['cover_folder'].$v['cover_name'].'_m.'.$v['cover_type'];
                $return[$k]['cover_l'] = C('WEB_STATICS').$v['cover_folder'].$v['cover_name'].'_l.'.$v['cover_type'];
            }else{
                $return[$k]['cover_s'] = null;
                $return[$k]['cover_m'] = null;
                $return[$k]['cover_l'] = null;
            }
            unset($return[$k]['cover_folder']);
            unset($return[$k]['cover_name']);
            unset($return[$k]['cover_type']);
        }
        
        return $return;
    }
    
    /**
     * 获取项目详情
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午9:06:16        
     *
     */
    public function getInfo($sapId){
        if(!$sapId){
            return null;
        }
        
        $field = 'spa.*';
        $field.= ',merchant.merchant_name,merchant.address,merchant.phone,merchant.map_id';
        $field.= ',images.folder as cover_folder';
        $field.= ',images.name as cover_name';
        $field.= ',images.type as cover_type';
        
        
        $this->field($field);
        
        $this->where("spa_id=$sapId");
        $this->join('merchant on merchant.merchant_id = spa.merchant_id','LEFT');
        $this->join('images on images.img_id = spa.spa_cover','LEFT');
        
        $return = $this->find();
        
        if($return){
            if($return['spa_cover']){
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
     * 获取项目相册
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午10:30:58        
     *
     */
    public function getGallery($sapId){
        if(!$sapId){
            return null;
        }
        $model = M('spa_gallery');
        $model->where("spa_id=$sapId");
        $model->join('images on images.img_id = spa_gallery.img_id','LEFT');
        
        $result = array();
        
        foreach ($model->select() as $k=>$v){
            $result[]=array(
                'img_id'=>$v['img_id'],
                'spa_id'=>$v['spa_id'],
                'img_desc'=>$v['img_desc'],
                'sort'=>$v['sort'],
                'img_s'=>C('WEB_STATICS').$v['folder'].$v['name'].'_s.'.$v['type'],
                'img_m'=>C('WEB_STATICS').$v['folder'].$v['name'].'_m.'.$v['type'],
                'img_l'=>C('WEB_STATICS').$v['folder'].$v['name'].'_l.'.$v['type'],
            );
        }
        
        return $result;
        
    }
    
    
    
} 