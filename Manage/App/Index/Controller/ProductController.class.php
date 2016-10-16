<?php
namespace Index\Controller;

/**
 * 商品管理控制器
 * @author Administrator
 *
 */
class ProductController extends CommonController {
    public function index(){
    	$this->redirect('product/productList');
 		//$this->productList();
    }
    //商品分类管理
    public function cateList(){
		if(IS_POST){
			//获取操作
			$act = I('act');
			$data = I('data');
			$data['class_name'] = addslashes($data['class_name']);

			if ($act == "add") { //添加分类

				echo json_encode(D("ProductClass")->add($data));
			} else if ($act == "edit") { //修改分类
				echo json_encode(D("ProductClass")->edit($data));
			} else if ($act == "del") { //删除分类
// 				echo json_encode(array('status' => 0, 'info' => json_encode($data)));
				echo json_encode(D("ProductClass")->del($data['class_id']));
			}
		}else{
			$this->assign("list", D("ProductClass")->getList());
			//print_r(D("ProductClass")->getList());
			$this->display("cateList");
		}

    }
     //商品分类推荐
    public function cateRecommend(){
		$class_id = I('get.class_id');
		$data['recommend'] = I('get.status');
		$status=D('ProductClass')->where("class_id='".$class_id."'")->save($data);
		echo json_encode(array('status'=>$status));
    }
    /**
     * 添加商品分类
     * @author Muke
     */
    public function cateAdd(){
    	$this->cateEdit();
    }
    
    /**
     * 编辑商品分类
     * @author Muke
     */
    public function cateEdit(){
    	$id = I('get.id',0,'int');
    	//编辑属性分组
    	if($id){
    		$pagetitle = "编辑商品分类";
    		$cate =  D('productClass')->loadCate($id);
    		if(!$cate)
    			$this->error('该分类不存在或已经被删除，请重新操作！',U('product/catelist'));
    		$this->assign('cate', $cate);
    		
    	}else{
    		$pagetitle = "添加商品分类";
    	}
    	//获取分类
    	$this->assign('cateList', D("ProductClass")->getList());
    	$this->assign('pagetitle', $pagetitle);
    	$this->display("cateEdit");
    }
    
    /**
     * 保存商品分类
     * @author Muke
     */
    public function cateSave(){
    	$cid = I('class_id',0);
    	
    	$data = array();
    	$data['class_name'] = I('class_name');
    	$data['pid'] = I('pid',0);
    	$data['keywords'] = I('keywords');
    	$data['description'] = I('description');
    	$data['title'] = I('title');
    	$data['sort'] = I('sort',99);
    	$data['imgurl'] = I('icon');
    	
    	if($cid){
		//修改分类
    		if(!D('ProductClass')->updateCate($cid,$data)){
    			$this->error('分类保存失败，请重新检查');
    		}
    	}else{
    	//新增分类	
    		if(!D('ProductClass')->addCate($data)){
    			$this->error('分类保存失败，请重新检查');
    		}
    	}
    	
    	$this->redirect('cateList');
    }

    //商品属性分组(模型)列表
    public function attribute(){
    	$list = D("AttributeCate")->get_list();
    	$this->assign("list",$list);
    	$this->display();
    }

    //商品属性分组(模型)编辑
    public function attribute_edit(){
    	if(IS_POST){
    		$cate = I('post.cate');
    		$attrs = I('post.attr');

    		$AttributeCate = D("AttributeCate");
    		$Attribute = D("Attribute");
    		$AttributeCate->cate_name = sub_str($cate['cate_name'],60);
    		$AttributeCate->attr_group = sub_str($cate['attr_group'],255);

    		//编辑保存
			if($cate['cate_id']){

				//保存属性分组及属性
				if($AttributeCate->update_cate($cate,$attrs)){
					//保存成功后跳转到列表
					$this->success('操作完成','attribute',3);
				}else{
					$this->error('属性修改失败',U('Product/attribute_edit?id='.$cate['cate_id']),5);
				}

			//添加保存
			}else{

				//保存分组信息
				$cate_id = $AttributeCate->insert();
				//保存属性信息
				foreach ($attrs['attr_id'] as $k=>$v)
				{
					$Attribute->attr_name = $attrs['attr_name'][$k];
					$Attribute->cat_id = $cate_id;
					$Attribute->attr_type = $attrs['attr_type'][$k];
					$Attribute->attr_values = $attrs['attr_values'][$k];
					$Attribute->is_search = $attrs['is_search'][$k];
					$Attribute->is_linked = $attrs['is_linked'][$k];
					$Attribute->attr_group = '';
					$Attribute->sort = 99;
					$Attribute->insert();
				}
				//保存成功后跳转到列表
				$this->redirect("Product/attribute");

			}
    	}else{
    		$id = I('get.id',0,'int');

    		//编辑属性分组
    		if($id){
    			$pagetitle = "编辑属性类型";
    			$AttributeCate = D("AttributeCate");
    			$Attribute = D("Attribute");
				$this->assign("cate",$AttributeCate->get_cate($id));//获取模型信息
				$this->assign("attributes",$Attribute->get_lists_by_cate($id));//获取属性
    		//增加属性分组
    		}else{
    			$pagetitle = "添加属性类型";
    		}
    		$this->assign("pagetitle",$pagetitle);
    		$this->display();
    	}

    }

    //删除商品属性分组
    public function attribute_del(){
		$id = I('id');
		$AttributeCate = D("AttributeCate");

		if($AttributeCate->delete_cate($id)){
			echo json_encode(array('status' => 1, 'info' => "属性分组删除成功",'url' => U('Product/attribute')));
		}else{
			echo json_encode(array('status' => 0, 'info' => "属性分组删除失败"));
		}
    }

