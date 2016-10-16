<?php
namespace Index\Controller;

/**
 * 商家管理控制器
 * @author Administrator
 *
 */
class MerchantController extends CommonController {
    public function index(){
    	$this->redirect('Merchant/merchantList');
 		//$this->productList();
    }
    
    /**
     * 商家列表
     * 
     * @author kezhen.yi                  
     * @date 2016年1月7日 下午4:30:07        
     *
     */
    public function merchantList(){
        //定义查询条件
        $cond = array();
        
        if(I("name")) {
            //$search_str['name'] = I("name");
            $cond['merchant_name'] = array("LIKE","%".I("name")."%");
        }
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Merchant")->getCount($cond),C("PAGE_SIZE"));
        $list = D("Merchant")->getList($cond,$Page->firstRow,$Page->listRows,'merchant_id desc');
        
        //赋值至模板
        $this->assign('merchantList',$list);
        $this->assign('page',$Page->show());
        
        $this->display();
    }
    
    /**
     * 添加商家
     * 
     * @author kezhen.yi                  
     * @date 2016年1月7日 下午5:20:05        
     *
     */
    public function merchantAdd(){
        $this->merchantEdit();
    }
    
    /**
     * 编辑商家
     * @author Muke
     */
    public function merchantEdit(){
        $mid = I('get.id',0,'int');
        
        if($mid){
            $pagetitle = "编辑商家";
            $info =  D('merchant')->getInfo($mid);
            if(!$info)
                $this->error('该商家不存在或已经被删除，请重新操作！',U('Merchant/merchantList'));
            $this->assign('merchant', $info);
            //获取商家相册
            $merchant_gallery = D('merchant')->getGallery($mid);//print_r($merchant_gallery);die;
            $this->assign('merchant_gallery', $merchant_gallery);
        }else{
            $pagetitle = "添加商家";
        }
        
        $this->assign('pagetitle', $pagetitle);
        $this->display("merchantEdit");
    }
    
    //批量导入数据
    public function batchup(){
         //定义查询条件
        $cond = array();
        
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Merchant")->getCount($cond),100);
        $list = D("Merchant")->getList($cond,$Page->firstRow,$Page->listRows,'merchant_id asc');
        
        //赋值至模板
//         $this->assign('merchantList',$list);
//         $this->assign('page',$Page->show());
//         $list = D("Merchant")->getList($cond,102,200,'merchant_id asc');
        
        
        
//         print_r($list);
        
        $model = M('merchant');
        
        foreach ($list as $v){
            $data = array(
                '_name' => $v['merchant_name'],
                '_address' => $v['address'],
                'merchant_id'=>$v['merchant_id'],
                'contact'=>$v['phone']
            );
            $mapData = D('MapApi')->createOne($data);
            $mapData = json_decode($mapData,true);
            
            
            
            if($mapData['status']==1){
                $map_id = $mapData['_id'];
                $model->where(array("merchant_id"=>$v['merchant_id']))->save(array('map_id'=>$map_id));//
            
                echo "$map_id<br/>";
                //return $mid;
            }else{
                $model->where(array("merchant_id"=>$v['merchant_id']))->delete();
                
                echo $v['merchant_id'].">".$v['merchant_name'].":保存失败<br/>";
            }
            
            
        }
        
        
//         $data = array(
//             '_name' => $data['merchant_name'],
//             '_address' => $data['address'],
//             'merchant_id'=>$mid
//         );
//         $mapData = D('MapApi')->createOne($data);
//         $mapData = json_decode($mapData,true);
        
        
        
        
        
        echo $Page->show();
        
        
//         $this->display();
    }
    
    /**
     * 保存商家
     * @author Muke
     */
    public function merchantSave(){
        $mid = I('merchant_id',0);
         
        //print_r(I('post.'));die;
        
        $data = array();
        $data['merchant_name'] = I('merchant_name');
        $data['address'] = I('address');
        $data['phone'] = I('phone');
        $data['pos'] = I('pos');
        $data['intro'] = I('intro','',false);
        
        $data['merchant_cover'] = I('photo_id');//商家封面
        $photoData = I('_imgList');
         
        
        //修改记录
        if($mid){
            if(!D('Merchant')->updateOne($mid,$data)){
                $this->error('商家保存失败，请重新检查');
            }
        //新增记录
        }else{
            $mid = D('Merchant')->addOne($data);
            if(!$mid) $this->error('商家保存失败，请重新检查');
        }
        
        
        //图片处理
        D('Merchant')->delPhoto($mid);
        if(isset($photoData) && $photoData){
            $photoData = explode(",",$photoData);
            if($photoData)
            {
                foreach($photoData as $item)
                {
                    $temp = array("merchant_id"=>$mid,"img_id"=>$item);
                    D('Merchant')->addPhoto($temp);
                }
            }
        }
         
        $this->redirect('merchantList');
        
        
    }
    
    /**
     * 删除商家
     * 
     * @author kezhen.yi                  
     * @date 2016年3月4日 下午2:53:14        
     *
     */
    public function merchantDel(){
        $id = I("id",0,'int');
        D('Merchant')->delone($id);
        
        $this->redirect('merchantList');
    }
    
    
    /**
     * 美容项目列表
     *
     * @author kezhen.yi
     * @date 2016年1月7日 下午4:30:07
     *
     */
    public function spaList(){
        //定义查询条件
        $cond = array();
        
        if(I("name")) {
            //$search_str['name'] = I("name");
            $cond['spa_name'] = array("LIKE","%".I("name")."%");
        }
    
        //实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page(D("Spa")->getCount($cond),C("PAGE_SIZE"));
        $list = D("spa")->getList($cond,$Page->firstRow,$Page->listRows,'spa_id desc');
    
        //赋值至模板
        $this->assign('spaList',$list);
        $this->assign('page',$Page->show());
    
        $this->display();
    }
    
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
        $data['introduce'] = I('introduce','',false);
        $data['buynotes'] = I('buynotes','',false);
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

    /**
     * 删除项目
     *
     * @author kezhen.yi
     * @date 2016年3月4日 下午2:53:14
     *
     */
    public function spaDel(){
        $id = I("id",0,'int');
        D('Spa')->delone($id);
    
        $this->redirect('spaList');
    }


	/**
	* 文件上传
	**/
	public function uploadFile(){
		if(IS_POST){

			echo json_encode(D("Product")->uploadFile());
		}else{

		}

	}
	
}