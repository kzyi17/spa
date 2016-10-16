<?php
namespace Index\Controller;

/**
 * 广告管理控制器
 * @author Administrator
 *
 */
class AdgoodsController extends CommonController {
    public function index(){
    	$this->redirect('Adgoods/adList');
    }
    
    /**
     * 广告商品列表
     * 
     * @author kezhen.yi                  
     * @date 2016年2月5日 下午3:08:17        
     *
     */
    public function adList(){
        //定义查询条件
        $cond = array();
        //$cond['total_point'] = array('gt',0);
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Adgoods")->getCount($cond),C("PAGE_SIZE"));
        $list = D("Adgoods")->getList($cond,$Page->firstRow,$Page->listRows,'adgoods_id desc');
        
        //赋值至模板
        $this->assign('AdgoodsList',$list);
        $this->assign('page',$Page->show());
        $this->display('adList');
    }
    
    /**
     * 添加广告商品
     * 
     * @author kezhen.yi                  
     * @date 2016年2月5日 下午4:19:45        
     *
     */
    public function adgoodsAdd(){
        $this->adgoodsEdit();
    }
    
    /**
     * 编辑广告商品
     * 
     * @author kezhen.yi                  
     * @date 2016年2月5日 下午4:20:39        
     *
     */
    public function adgoodsEdit(){
        $id = I('get.id',0,'int');
        
        if($id){
            $info =  D('Adgoods')->getInfo($id);
            if(!$info)
                $this->error('该项目不存在或已经被删除，请重新操作！',U('Adgoods/adList'));
            
            $pagetitle = "编辑广告【ID:".$info['adgoods_id']."】【".$info['adgoods_name']."】";
            
            $this->assign('adinfo', $info);
            
        }else{
            $pagetitle = "添加广告";
        }
        
        //加载商家列表数据
        $this->assign('merchantList', D("Merchant")->getList(null,null,null));
        //加载类目列表数据
        $this->assign('cateList', D("spa")->getCateList());
        
        $this->assign('pagetitle', $pagetitle);
        $this->display("adEdit");
    }
    
    
    /**
     * 保存广告
     * @author Muke
     */
    public function adgoodsSave(){
        $sid = I('adgoods_id',0);
         
        $data = array();
        $data['adgoods_name'] = I('adgoods_name');
        $data['intro'] = I('intro');
        $data['merchant_id'] = I('merchant_id');
        $data['cate_id'] = I('cate_id');
//         $data['order_type'] = I('order_type');
//         $data['market_price'] = I('market_price');
//         $data['sale_price'] = I('sale_price');
        $data['introduce'] = I('introduce');
//         $data['buynotes'] = I('buynotes');
//         $data['create_time'] = time();
//         $data['spa_cover'] = I('photo_id');//项目封面
    
        $photoData = I('_imgList');
         
        //修改项目信息
        if($sid){
            if(!D('Adgoods')->updateOne($sid,$data)) $this->error('广告保存失败，请重新检查');
            //新增项目
        }else{
            $sid = D('Adgoods')->addOne($data);
            if(!$sid) $this->error('广告添加失败，请重新检查');
        }
    
        /* 
        //图片处理
        D('Spa')->delPhoto($sid);
        if(isset($photoData) && $photoData){
            $photoData = explode(",",$photoData);
            if($photoData)
            {
                foreach($photoData as $item)
                {
                    $temp = array("spa_id"=>$sid,"img_id"=>$item);
                    D('Spa')->addPhoto($temp);
                }
            }
        } */
    
        $this->success('广告保存成功');
    }
    
    
    
    ////////////////////////////////
    /**
     * 添加美容项目
     *
     * @author kezhen.yi
     * @date 2016年1月7日 下午5:20:05
     *
     */
    public function spaAdd(){
        $this->spaEdit();
    }
    
    /**
     * 编辑美容项目
     * @author Muke
     */
    public function spaEdit(){
        $sid = I('get.id',0,'int');
    
        if($sid){
            $info =  D('spa')->getInfo($sid);
            if(!$info)
                $this->error('该项目不存在或已经被删除，请重新操作！',U('Merchant/spaList'));
    
            $pagetitle = "编辑项目【ID:".$info['spa_id']."】【".$info['spa_name']."】";
    
            $this->assign('spainfo', $info);
    
            //获取商品相册
            $spa_gallery = D('spa')->getGallery($sid);//print_r($spa_gallery);die;
            $this->assign('spa_gallery', $spa_gallery);
    
        }else{
            $pagetitle = "添加项目";
        }
    
        //加载商家列表数据
        $this->assign('merchantList', D("Merchant")->getList(null,null,null));
        //加载类目列表数据
        $this->assign('cateList', D("spa")->getCateList());
    
        $this->assign('pagetitle', $pagetitle);
        $this->display("spaEdit");
    }
    
    /**
     * 保存项目
     * @author Muke
     */
    public function spaSave(){
        $sid = I('spa_id',0);
         
        $data = array();
        $data['spa_name'] = I('spa_name');
        $data['intro'] = I('intro');
        $data['merchant_id'] = I('merchant_id');
        $data['cate_id'] = I('cate_id');
        $data['order_type'] = I('order_type');
        $data['market_price'] = I('market_price');
        $data['sale_price'] = I('sale_price');
        $data['introduce'] = I('introduce');
        $data['buynotes'] = I('buynotes');
        $data['create_time'] = time();
        $data['spa_cover'] = I('photo_id');//项目封面
    
        $photoData = I('_imgList');
         
        //修改项目信息
        if($sid){
            if(!D('Spa')->updateOne($sid,$data)) $this->error('项目保存失败，请重新检查');
            //新增项目
        }else{
            $sid = D('Spa')->addOne($data);
            if(!$sid) $this->error('项目添加失败，请重新检查');
        }
    
        //图片处理
        D('Spa')->delPhoto($sid);
        if(isset($photoData) && $photoData){
            $photoData = explode(",",$photoData);
            if($photoData)
            {
                foreach($photoData as $item)
                {
                    $temp = array("spa_id"=>$sid,"img_id"=>$item);
                    D('Spa')->addPhoto($temp);
                }
            }
        }
    
        $this->success('项目保存成功');
         
        //         $this->redirect('merchantList');
    }
    
   
   
	
}