    /**
     * 商品规格管理
     */
    public function specList(){

        $Spec = D("ProductSpec")->specList();
        $this->assign("page",   $Spec['showPage']);
        $this->assign("list",   $Spec['list']);
    	$this->display();
    }
    /**
     * 添加编辑规格
     */
    public function specEdit(){

    	$M = D('ProductSpec');
    	if (IS_POST) {
    		echo json_encode($M->specEdit());
    	} else {
    		$dataRow = null;
    		$spec_id = I("get.id",'','intval');
    		$dataRow = $M->where("`status` = 1 and spec_id =" . $spec_id)->find();
    		$dataRow['spec_value'] = json_decode($dataRow['spec_value']);

	    	if(!$dataRow)
	    	{
	    		$dataRow = array(
	    				'spec_id'   => null,
	    				'spec_name' => null,
	    				'spec_type' => null,
	    				'spec_value'=> null,
	    				'note'      => null,
	    		);
	    	}
    		$this->assign("data", $dataRow);
    		$this->display();
    	}

    }

	/**
	 * 商品规格批量删除
	 */
	public function specDelAll(){
		if(IS_POST){
			$ProductSpec = D('ProductSpec');
			$ids = array();
			//$datas   = I('data');
			$ids = I('post.del_id');
			for($i=0;$i<count($ids);$i++){
				$ProductSpec->specDel($ids[$i]);
			}
			$this->success('删除成功',u('product/specList'));
		}
	}

	/**
	 * 商品规格删除
	 */

	public function specDel(){
		$spec_id = I('id');
		$ProductSpec = D('ProductSpec');
		if($ProductSpec->specDel($spec_id)){
			echo json_encode(array('status' => 1, 'info' => "规格删除成功",'url' => U('Product/specList'),'id' =>$spec_id ));
		}else{
			echo json_encode(array('status' => 0, 'info' => "规格删除失败"));
		}
	}

	/**
	 * 回收站 商品规格列表
	 */
	public function specRecycleList(){
		$Spec = D("ProductSpec")->specRecycleList();
        $this->assign("page",   $Spec['showPage']);
        $this->assign("list",   $Spec['list']);
    	$this->display('specList');
	}
	/**
	 * 回收站 商品规格恢复
	 */
	public function specRecycleRegain(){
		$id = I('id');
		$Spec = D("ProductSpec");

		if($Spec->regain_spec($id)){
			echo json_encode(array('status' => 1, 'info' => "规格恢复删除成功",'url' => U('Product/specRecycleList')));
		}else{
			echo json_encode(array('status' => 0, 'info' => "规格恢复删除失败"));
		}
	}
	
	/**
	 * 回收站 商品规格彻底删除
	 */
	public function specRecycleDel(){
		$id = I('id');
		$Spec = D("ProductSpec");

		if($Spec->specRecycleDel($id)){
			echo json_encode(array('status' => 1, 'info' => "规格彻底删除成功",'url' => U('Product/specRecycleList')));
		}else{
			echo json_encode(array('status' => 0, 'info' => "规格彻底删除失败"));
		}
	}
	/**
	 * 回收站 商品规格批量删除
	 */
	public function specRecycleDelAll(){
		if(IS_POST){
			$ProductSpec = D('ProductSpec');
			$ids = array();
			//$datas   = I('data');
			$ids = I('post.del_id');
			for($i=0;$i<count($ids);$i++){
				$ProductSpec->specRecycleDel($ids[$i]);
			}
			$this->success('删除成功',u('product/specRecycleList'));
		}
	}
    /**
     * 商品列表
     */
    public function productList(){

    	$search = I("post.");
    	$cond = array();
    	//$cond['product.is_del'] = array('NEQ',1);
    	
    	$search_str = array();
    	if(I("category_id")>0) {
    		$search_str['category_id'] = I("category_id");
    		$cate_ids = M('product_class_extend')->where(array('class_id'=>$search_str['category_id']))->getField('goods_id',true);
//     		if($cate_ids){
    			$cond['product.product_id'] = array('in',$cate_ids);
//     		}
    	}
    	
    	if(I("is_del")!="") {
    		$search_str['is_del'] = I("is_del");
    		$cond['product.is_del'] = $search_str['is_del'];
    	}else{
    		$cond['product.is_del'] = array('NEQ',1);
    	}
    	
    	
    	if(I("name")) {
    		$search_str['name'] = I("name");
    		$cond['product.name'] = array("LIKE","%".$search['name']."%");
    	}
    	if(I("store_nums")){
    		$search_str['store_nums'] = I("store_nums");
    		switch($search_str['store_nums'])
    		{
    			case 1:
    		
    				$cond['product.store_nums'] = array('LT',1);
    				break;
    		
    			case 10:
    		
    				$cond['product.store_nums'] = array(array('EGT',1),array('ELT',10));
    				break;
    		
    			case 100:
    		
    				$cond['product.store_nums'] = array(array('EGT',10),array('ELT',100));
    				break;
    		
    			case 101:
    		
    				$cond['product.store_nums'] = array('EGT',100);
    				break;
    		}
    	}
    	
    
//     	print_r($cond);
//     	die;
    	
    	
    	//搜索条件处理
//     	if($search){
//     		if($search['category_id']>0){
//     			$cate_ids = M('product_class_extend')->where(array('class_id'=>$search['category_id']))->getField('goods_id',true);
//     			if($cate_ids){
//     				$cond['product_id'] = array('in',$cate_ids);
//     			}
//     		}
    		
//     		if($search['is_del']==""){
//     			$cond['is_del'] = array('NEQ',1);
//     		}else{
//     			$cond['is_del'] = $search['is_del'];
//     		}
// 			if($search['name']!=''){
// 				$cond['name'] = array("LIKE","%".$search['name']."%");
// 			}
//     		switch($search['store_nums'])
//     		{
//     			case 1:

//     				$cond['store_nums'] = array('LT',1);
//     				break;

//     			case 10:

//     				$cond['store_nums'] = array(array('EGT',1),array('ELT',10));
//     				break;

//     			case 100:

//     				$cond['store_nums'] = array(array('EGT',10),array('ELT',100));
//     				break;

//     			case 101:

//     				$cond['store_nums'] = array('EGT',100);
//     				break;
//     		}

//     	}
//     	else{
//     		$cond['product.is_del'] = array('NEQ',1);
//     	}
    	
//     	$search_str = array('search_str'=>'aa');
    	
    	
    	//实例化分页类 传入总记录数和每页显示的记录数
    	$Page = new \Think\Page(D("Product")->goodsCount($cond),C("PAGE_SIZE"),$search_str);
//     	$list = D("Product")->goodsList($cond,$Page->firstRow,$Page->listRows);
    	$list = array();
    	foreach (D("Product")->goodsList($cond,$Page->firstRow,$Page->listRows,'product_id desc') as $k=>$v){
    		$v['cate_extend'] = D("Product")->CateExtendById($v['product_id'],'class_name');
    		$list[] = $v;
    	}
//     	foreach($cond as $key=>$val) {    
//     		$Page->parameter   .=   "$key=".urlencode('aa').'&';
//     	}
    	
//     	if($search){
//     	//$Page->parameter = '&aa=aaa&';
    	
// 	    	print_r($Page->show());
// 	    	die;
    	
//     	}
    	//获取分类
    	$this->assign('catList', D("ProductClass")->getList());
    	$this->assign('page',$Page->show());
    	$this->assign('productList',$list);
    	$this->assign('search',$search_str);
    	$this->display("productList");
    }
	public function productRecycle(){
		
	}
    /**
     * 添加商品
     */
    public function productAdd(){
    	$this->productEdit();
    }

