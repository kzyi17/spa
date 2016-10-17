<?php
/**
 * 美容项目API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Api\Controller;
class SpaController extends CommonController {
    
    
    /**
     * 获取项目列表
     * 
     * @author kezhen.yi                  
     * @date 2015年12月20日 上午12:06:50        
     *
     */
    public function getlist(){
        //检查参数
        $this->checkParam('order_type',true,'int');
        $this->checkParam('city_id',false,'int');
        $this->checkParam('cate_id',false,'int',0);
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
    
        //设置查询条件
        if($this->params['city_id']){
            $where['city_id'] = $this->params['city_id'];
        }
        $where['order_type'] = $this->params['order_type'];
        if($this->params['cate_id']){
            $where['cate_id'] = $this->params['cate_id'];
        }
        if($this->params['keywords']){
            $where['spa_name']  = array('like',"%".$this->params['keywords']."%");
        }
        if($this->params['merchant_id']){//商家ID
            $where['merchant_id'] = $this->params['merchant_id'];
        }
        
        
        //$this->success($this->params);
        //设置排序
        $sort = null;
        switch ($this->params['sort']){
            case 'hits'://按人气
                $sort['hits'] = 'desc';
                break;
            case 'score'://按评分
                $sort['comment_score'] = 'desc';
                break;
            case 'price'://按价格
                $sort['sale_price'] = 'desc';
                break;
            case 'hot'://按热门
                $sort['buy_times'] = 'desc';
                break;
            default://默认按最新
                $sort['spa_id'] = 'desc';
        }
        //$sort['spa_id'] = 'desc';
        
        //查询获取数据
        $data = D('Spa')->getList($where,$sort,$this->params['page'],$this->params['limit']);
    
        $this->success($data);
    }
    
    /**
     * 获取项目详情
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午12:06:50
     *
     */
    public function getinfo(){
        //检查参数
        $this->checkParam('spa_id',true,'int');
    
        $spa_id = $this->params['spa_id'];
    
        $data = D('Spa')->getInfo($spa_id);
    
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
    
    }
    
    /**
     * 获取项目相册
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午12:06:50
     *
     */
    public function getgallery(){
        //检查参数
        $this->checkParam('spa_id',true,'int');
    
        $spa_id = $this->params['spa_id'];
    
        $data = D('spa')->getGallery($spa_id);
    
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
    }
}