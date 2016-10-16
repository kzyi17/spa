<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->register();
    }
    
    /**
     * 注册
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 上午10:47:17        
     *
     */
    public function register(){
        $user_id = I('userid',0);
        $this->assign('user_id',$user_id);
        $this->display('./register');
    }
    
    /**
     * 下载
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 上午10:47:32        
     *
     */
    public function download(){
        $this->display('./download');
    }
    
    /**
     * 文章分享详情页
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 上午10:47:48        
     *
     */
    public function article(){
        $article_id = I('get.id',0);
        $user_id = I('uid',0);
        $art = D("Share")->getArticleInfo($article_id);
        $this->assign('art',$art);
        //增加推荐者积分
        D("Share")->addPoint($article_id);
        
        $this->assign('uid',$user_id);
        $this->display('./article');
    }
    
    
}