    /**
     * 编辑商品
     */
    public function productEdit(){
    	$id = I('get.id',0,'int');
    	
    	//编辑属性分组
    	if($id){
    		$pagetitle = "编辑商品";
    		//获取商品
    		$goods_info =  D("Product")->loadGoods($id);
    		
    		if($goods_info){
    			//获取商品分销商价格
    			$groupPriceDB = M('group_price');
    			$goodsPrice   = $groupPriceDB->where(array('goods_id'=>$id,'product_id'=>NUll))->select();
    			
    			$temp = array();
    			foreach($goodsPrice as $key => $val)
    			{
    				$temp[$val['group_id']] = $val['price'];
    			}
    			$goods_info['groupPrice'] = $temp ? json_encode($temp) : '';
    			
    			
    			//获取商品相册
    			$goods_photo = M('Product_photo_relation')->join('product_photo on product_photo.photo_id=product_photo_relation.photo_id')->where(array('goods_id'=>$goods_info['product_id']))->select();
    			
    			
    			//获取商品分类
    			$cate_extend = M('Product_class_extend')->field('class_id')->where(array('goods_id'=>$goods_info['product_id']))->select();
    			$goods_cate = array();
    			if($cate_extend){
    				foreach ($cate_extend as $v){
    					$goods_cate[]=$v['class_id'];
    				}
    			}
    			
    			//获取货品
    			$product_info = M('product_stocks')->where(array('goods_id'=>$goods_info['product_id']))->select();
    			$products = array();
    			if($product_info)
    			{
    				//获取货品会员价格
    				foreach($product_info as $k => $rs)
    				{
    					$temp = array();
    					$productPrice = $groupPriceDB->where(array('product_id'=>$rs['product_id']))->select();//$groupPriceDB->query('product_id = '.$rs['id']);
    					foreach($productPrice as $key => $val)
    					{
    						$temp[$val['distribution_level_id']] = $val['price'];
    					}
    					$product_info[$k]['groupPrice'] = $temp ? json_encode($temp) : '';
    					
    				}
    				$products = $product_info;
    			}
    			
    			//获取属性
    			$condition['product_id'] = $goods_info['product_id'];
    			$condition['attr_id'] = array('neq',0);
    			$goods_attr_info = M('product_attr')->where($condition)->select();
    			if($goods_attr_info){
    				foreach ($goods_attr_info as $v)
    				{
    					$goods_attr[$v['attr_id']] = $v['attr_value'];
    				}
    			}
    			
    			//获取关联商品
    			$link_goods = $this->get_link_goods($goods_info['product_id']);
    			
//     			print_r($products);
//     			die;
    			
    			$goods_info['promote_start_date'] = date('Y-m-d',$goods_info['promote_start_date']);
				$goods_info['promote_end_date'] = date('Y-m-d',$goods_info['promote_end_date']);
    			$this->assign('goods_info', $goods_info);
    			$this->assign('goods_cate', $goods_cate);
    			$this->assign('products', $products);
    			$this->assign('goods_attr', $goods_attr);
    			$this->assign('goods_photo', $goods_photo);
    			$this->assign('link_goods', $link_goods);
    			
    		}else{
    			$this->error('没有找到相关商品！',U('productList'));
    		}
    		
    	}else{
    		$pagetitle = "添加商品";
    	}
    	
    	$this->assign('pagetitle', $pagetitle);

    	//获取分类
    	$this->assign('catList', D("ProductClass")->getList());

		//获取品牌
    	$brand = D('Brand')->brandList();
    	$this->assign('brandList', $brand['list']);

    	//获取商品模型
    	$this->assign('models', D("AttributeCate")->get_list());

    	$this->assign('form', null);

    	$this->display("productEdit");
    }


