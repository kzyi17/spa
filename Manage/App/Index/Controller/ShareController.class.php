<?php
namespace Index\Controller;

/**
 * 文章分享管理控制器
 * @author Administrator
 *
 */
class ShareController extends CommonController {
    public function index(){
    	//$this->redirect('Share/articleList');
        $cond = array();
        
        $current_date=getdate();
        $time = strtotime($current_date['year'].'-'.$current_date['mon'].'-'.$current_date['mday']);
        $model = M('log_article_visit');
        $where['create_time'] = array('gt',$time);
        
        $this->assign('articleCount',D("Share")->getArticleCount($cond));
        $this->assign('hits_member',D("Share")->hitsLog());
        $this->assign('hits_visitor',$model->where($where)->count());
    	$this->display();
    	
    }
    
    public function tongji(){
        //定义查询条件
        $cond = array();
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Share")->getLogArticleCount($cond),C("PAGE_SIZE"));
        $list = D("Share")->getLogArtList($cond,$Page->firstRow,$Page->listRows,'article_id desc');
        
        //赋值至模板
        $this->assign('articleList',$list);
        $this->assign('page',$Page->show());
        
        //print_r($list);die;
        
        $this->display();
    }
    
    public function tongjiDetail(){
        $id = I('get.id',0,'int');
        $info = D("Share")->getArticleInfo($id);
        
        $model = M('log_article_visit');
        $where['article_id'] = $id;
        $Page = new \Think\Page($model->where($where)->count(),C("PAGE_SIZE"));
        $model->limit($Page->firstRow,$Page->listRows);
        $model->order('create_time desc');
        $list = $model->where($where)->select();
        
        $this->assign('info',$info);
        $this->assign('logList',$list);
        $this->assign('page',$Page->show());
        
        $this->display();
    }
    
    /**
     * 兑换管理
     */
    public function exchange(){
        //定义查询条件
        $cond = array();
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Share")->getExchangeCount($cond),C("PAGE_SIZE"));
        $list = D("Share")->getExchangeList($cond,$Page->firstRow,$Page->listRows,'exchange_id desc');
        
        //赋值至模板
        $this->assign('exchangeList',$list);
        $this->assign('page',$Page->show());
        
        //print_r($list);print_r($Page);die;
        
        $this->display();
    }
    
    /**
     * 文章列表
     * 
     * @author kezhen.yi                  
     * @date 2016年4月21日 上午10:32:23        
     *
     */
    public function articleList(){
        //定义查询条件
        $cond = array();
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Share")->getArticleCount($cond),C("PAGE_SIZE"));
        $list = D("Share")->getArticleList($cond,$Page->firstRow,$Page->listRows,'article_id desc');
        
        //赋值至模板
        $this->assign('articleList',$list);
        $this->assign('page',$Page->show());
        
        $this->display('articleList');
    }
    
    /**
     * 添加文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月19日 上午9:56:02        
     *
     */
    public function articleAdd(){
        $this->articleEdit();
    }
    
    /**
     * 编辑文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月19日 上午10:12:44        
     *
     */
    public function articleEdit(){
        $aid = I('get.id',0,'int');
        if($aid){
            $pagetitle = "编辑文章";
            $info = D("Share")->getArticleInfo($aid);
            if(!$info)
                $this->error('该商家不存在或已经被删除，请重新操作！',U('Share/articleList'));
            
            $this->assign('article', $info);
        }else{
            $pagetitle = "添加文章";
        }
        $this->assign('articleCateList', D("Share")->getArticleCateList());
        $this->assign('pagetitle', $pagetitle);
        $this->display("articleEdit");
    }
    
    /**
     * 保存文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月20日 上午12:48:56        
     *
     */
    public function articleSave(){
        if(D("Share")->saveArticle()){
            $this->redirect('articleList');
        }else{
            $this->error('文章保存失败，请重新检查');
        }
        
    }
    
