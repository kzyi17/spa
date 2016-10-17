<?php
/**
 * 广告兑换API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Api\Controller;
class ExchangeController extends CommonController {
    
    
    /**
     * 获取广告区商品列表
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午12:06:50
     *
     */
    public function getlist(){
        //检查参数
        $this->checkParam('city_id',true,'int');
        $this->checkParam('cate_id',false,'int',0);
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        //设置查询条件
        //$where['total_point'] = array('gt',0);
        $where['status'] = 0;
        
        if($this->params['cate_id']){
            $where['cate_id'] = $this->params['cate_id'];
        }
        
        //设置排序
        $sort['adgoods_id'] = 'desc';
        
        //查询获取数据
        $data = D('Adgoods')->getList($where,$sort,$this->params['page'],$this->params['limit']);
        
        $this->success($data);
        
    }
    
    /**
     * 获取兑换区商品列表
     *
     * @author kezhen.yi
     * @date 2016年2月3日 上午1:09:38
     *
     */
    public function getExchangeList(){
        //检查参数
        $this->checkParam('city_id',true,'int');
        $this->checkParam('cate_id',false,'int',0);
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
    
        //设置查询条件
        //$where['total_point'] = array('elt',0);
        $where['status'] = 1;
        
        if($this->params['cate_id']){
            $where['cate_id'] = $this->params['cate_id'];
        }
    
        //设置排序
        $sort['adgoods_id'] = 'desc';
    
        //查询获取数据
        $data = D('Adgoods')->getList($where,$sort,$this->params['page'],$this->params['limit']);
    
        $this->success($data);
    
    }
    
    /**
     * 获取广告详情
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午12:06:50
     *
     */
    public function getinfo(){
        //检查参数
        $this->checkParam('adgoods_id',true,'int');
    
        $adgoods_id = $this->params['adgoods_id'];
    
        $data = D('Adgoods')->getInfo($adgoods_id);
    
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
    
    }
    
    /**
     * 获取首页广告列表
     * 
     * @author kezhen.yi                  
     * @date 2016年1月4日 上午7:20:37        
     *
     */
    public function getindexlist(){
        //检查参数
        $this->checkParam('city_id',true,'int');
        $this->checkParam('cate_id',false,'int',0);
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',6);
    
        //设置查询条件
        //$where['city_id'] = $this->params['city_id'];
        $where['is_index'] = 1;
        if($this->params['cate_id']){
            $where['cate_id'] = $this->params['cate_id'];
        }
    
        //设置排序
        $sort['adgoods_id'] = 'desc';
    
        //查询获取数据
        $data = D('Adgoods')->getList($where,$sort,$this->params['page'],$this->params['limit']);
    
        $this->success($data);
    
    }
    
    /**
     * 广告浏览（获取积分）
     * 
     * @author kezhen.yi                  
     * @date 2015年12月20日 上午4:34:20        
     *
     */
    public function gainad(){
        //检查参数
        $this->checkParam('adgoods_id',true,'int');
        $this->checkParam('user_id',true,'int');
        
        $data = D('Adgoods')->getPoint($this->params);
        
        $this->jsonReturn($data);
    }
    
    
    /**
     * 积分兑换(获得兑换券)
     * 
     * @author kezhen.yi                  
     * @date 2015年12月20日 上午4:34:48        
     *
     */
     public function exgoods(){
         //检查参数
         $this->checkParam('adgoods_id',true,'int');
         $this->checkParam('user_id',true,'int');
         
         $data = D('Adgoods')->exchangeAd($this->params);
         
         $this->jsonReturn($data);
     }
    
    
    
}