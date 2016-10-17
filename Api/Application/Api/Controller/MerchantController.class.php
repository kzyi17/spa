<?php
namespace Api\Controller;
/**
 * 美容商家API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
class MerchantController extends CommonController {
    
    
    /**
     * 获取商家列表
     * 
     * @author kezhen.yi                  
     * @date 2015年12月16日 下午6:55:47        
     *
     */
    public function getlist(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        
        //商家搜索
        if(isset($this->params['keywords'])){
            $result = D('MapApi')->getLocal($this->params);
            $data = array();
            foreach ($result as $k=>$v){
                $tmpinfo = D('Merchant')->getInfo($v['merchant_id']);
                if($tmpinfo){
                    $data[$k] = $tmpinfo;
                    $data[$k]['distance'] = $v['_distance'];
                }
            }
        }else{
            //currentpos:当前定位
            //radius:范围
            //page:分页
            //limit:分页大小
            //city:城市
            $this->params['radius'] = 50000;
            
            $result = D('MapApi')->getAround($this->params);
            $data = array();
            foreach ($result as $k=>$v){
                $tmpinfo = D('Merchant')->getInfo($v['merchant_id']);
                if($tmpinfo){
                    $data[$k] = $tmpinfo;
                    $data[$k]['distance'] = $v['_distance'];
                }
            
            }
        }
        
        $this->success($data);
    }
    
    /**
     * 商家搜索
     * 
     * @author kezhen.yi                  
     * @date 2016年3月3日 下午3:45:39        
     *
     */
    public function search(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        $data = D('MapApi')->getLocal($this->params);
        
        $this->success($data);
    } 
    
    
    /**
     * 获取商家详情
     * --返回商家详情信息
     * 
     * @author kezhen.yi                  
     * @date 2015年12月16日 下午6:58:43        
     *
     */
    public function getinfo(){
        //检查参数
        $this->checkParam('merchant_id',true,'int');
        
        $merchant_id = $this->params['merchant_id'];
        
        $data = D('Merchant')->getInfo($merchant_id);
        
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
        
    }
    
    /**
     * 获取商家相册
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午9:47:39        
     *
     */
    public function getgallery(){
        //检查参数
        $this->checkParam('merchant_id',true,'int');
        
        $merchant_id = $this->params['merchant_id'];
        
        $data = D('Merchant')->getGallery($merchant_id);
        
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
    }
    
    /**
     * 获取商家美容项目
     * 
     * @author kezhen.yi                  
     * @date 2015年12月27日 下午12:09:20        
     *
     */
    public function getspa(){
        //检查参数
        $this->checkParam('merchant_id',true,'int');
        $merchant_id = $this->params['merchant_id'];
        
        $data = D('Merchant')->getSpaList($merchant_id);
        
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
        
    }
   
    
}