    /**
     * 删除文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月20日 下午3:27:50        
     *
     */
    public function articleDel(){
       
        if(D('Share')->delArticle()){
            $this->redirect('articleList');
        }else{
            $this->error('文章删除失败，请重新检查');
        }
        
        
    }
    
    /**
     * 分类管理
     * 
     * @author kezhen.yi                  
     * @date 2016年4月21日 上午10:32:41        
     *
     */
    public function articleCateList(){
        //定义查询条件
        $cond = array();
    
        //实例化分页类 传入总记录数和每页显示的记录数
        $list = D("Share")->getArticleCateList();
    
        //赋值至模板
        $this->assign('cateList',$list);
    
        $this->display('articleCateList');
    }
    
    /**
     * 添加分类
     *
     * @author kezhen.yi
     * @date 2016年4月19日 上午9:56:02
     *
     */
    public function articleCateAdd(){
        $this->articleCateEdit();
    }
    
    /**
     * 编辑分类
     *
     * @author kezhen.yi
     * @date 2016年4月19日 上午10:12:44
     *
     */
    public function articleCateEdit(){
        $cid = I('get.id',0,'int');
        if($cid){
            $pagetitle = "编辑分类";
            $info = D("Share")->getArticleCateInfo($cid);
            if(!$info)
                $this->error('该分类不存在或已经被删除，请重新操作！',U('Share/articleCateList'));
    
            $this->assign('cate', $info);
        }else{
            $pagetitle = "添加分类";
        }
        $this->assign('pagetitle', $pagetitle);
        $this->display("articleCateEdit");
    }
   
    /**
     * 保存分类
     *
     * @author kezhen.yi
     * @date 2016年4月20日 上午12:48:56
     *
     */
    public function articleCateSave(){
        if(D("Share")->saveArticleCate()){
            $this->redirect('articleCateList');
        }else{
            $this->error('分类保存失败，请重新检查');
        }
    
    }
   
    /**
     * 删除分类
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午3:27:50
     *
     */
    public function articleCateDel(){
         
        if(D('Share')->delArticleCate()){
            $this->redirect('articleCateList');
        }else{
            $this->error('分类删除失败，请重新检查');
        }
    }
    
    /**
     * 积分商城商品列表
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午3:27:50
     *
     */
    public function goodsList(){
        //定义查询条件
        $cond = array();
        //$cond['total_point'] = array('gt',0);
    
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Share")->getGoodsCount($cond),C("PAGE_SIZE"));
        $list = D("Share")->getGoodsList($cond,$Page->firstRow,$Page->listRows,'sharegoods_id desc');
    
        //赋值至模板
        $this->assign('goodsList',$list);
        $this->assign('page',$Page->show());
        $this->display('goodsList');
    }
    
    /**
     * 添加积分商品
     * 
     * @author kezhen.yi                  
     * @date 2016年4月21日 下午2:14:45        
     *
     */
    public function goodsAdd(){
        $this->goodsEdit();
    }
    
    /**
     * 
     * 
     * @author kezhen.yi                  
     * @date 2016年4月21日 下午2:16:20        
     *
     */
    public function goodsEdit(){
        $gid = I('get.id',0,'int');
        if($gid){
            $pagetitle = "编辑积分商品";
            $info = D("Share")->getGoodsInfo($gid);
            if(!$info)
                $this->error('该积分商品不存在或已经被删除，请重新操作！',U('Share/goodsList'));
        
            $this->assign('goods', $info);
        }else{
            $pagetitle = "添加积分商品";
        }
        //加载商家列表数据
        $this->assign('merchantList', D("Merchant")->getList(null,null,null));
        $this->assign('pagetitle', $pagetitle);
        $this->display("goodsEdit");
    }
    
    /**
     * 保存积分商品
     *
     * @author kezhen.yi
     * @date 2016年4月21日 下午2:35:37
     *
     */
    public function goodsSave(){
        if(D("Share")->saveGoods()){
            $this->redirect('goodsList');
        }else{
            $this->error('积分商品保存失败，请重新检查');
        }
    }
    