    /**
     * 保存商品
     */
    public function productSave(){

    	//检查表单提交状态
    	if(!$_POST)
    	{
    		$this->error('请确认表单提交正确');
    	}
    	
    	
    	$id = I('post.product_id',0,'int');
    	$_postfrom = I('post.');
    	$postData = array();
    	$nowDataTime = date('Y-m-d H:i:s');


    	//初始化商品数据
    	unset($_postfrom['product_id']);
    	unset($_postfrom['cat_id1']);
    	unset($_postfrom['keyword1']);
    	unset($_postfrom['source_select1']);
    	unset($_postfrom['is_single']);
    	unset($_postfrom['target_select1']);
    	
    	
    	foreach($_postfrom as $key => $val)
    	{
    		$postData[$key] = $val;

    		//数据过滤分组(分商品信息和商品属性)
    		if(strpos($key,'attr_id_') !== false)
    		{
    			//商品属性
    			$goodsAttrData[ltrim($key,'attr_id_')] = $val;
    		}
    		else if($key[0] != '_')
    		{
    			//商品信息
    			$goodsUpdateData[$key] = $val;
    		}
    	}
    	

    	//更改上下架时间
    	if(isset($goodsUpdateData['is_del']))
    	{
    		$goodsUpdateData['is_del'] == 2 ? ($goodsUpdateData['down_time'] = $nowDataTime) : ($goodsUpdateData['up_time'] = $nowDataTime);
    	}

    	//是否存在货品
    	$postData['_spec_array'] = $_POST['_spec_array'];//I方法将JSON数据过滤，此处用回原始数据
    	$goodsUpdateData['spec_array'] = '';
    	if(isset($postData['_spec_array']))
    	{
     		//生成goods中的spec_array字段数据
    		$goods_spec_array = array();
    		foreach($postData['_spec_array'] as $key => $val)
    		{
    			foreach($val as $v)
    			{
    				$tempSpec = json_decode($v,true);//JSON::decode($v);
    				if(!isset($goods_spec_array[$tempSpec['id']]))
    				{
    					$goods_spec_array[$tempSpec['id']] = array('id' => $tempSpec['id'],'name' => $tempSpec['name'],'type' => $tempSpec['type'],'value' => array());
    				}
    				$goods_spec_array[$tempSpec['id']]['value'][] = $tempSpec['value'];
    			}
    		}
    		foreach($goods_spec_array as $key => $val)
    		{
    			$val['value'] = array_unique($val['value']);
    			$goods_spec_array[$key]['value'] = join(',',$val['value']);
    		}
    		$goodsUpdateData['spec_array'] = json_encode($goods_spec_array);//JSON::encode($goods_spec_array);
    	}
    	
    	$goodsUpdateData['product_sn']     = preg_replace("/(?:\-\d*)$/","",current($postData['_goods_no']));
    	$goodsUpdateData['store_nums']   = array_sum($postData['_store_nums']);
    	$goodsUpdateData['market_price'] = isset($postData['_market_price']) ? current($postData['_market_price']) : 0;
    	$goodsUpdateData['sell_price']   = isset($postData['_sell_price'])   ? current($postData['_sell_price'])   : 0;
    	$goodsUpdateData['cost_price']   = isset($postData['_cost_price'])   ? current($postData['_cost_price'])   : 0;
    	$goodsUpdateData['weight']       = isset($postData['_weight'])       ? current($postData['_weight'])       : 0;
		$goodsUpdateData['is_promote']       = I('is_promote')       ? I('is_promote')       : 0;
		$goodsUpdateData['promote_end_date']       = (I('promote_end_date'))       ? strtotime(I('promote_end_date'))       : 0;
		$goodsUpdateData['promote_start_date']       = (I('promote_start_date'))       ? strtotime(I('promote_start_date'))       : 0;
    	$goodsUpdateData['content'] = I('post.content','','');
		$goodsUpdateData['promote_price'] = I('post.promote_price','','');
		$goodsUpdateData['supplier_url'] = I('post.supplier_url','','');
		$goodsUpdateData['supplier_description'] = I('post.supplier_description','','');
		$goodsUpdateData['supplier_name'] = I('post.supplier_name','','');
    	//[保存/修改]商品信息
    	$goodsDB = D("Product");
    	if($id)
    	{
    		$goodsUpdateData['last_time'] = $nowDataTime;
    		$goodsDB->updateGoods($id,$goodsUpdateData);
    	}
    	else
    	{//新增保存
    		$goodsUpdateData['create_time'] = $goodsUpdateData['last_time'] = $nowDataTime;
    		$id = $goodsDB->addGoods($goodsUpdateData);
    		$mod_link_goods = M('link_goods');
    		//$mod_link_goods->where(array('goods_id'=>0,'link_goods_id'=>0,'_logic'=>'or'))->delete();
    		//$data_link_goods = array();
    		
    		$mod_link_goods->where(array('goods_id'=>0))->save(array('goods_id'=>$id));
    		$mod_link_goods->where(array('link_goods_id'=>0))->save(array('link_goods_id'=>$id));
    		
    		//$mod_link_goods->add($data_link_goods);
    		
    	}
    	
    	
    	
    	
    	//保存属性和规格
    	//处理商品属性和规格
    	$goodsDB->delGoodsAttrByPid($id);
    	if(isset($goodsAttrData) && $goodsAttrData)
    	{
    		foreach($goodsAttrData as $key => $val)
    		{
    			$attrData = array(
    					'product_id' => $id,
    					'model_id' => $goodsUpdateData['model_id'],
    					'attr_id' => $key,
    					'attr_value' => is_array($val) ? join(',',$val) : $val
    			);
    			$goodsDB->addGoodsAttr($attrData);
    		}
    	}
    	if(isset($goods_spec_array) && $goods_spec_array)
    	{
    		foreach($goods_spec_array as $key => $val)
    		{
    			$temp = explode(',',$val['value']);
    			foreach($temp as $v)
    			{
    				$attrData = array(
    						'product_id' => $id,
    						'model_id' => $goodsUpdateData['model_id'],
    						'spec_id' => $val['id'],
    						'spec_value' => $v
    				);
    				$goodsDB->addGoodsAttr($attrData);
    			}
    		}
    	}
    	
    	//是否存在货品
    	$goodsDB->delProductByPid($id);
    	if(isset($postData['_spec_array']))
    	{
    		$productIdArray = array();					//处理会员组价格时可用

    		//创建货品信息
    		foreach($postData['_goods_no'] as $key => $rs)
    		{
    			$productsData = array(
    					'goods_id' => $id,
    					'product_sn' => $postData['_goods_no'][$key],
    					'store_nums' => $postData['_store_nums'][$key],
    					'market_price' => $postData['_market_price'][$key],
    					'sell_price' => $postData['_sell_price'][$key],
    					'cost_price' => $postData['_cost_price'][$key],
    					'weight' => $postData['_weight'][$key],
    					'spec_array' => "[".join(',',$postData['_spec_array'][$key])."]"
    			);
    			$productIdArray[$key] = $goodsDB->addProduct($productsData);  //处理会员组价格时可用
    		}
    	}

    	//处理商品分类
    	$goodsDB->delCateExtend($id);
    	if(isset($postData['_goods_category']) && $postData['_goods_category'])
    	{
    		foreach($postData['_goods_category'] as $item)
    		{
    			$goodsDB->addCateExtend(array('goods_id' => $id,'class_id' => $item));
    		}
    	}


    	//处理商品图片
//     	$photoRelationDB = new IModel('goods_photo_relation');
    	$goodsDB->delPhoto($id);
    	if(isset($postData['_imgList']) && $postData['_imgList'])
    	{
    		//$postData['_imgList'] = str_replace(',','","',trim($postData['_imgList'],','));
    		$photoData = explode(",",$postData['_imgList']);
//     		$photoDB = new IModel('goods_photo');
//     		$photoData = $photoDB->query('img in ("'.$postData['_imgList'].'")','id');
    		if($photoData)
    		{
    			foreach($photoData as $item)
    			{
//     				$photoRelationDB->setData(array('goods_id' => $id,'photo_id' => $item['id']));
//     				$photoRelationDB->add();
					$temp = array("goods_id"=>$id,"photo_id"=>$item);
    				$goodsDB->addPhoto($temp);
    			}
    		}
    	}
    	
    	
		//处理分销组价格
		$groupPriceDB = M('group_price');
		$groupPriceDB->where('goods_id='.$id)->delete(); 
		$postData['_groupPrice'] = $_POST['_groupPrice'];
    	if(isset($productIdArray) && $productIdArray)
    	{
    		foreach($productIdArray as $index => $value)
    		{
    			if(isset($postData['_groupPrice'][$index]) && $postData['_groupPrice'][$index])
    			{
    				$temp = json_decode($postData['_groupPrice'][$index],true);
    				foreach($temp as $k => $v)
    				{
    					$groupPriceDB->data(array(
    							'goods_id' => $id,
    							'product_id' => $value,
    							'distribution_level_id' => $k,
    							'price' => $v
    					));
    					$groupPriceDB->add();
    				}
    			}
    		}
			
    	}
    	else
    	{
    		if(isset($postData['_groupPrice'][0]) && $postData['_groupPrice'][0])
    		{
    			$temp = json_decode($postData['_groupPrice'][0],true);
    			foreach($temp as $k => $v)
    			{
    				$groupPriceDB->data(array(
    						'goods_id' => $id,
    						'distribution_level_id' => $k,
    						'price' => $v
    				));
    				$groupPriceDB->add();
    			}
    		}
			
    	}
    	
//     	echo $id;
//     	var_dump($_postfrom);
//     	echo("goods_spec_array:<br>");
//     	var_dump($goods_spec_array);
//     	echo("goodsUpdateData:<br>");
// 		var_dump($goodsUpdateData);
// 		echo("goodsAttrData:<br>");
// 		var_dump($goodsAttrData);
// 		echo("postData:<br>");
//     	print_r($postData['_imgList']);
// 		print_r($postData);
// 		die;


    	$this->redirect('productList');
    }

