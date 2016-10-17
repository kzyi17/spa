<?php
/**
 * 文章分享API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Api\Controller;
class ShareController extends CommonController {
    
    
    
    /**
     * 获取系统文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午5:32:08        
     *
     */
    public function guidelist(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
    
        $data = D('Share')->getGuideList($this->params);
    
        $this->jsonReturn($data);
    }
    
    
    /**
     * 获取文章分类
     */
    public function getArticleCate(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        $data = D('Share')->getArticleCate($this->params);
        
        $this->jsonReturn($data);
    }
    
    /**
     * 获取文章分类及其文章列表
     */
    public function getArticleCateAndList(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        $data = array();
        
        $data = D('Share')->getArticleCate($this->params);
        
        foreach ($data as $k=>$v){
            $param = array(
                'where' => array('cate_id'=>$v['cate_id']),
                'page' => $this->params['page'],
                'limit' => $this->params['limit']
            );
            
            $data[$k]['article'] = D('Share')->getArticleList($param);
        }
        
        $this->success($data);
    }
    
    /**
     * 获取文章详情
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午4:54:41        
     *
     */
    public function getArticleInfo(){
        //检查参数
        $this->checkParam('article_id',true);
        
        $data = D('share')->getArticleInfo($this->params);
        
        $this->jsonReturn($data);
    }
    
    /**
     * 获取文章列表
     * 
     * @author kezhen.yi                  
     * @date 2016年4月9日 下午5:07:05        
     *
     */
    public function getArticleList(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        if($this->params['cate_id']){
            $this->params['where'] = array('cate_id'=>$this->params['cate_id']);
        }
        
        $data = D('share')->getArticleList($this->params); 
        
        $this->jsonReturn($data);
    }
    
    /**
     * 获取推荐文章列表
     *
     * @author kezhen.yi
     * @date 2016年4月9日 下午5:07:05
     *
     */
    public function getRmdArticleList(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        $this->params['where'] = array('is_recommend'=>1);
    
        $data = D('share')->getArticleList($this->params);
    
        $this->jsonReturn($data);
    }
    
    /**
     * 获取用户信息
     *
     * @author kezhen.yi
     * @date 2016年4月12日 上午6:12:53
     *
     */
    public function getUserInfo(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('share')->getUserInfo($this->params);
    
        $this->jsonReturn($data);
    
    }
    
    /**
     * 浏览获取积分
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午6:12:53        
     *
     */
    public function getPoint(){
        //检查参数
        $this->checkParam('article_id',true);
        $this->checkParam('user_id',true);
        
        $data = D('share')->getPoint($this->params);
        
        $this->jsonReturn($data);
        
    }
    
    /**
     * 获取收益明细
     *
     * @author kezhen.yi
     * @date 2016年4月12日 上午7:53:20
     *
     */
    public function getShareLog(){
        //检查参数
        $this->checkParam('user_id',true);
        
        $data = D('share')->getShareLog($this->params);
        
        $this->jsonReturn($data);
    }
    
    /**
     * 获取广告
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午9:02:56        
     *
     */
    public function getAd(){
        //检查参数
        $this->checkParam('article_id',true);
        $this->checkParam('pos',true);
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
        
        $data = D('share')->getAd($this->params);
        
        $this->jsonReturn($data);
        
    }
    
    /**
     * 获取积分商城商品列表
     *
     * @author kezhen.yi
     * @date 2016年2月3日 上午1:09:38
     *
     */
    public function getShareGoodsList(){
        //检查参数
        $this->checkParam('page',false,'int',1);
        $this->checkParam('limit',false,'int',10);
            
        //查询获取数据
        $data = D('share')->getGoodsList($this->params);
    
        $this->jsonReturn($data);
    
    }
    
    /**
     * 获取积分商城商品详情
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午12:06:50
     *
     */
    public function getShareGoodsInfo(){
        //检查参数
        $this->checkParam('goods_id',true,'int');
        
        $data = D('share')->getGoodsInfo($this->params);
        
        $this->jsonReturn($data);
    
    }
    
    /**
     * 获取商品兑换信息
     *  --商品详情
     *  --用户收货地址
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午4:34:48
     *
     */
    public function getExchangeInfo(){
        //检查参数
        $this->checkParam('goods_id',true,'int');
        $this->checkParam('user_id',true,'int');
         
        $data = D('share')->getExchangeInfo($this->params);
         
        $this->jsonReturn($data);
    }
    
    
    
    /**
     * 兑换商品
     *
     * @author kezhen.yi
     * @date 2015年12月20日 上午4:34:48
     *
     */
    public function exgoods(){
        //检查参数
        $this->checkParam('goods_id',true,'int');
        $this->checkParam('user_id',true,'int');
        $this->checkParam('address_id',true,'int');
        
        $data = D('share')->exchangeGoods($this->params);
         
        $this->jsonReturn($data);
    }
    
    
}