    /**
     * 删除积分商品
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午3:27:50
     *
     */
    public function goodsDel(){
         
        if(D('Share')->delGoods()){
            $this->redirect('goodsList');
        }else{
            $this->error('积分商品删除失败，请重新检查');
        }
    }
    
    /**
     * 广告管理
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午3:27:50
     *
     */
    public function admanage(){
       /*  $list = D("Share")->getArticleCateList();
        foreach ($list as $k=>$v){
            $poscode_font = serialize(array(
                'type'       =>  'article',
                'pos'        =>  'font',
                'artcate'    =>  $list[$k]['cate_id']
            ));
            $poscode_top = serialize(array(
                'type'       =>  'article',
                'pos'        =>  'top',
                'artcate'    =>  $list[$k]['cate_id']
            ));
            $poscode_mid = serialize(array(
                'type'       =>  'article',
                'pos'        =>  'mid',
                'artcate'    =>  $list[$k]['cate_id']
            ));
            $poscode_bottom = serialize(array(
                'type'       =>  'article',
                'pos'        =>  'bottom',
                'artcate'    =>  $list[$k]['cate_id']
            ));
            
            $saveData1 = array('pos_name'=>$list[$k]['cate_name'].'_文字广告','code'=>$poscode_font);
            $saveData2 = array('pos_name'=>$list[$k]['cate_name'].'_顶部广告','code'=>$poscode_top);
            $saveData3 = array('pos_name'=>$list[$k]['cate_name'].'_中部广告','code'=>$poscode_mid);
            $saveData4 = array('pos_name'=>$list[$k]['cate_name'].'_底部广告','code'=>$poscode_bottom);
            M('ad_pos')->add($saveData1);
            M('ad_pos')->add($saveData2);
            M('ad_pos')->add($saveData3);
            M('ad_pos')->add($saveData4);
        } */
        //定义查询条件
        $cond = array();
        //$cond['total_point'] = array('gt',0);
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Share")->getAdCount($cond),C("PAGE_SIZE"));
        $list = D("Share")->geAdList($cond,$Page->firstRow,$Page->listRows,'ad_id desc');
        
        //赋值至模板
        $this->assign('adList',$list);
        $this->assign('page',$Page->show());
        $this->display("adList");
    }
    
    /**
     * 添加广告
     *
     * @author kezhen.yi
     * @date 2016年4月19日 上午9:56:02
     *
     */
    public function adAdd(){
        $this->adEdit();
    }
    
    /**
     * 编辑广告
     *
     * @author kezhen.yi
     * @date 2016年4月19日 上午10:12:44
     *
     */
    public function adEdit(){
        $aid = I('get.id',0,'int');
        if($aid){
            $pagetitle = "编辑广告";
            $info = D("Share")->getAdInfo($aid);
            if(!$info)
                $this->error('该广告不存在或已经被删除，请重新操作！',U('Share/admanage'));
            $this->assign('ad', $info);
        }else{
            $pagetitle = "添加广告";
        }
        
        $goodsList = D("Share")->getGoodsList($cond);//print_r($goodsList);
        $this->assign('goodsList',$goodsList);
        $this->assign('adposList', D("Share")->getAdPosList());
        $this->assign('pagetitle', $pagetitle);
        $this->display("adEdit");
    }
    
    /**
     * 保存广告
     *
     * @author kezhen.yi
     * @date 2016年4月20日 上午12:48:56
     *
     */
    public function adSave(){
        if(D("Share")->saveAd()){
            $this->redirect('admanage');
        }else{
            $this->error('广告保存失败，请重新检查');
        }
    
    }
    
    /**
     * 删除广告
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午3:27:50
     *
     */
    public function adDel(){
         
        if(D('Share')->delAd()){
            $this->redirect('admanage');
        }else{
            $this->error('广告删除失败，请重新检查');
        }
    
    
    }
    
	
}