    /**
     * 删除商品（入回收站）
     * @author Muke
     */
    public function productDel(){
    	$id = I("id",0,'int');
    	
    	D("Product")->goodsInRecyle($id);

    	$this->redirect('productList');
    }
    /**
     * 彻底删除商品（回收站删除）
     * @author haichao
     */
    public function productRecyleDel(){
    	$id = I("id",0,'int');
		echo json_encode(D("Product")->delGoodsById($id));
    }
	/**
     * 批量 彻底删除商品（回收站删除）
     * @author haichao
     */
	public function productRecyleDelAll(){
		$ids = array();
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('Product')->delGoodsById($ids[$i]);
		}
		echo json_encode($arry);
    }
    /**
     * 设置分销组价格
     * @author Muke
     */
    public function memberPrice(){
    	$distribution_level = M('distribution_level')->select();
    	$this->assign('distribution_level',$distribution_level);
    	$this->assign('sell_price',I('sell_price'));
    	$this->display('memberPrice');
    }

    /**
     * 品牌列表
     */
    public function brandList(){

		if (IS_POST) {
			//获取搜索操作
			$data = I('data');
			if(!empty($data))
				$brand = D('Brand')->brandList('0',$data);
		}else{
			$brand = D('Brand')->brandList();
		}
		$brandCate = D('BrandCate')->select();
		$this->assign("page",   $brand['showPage']);
		$this->assign("list",   $brand['list']);

		$this->assign("brandcate",   $brandCate);
		$this->display();
    }

	/**
     * 品牌编辑，添加
     */
	 public function brandEdit(){
		$M = D('Brand');


    	if (IS_POST) {
			$data = I('data');
			echo json_encode($M->brandEdit($data));
    	} else {
    		$dataRow = null;
    		$brand_id = I("get.id",'','intval');
    		$dataRow = $M->getBrand($brand_id);
    		$brandCate = D('BrandCate')->select();
    		$this->assign("data", $dataRow);

			$this->assign("brandCate", $brandCate);
    		$this->display();
    	}
	 }
	/**
     * 品牌删除
     */
	public function brandDel(){
		$id = I('id')?I('id'):0;
		echo json_encode(D("Brand")->del($id));
	}

	/**
     * 品牌分类列表
     */
    public function brandCate(){
		if(IS_POST){
			$data = I('data');
			$act = I('act');
			if($act =='add'){
				echo json_encode(D("BrandCate")->add($data));
			}else if ($act == "edit") { //修改分类
				echo json_encode(D("BrandCate")->edit($data));
			}else if ($act == "del") { //修改分类
				echo json_encode(D("BrandCate")->del($data));
			}
		}else{
			$brandcateList = D('BrandCate')->brandCateList();
			$this->assign("page",   $brandcateList['showPage']);
			$this->assign("list",   $brandcateList['list']);
			$this->display();
		}
    }

    /**
     * 获取商品模型信息
     * @return JSON
     */
    public function JSON_attr(){
    	$model_id = I('model_id');
    	$attribute = D("Attribute")->get_lists_by_cate($model_id);
    	echo json_encode($attribute);
    }

    /**
     * 后台添加商品规格
     *
     */
    public function search_spec(){
    	$this->display();
    }

    /**
     * 后台选择商品规格
     *
     */
    public function select_spec(){
    	$Spec = D("ProductSpec")->specList();
    	$this->assign("speclist",   $Spec['list']);
//     	dump($Spec['list']);
//     	die;
//			json_encode($value)
//     	$this->assign("aa","ssss");
    	$this->display();
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
	//订单选择商品时搜索
	public function orderSearchGoods(){
		//post提交到goods_list
		$type = I('get.type') ? I('get.type') : 0;
		$this->assign("category", D("ProductClass")->getList());
		$this->assign("type", $type);
		$this->display();
	}
	//订单选择商品时筛选商品
	function orderSearchList()
	{
		$type = I('type');
		$search = I('search');
		$offset = $search['show_num'];
		$where['is_del'] = 0;
		if($search['keywords'] !='')
			$where['name'] = $search['keywords'];
		if($search['goods_no'] !='')
			$where['product_sn'] = $search['goods_no'];
		$where['spec_array'] = ($search['is_products'] ==0)? array('eq',''):array('neq','');
		if($search['min_price'] !='')
			$where['sell_price'] = array('egt',$search['min_price']);
		if($search['max_price'] !='')
			$where['sell_price'] = array('elt',$search['max_price']);
		$data = D("Product")->limit($offset)->where($where)->select();
		//$data = M("Product")->select();
		$goodsdata = array();
		$i=0;
		foreach ($data as $key => $val){
			if($search['is_products'] =='1' && $type==0){
				$specarray = M("product_stocks")->where('goods_id = '.$val['product_id'])->select();
				//$specarray = M("product_stocks")->where('goods_id = '.'50')->select();
				//dump($specarray);die();

				foreach($specarray as $v){
					$goodsdata[$i]['goods_price'] = $v['sell_price'];
					$goodsdata[$i]['market_price'] = $v['market_price'];
					$photo = M('product_photo')->where('photo_id ='.$val['photo_id'])->find();
					$goodsdata[$i]['img'] = $photo['folder'].$photo['name'].'.'.$photo['type'];
					$goodsdata[$i]['name'] = $val['name'];
					$goodsdata[$i]['goods_weight'] = $v['weight'];
					$goodsdata[$i]['goods_no'] = $v['product_sn'];
					$goodsdata[$i]['product_id'] = $v['product_id'];
					$goodsdata[$i]['goods_id'] = $v['goods_id'];
					$goodsdata[$i]['spec_array'] = $v['spec_array'];
					$goodsdata[$i]['data'] = json_encode($goodsdata[$i]);
					$goodsdata[$i]['spec_array'] = json_decode($v['spec_array'],true);
					//dump($goodsdata);
					//$goodsdata[1]['data'] = $goodsdata[1];
					$i++;
				}
			}else{
				$goodsdata[$key]['goods_price'] = $val['sell_price'];
				$goodsdata[$key]['market_price'] = $val['market_price'];
				$photo = M('product_photo')->where('photo_id ='.$val['photo_id'])->find();
				$goodsdata[$key]['img'] = $photo['folder'].$photo['name'].'.'.$photo['type'];
				$goodsdata[$key]['name'] = $val['name'];
				$goodsdata[$key]['goods_weight'] = $val['weight'];
				$goodsdata[$key]['goods_no'] = $val['product_sn'];
				$goodsdata[$key]['product_id'] = 0;
				$goodsdata[$key]['goods_id'] = $val['product_id'];
				$goodsdata[$key]['spec_array'] = $val['spec_array'];
				$goodsdata[$key]['data'] = json_encode($goodsdata[$key]);
				$goodsdata[$key]['spec_array'] = json_decode($val['spec_array'],true);
			}

		}
		$goodsdatas = array();
		if($search['category_id'] !=''){
			$classId = $search['category_id'];
			foreach($goodsdata as $key =>$val){
				$idfalse = 0;
				$class = M("product_class_extend")->where('goods_id = '.$val['goods_id'])->select();
				foreach($class as $k =>$v){
					if($v['class_id']==$classId){
						$idfalse = 1;
						break;
					}
				}
				if($idfalse==0){
					unset($goodsdata[$key]);
				}else{
					$goodsdatas[] = $goodsdata[$key];
				}
			}
		}else{
			$goodsdatas = $goodsdata;
		}
		$goodsdatas = array_slice($goodsdatas,0,$offset,true);
		$this->assign('data',$goodsdatas);

		$this->display();
	}
	
	/**
	 * 优惠活动列表
	 * @author Muke
	 */
	public function couponList(){
		$couponList = M('coupon')->where(array('is_del'=>0))->select();
		
		$this->assign('couponList',$couponList);
		$this->assign('pagetitle','优惠劵列表');
		$this->display('couponList');
	}
	/**
	 * 上下架处理
	 * @author haichao
	 */
	public function updateShelf(){
		$id = I('get.id');
		$value = I('get.value');
		echo json_encode(D('Product')->updateShelf($id,$value));
		
	}
	/**
	 * 批量上下架处理
	 * @author haichao
	 */
	public function updateShelfAll(){
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$value = I('get.value');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('Product')->updateShelf($ids[$i],$value);
		}
		echo json_encode($arry);
	}
	
	/**
	 * 优惠活动编辑
	 * @author Muke
	 */
	public function couponEdit(){
		$id = I('id');
		if($id){
			$coupon = M('coupon')->where('coupon_id='.$id)->find();
			if(!$coupon){
				$this->error('找不到该资料');
			}
			
			$this->assign('coupon',$coupon);
		}
		
		$this->assign('pagetitle','优惠劵编辑');
		$this->display('couponEdit');
	}
	
	/**
	 * 添加优惠劵
	 * @author Muke
	 */
	public function couponAdd(){
		$this->couponEdit();
	}
	
	/**
	 * 保存优惠劵
	 * @author Muke
	 */
	public function couponSave(){

		//检查表单提交状态
		if(!$_POST)
		{
			$this->error('请确认表单提交正确');
		}
		
		$id = I('coupon_id');
		
		$coupon_data = array(
				'coupon_name' => I('post.coupon_name'),
				'coupon_amount' => I('post.coupon_amount'),
				'min_amount' => I('post.min_amount'),
				'max_amount' => 0,
				'act_range' => 0,//使用范围
				'start_time' => strtotime(I('post.start_time')),
				'end_time' => strtotime('+1 days',strtotime(I('post.end_time'))),
		);
		
// 		header("Content-type: text/html; charset=utf-8");
// 		dump($coupon_data);
// 		die;
		
		$is_save = false;
		
		if($id){//修改
			if(M('coupon')->where('coupon_id='.$id)->save($coupon_data)){
				$is_save = true;
			}
		}else{//新增
			if(M('coupon')->add($coupon_data)){
				$is_save = true;
			}
		}
		
		if($is_save){
			$this->success('保存成功',U('product/couponList'));
		}else{
			$this->error('保存失败，请重新检查');
		}
		
// 		dump($id);
	}
	
	/**
	 * 删除优惠卷（入回收站）
	 * @author Muke
	 */
	public function couponDel(){
		$id = I("id",0,'int');
		
		M('coupon')->where('coupon_id='.$id)->setField('is_del',1);
		//D("Product")->goodsInRecyle($id);
	
		$this->redirect('couponList');
	}
	
	/**
	 * 商品搜索 
	 * @author Muke
	 * @return JSON
	 */
	public function search_json(){
		$cate_id = I('cate_id');
		
		if($cate_id){
			$cate_ids = M('product_class_extend')->where(array('class_id'=>$cate_id))->getField('goods_id',true); 
			$cond['product_id'] = array('in',$cate_ids);
		}
		
		
		$cond['name'] = array("LIKE","%".I('keyword')."%");
		$cond['is_del'] = array('NEQ',1);
		
		
		
	 	foreach (M('product')->where($cond)->select() as $val){
	 		
	 		$opt[] = array(
	 				'value' => $val['product_id'],
	 				'text' => $val['name'],
	 				'data' => $val['sell_price']);
	 	}
		
		
		
		$data['error'] = 0;
		$data['message'] = '';
		$data['content'] = $opt;
		
		
		exit(json_encode($data));
	}
	
	/**
	 * 关联商品
	 * @author Muke
	 */
	public function add_link_goods(){
		$MOD = M('link_goods');
		$goods_id = I('goods_id');
		$is_double = !I('is_double');
		
		$add_ids = I('add_ids');
		foreach ($add_ids as $v){
			if($is_double){
				$MOD->add(array('goods_id'=>$v,'link_goods_id'=>$goods_id,'is_double'=>$is_double));
			}
			$MOD->add(array('goods_id'=>$goods_id,'link_goods_id'=>$v,'is_double'=>$is_double));
			
		}
		
		
		$link_goods = $this->get_link_goods($goods_id);
		
		$options        = array();
		foreach ($link_goods AS $val){
			$double = $val['is_double']?' 【双向关联】':' 【单向关联】';
			$options[] = array('value'  => $val['product_id'],
								'text'      => $val['name'].$double,
								'data'      => ''
			);
		}
		
		
		$data['error'] = 0;
		$data['message'] = '';
		$data['content'] = $options;
		
		exit(json_encode($data));
		
	}
	
	/**
	 * 删除关联产品
	 * @author Muke
	 */
	public function drop_link_goods(){
		$MOD = M('link_goods');
		$goods_id = I('goods_id');
		
		$drop_ids = I('drop_ids');
		foreach ($drop_ids as $v){
			$con = array('goods_id'=>$goods_id,'link_goods_id'=>$v);
			$link = $MOD->where($con)->getField('is_double');
			if($link){
				$MOD->where(array('goods_id'=>$v,'link_goods_id'=>$goods_id))->delete();
			}
			
			$MOD->where(array('goods_id'=>$goods_id,'link_goods_id'=>$v))->delete();
		}
		
		$link_goods = $this->get_link_goods($goods_id);
		
		$options        = array();
		foreach ($link_goods AS $val){
			$double = $val['is_double']?' 【双向关联】':' 【单向关联】';
			$options[] = array('value'  	=> $val['product_id'],
								'text'      => $val['name'].$double,
								'data'      => ''
			);
		}
		
		
		$data['error'] = 0;
		$data['message'] = '';
		$data['content'] = $options;
		
		exit(json_encode($data));
	}
	
	/**
	 * 获取商品的关联商品
	 * @author Muke
	 * @param unknown $goods_id
	 */
	private function get_link_goods($goods_id){
		$MOD = M('link_goods');
		return $MOD->field('p.product_id,p.name,lg.is_double')
					->alias('lg')
					->where(array('lg.goods_id'=>$goods_id,'p.is_del'=>0))
					->join('product as p on p.product_id=lg.link_goods_id','left')
					->select();
	}
	
	/**
	 * 商品套餐列表
	 * @author Muke
	 */
	public function packageList(){
		
		$cond['is_del'] = array('NEQ',1);
		
		$Page = new \Think\Page(D("package")->getPackageCount($cond),C("PAGE_SIZE"));
		
		
		$list = D('package')->getPackageList($cond,$Page->firstRow,$Page->listRows);
		
// 		print_r($list);
// 		die;
		
		$this->assign('packageList',$list);
		$this->assign('page',$Page->show());
		$this->display('packageList');	
	}
	
	/**
	 * 添加商品套餐
	 * @author Muke
	 */
	public function packageAdd(){
		$this->packageEdit();
	}
	
	/**
	 * 编辑商品套餐
	 * @author Muke
	 */
	public function packageEdit(){
		$id = I('get.id');
		
		if($id){
			$pagetitle = '编辑优惠套餐';
			$package = D('package')->getPackageInfo($id);
			if(!$package) $this->error('没有找到该套餐');
			$this->assign('package',$package);
// 			print_r($package);
// 			die;
		}else{
			$pagetitle = '新增优惠套餐';
		}
		$this->assign('pagetitle',$pagetitle);
		$this->display('packageEdit');
	}
	
	/**
	 * 保存商品套餐
	 * @author Muke
	 */
	public function packageSave(){
		$id = I('post.package_id');
		$nowtime = time();
		$package_data = array(
				'package_name' => I('post.package_name'),
				'package_amount' => I('post.package_amount'),
				'start_time' => strtotime(I('post.start_time')),
				'end_time' => strtotime(I('post.end_time')),
				'note' => I('post.package_id'),
				'create_time' => $nowtime,
				'update_time' => $nowtime,
				'is_sale' => I('post.is_sale'),
		);
		$package_data['is_favorable'] = $package_data['package_amount']?1:0;
		$goods_data = array();
		$product = I('post.product');
		$group_price = I('post.group_price');
		$level_id = I('post.level_id');
		$group_price_length = count($group_price);
		$product_length = count($product['goods_id']);
		$where['package_id'] = $id;
		$stass = M('package_group_price')->where($where)->delete();
		for ($i=0;$i<$group_price_length;$i++){
			if($group_price[$i]!=''){
				$data['distribution_level_id'] = $level_id[$i];
				$data['price'] = $group_price[$i];
				$data['package_id'] = $id;
				M('package_group_price')->add($data);
			}
		}
		for ($i=0;$i<$product_length;$i++){
			$goods_data[$i]['match_name'] = $product['match_name'][$i];
			$goods_data[$i]['goods_id'] = $product['goods_id'][$i];
			$goods_data[$i]['is_required'] = $product['is_required'][$i];
		}
		
// 		dump($package_data);
// 		dump($goods_data);
// 		die;
		
		if($id){
			if(D('package')->updatePackage($id,$package_data,$goods_data)){
				$this->success('修改套餐成功',U('product/packageList'));
			}else{
				$this->error('修改失败');
			}
		}else{
			if(D('package')->addPackage($package_data,$goods_data)){
				$this->success('添加套餐成功',U('product/packageList'));
			}else{
				$this->error('添加失败');
			}
		}
		
		
		
	}
	
	//删除优惠套餐
	public function packageDel(){
		$id = I("id",0,'int');
		 
		D("package")->delPackage($id);
		
		$this->redirect('packageList');
	}
	
	
	/**
	 * 商品检索（JSON）
	 * @author Muke
	 * @return JSON
	 */
	public function json_search_goods(){
		
	}
	public function packageMemberPrice(){
		$package_id = I('get.package_id');
		$package = D("package")->where('package_id = '.$package_id)->find();
		$level = M('distribution_level')->select();
		foreach($level as $key => $val){
			$where['distribution_level_id'] = $val['level_id'];
			$where['package_id'] = $package_id;
			$level[$key]['price'] = M('package_group_price')->where($where)->getField('price');
			$level[$key]['id'] = M('package_group_price')->where($where)->getField('id');
		}
		$this->assign('sell_price',$package['package_amount']);
		$this->assign('distribution_level',$level);
		$this->display('memberPrice');
	}

}