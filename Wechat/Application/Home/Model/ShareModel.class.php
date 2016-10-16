<?php
namespace Home\Model;
/**
 * 分享模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
class ShareModel{
    
    
    /**
     * 获取文章详情
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午4:49:22        
     *
     */
    public function getArticleInfo($article_id){
        $model = M('sharearticle');
        $model->where("article_id=$article_id");
        return $model->find();
    }
    
    
    public function addPoint($article_id){
        $user_id = I('uid',0);
        $clientIP = get_client_ip();
        $model = M('share_log');
        if(!$model->where(array('article_id'=>$article_id,'user_id'=>$user_id,'client_ip'=>$clientIP))->find()){
            $logDate = array(
                'article_id' => $article_id,
                'user_id' => $user_id,
                'create_time' => time(),
                'client_ip' => get_client_ip(),
                'point' => 1,
                'log_info' => '分享文章增加1积分',
            );
            $model->add($logDate);
        }
    }
    
   
    
    
} 