<?php
namespace Index\Model;
use Think\Model;

class ProductModel extends CommonModel{
	
	public function uploadFile(){
		
		$data["error"] = "";//错误信息
		$data["msg"] = "";//提示信息
		$path = date('Y-m-d',time());
		$paths = C('upload_rootPath').$path;
		if(!file_exists($paths))
			mkdir($paths);
		$config = array(
				'maxSize' => C('upload_maxSize'),   // 设置附件上传大小
				'exts'    => C('upload_exts'),      // 设置附件上传类型
				'rootPath'=> C('upload_rootPath'),  // 设置附件上传目录
		);
		$upload = new \Think\Upload($config);		// 实例化上传类     
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息        
			
			$data['msg'] = "上传失败";
			$data['error'] = "1";
		}else{// 上传成功        
			//$this->success('上传成功！');  
			$fileName = '/uploads/'.$info['photo']['savepath'].$info['photo']['savename'];
			$data['msg'] = "上传成功";
			$data['file'] = $fileName;
			
		}
		return $data;
	}
	//订单搜索商品列表
	public function getOrdergoods(){
		$search = I('search');
		
	}
	public function search(){
		
	}
	/**
	 * 商品上下架
	 * @author haichao
	 * @param unknown $where
	 */
	public function updateShelf($id,$value){
		$where['product_id'] = $id;
		$data['is_del'] = $value;
		$datas = D('Product')->where($where)->find();
		$status = D('Product')->where($where)->save($data);
		if($status){
			//日志文件
			$info = $this->productStatus($value);
			$log = '商品：'.$datas['name'].'('.$info.')操作';
			//$this->admin_log($log);
			$val = ($value==0) ? 4: 5;
			$this->sql_log($log,$this->getLastSql(),1,$val);
			return array('status'=>1,'info'=>'修改成功','url'=>'');
		}else{
			return array('status'=>0,'info'=>'修改成功','url'=>'');
		}
		
	}
	public function productStatus($is_del){
		if($is_del==0){
			return '上架';
		}else if($is_del==1){
			return '删除';
		}else{
			return '下架';
		}
	}
	
	public function productSpec(){
		
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * 商品信息列表
	 * @author Muke
	 * @param unknown $where
	 * @param unknown $limit
	 * @param unknown $offset
	 * @param unknown $order
	 * @param unknown $select
	 */
	public function goodsList($where,$limit,$offset,$order,$select=""){
	
		$field['CONCAT(product_photo.folder,product_photo.name,"_s.",product_photo.type)']= 'photo';
		if($select){
			foreach(explode(',', $select) as $v){
				$field[] = 'product.'.$v;
			}
		}else{
			$field[] = 'product.*';
		}
		$this->field($field);
		if($where) $this->where($where);
		if($offset) $this->limit($limit,$offset);
		$this->order($order);
		
		$this->join('product_photo on product_photo.photo_id = product.photo_id','LEFT');
		
		return $this->select();
	}
	
	/**
	 * 商品信息总数
	 * @author Muke
	 * @param unknown $where
	 */
	public function goodsCount($where){
		return M("Product")->where($where)->count();
	}
	
	/**
	 * 根据ID查询商品信息
	 * @author muke
	 * 
	 * @param int 商品ID
	 * @return array 商品信息数组
	 */
	public function loadGoods($id){
		if($id){
			return M("Product")->where(array("product_id"=>$id))->find();
		}
		return array();
	}
	
	/**
	 * 新增商品信息
	 * @author muke
	 * 
	 * @param array 
	 * @return int 返回新增商品ID
	 */
	public function addGoods($data){
		if (is_array($data) && !empty($data)) {
			$log = '添加商品：'.$data['name'];
			$status = M("Product")->add($data);
			$this->sql_log($log,$this->getLastSql(),1,3);
			return $status;
		}
		return 0;
	}
	
	/**
	 * 修改商品信息
	 * @author muke
	 * 
	 * @param int 商品ID
	 * @param array
	 * @return boolean
	 */
	public function updateGoods($id,$data){
		if($id && is_array($data)){
			$name = M("Product")->where('product_id = '.$id)->getField('name');
			$log = '修改商品：'.$name;
			$status = M("Product")->where(array("product_id"=>$id))->save($data);
			$this->sql_log($log,$this->getLastSql(),1,1);
			return $status;
		}
		return FALSE;
	}
	
	/**
	 * 删除商品信息
	 * @author muke
	 * 
	 * @param int 商品ID
	 * @return boolean
	 */
	public function delGoodsById($id){
		if($id){
			$name = M("Product")->where('product_id = '.$id)->getField('name');
			$log = '删除商品：'.$name;
			$status = M("Product")->delete($id);
			$this->sql_log($log,$this->getLastSql(),1,2);
			return $status;
		}
		return FALSE;
	}
	
	/**
	 * 商品入回收站
	 * 
	 * @author Muke
	 * @param int $id
	 * @return boolean
	 */
	public function goodsInRecyle($id){
		
		if($id){
			$name = M("Product")->where('product_id = '.$id)->getField('name');
			$log = '移到商品回收站：'.$name;
			$status = $this->where(array("product_id"=>$id))->setField("is_del",1);
			$this->sql_log($log,$this->getLastSql(),1,6);
			return $status;
		}
		return FALSE;
	}
	
	/**
	 * 商品出回收站
	 * @author Muke
	 * @param int $id
	 * @return boolean
	 */
	public function goodsOutRecyle($id){
		if($id){
			$name = M("Product")->where('product_id = '.$id)->getField('name');
			$log = '商品从回收站恢复：'.$name;
			$status = M("Product")->where(array("product_id"=>$id))->setField("is_del",0);
			$this->sql_log($log,$this->getLastSql(),1,6);
			return $status;
		}
		return FALSE;
	}
	
	/**
	 * 新增商品属性
	 * @author muke
	 * 
	 * @param array
	 * @return int 返回新增商品ID
	 */
	public function addGoodsAttr($data){
		if (is_array($data) && !empty($data)) {
			return M("Product_attr")->add($data);
		}
		return 0;
	}
	
	/**
	 * 删除商品属性
	 * -----根据商品ID
	 * @author muke
	 * 
	 * @param int 商品ID
	 * @return boolean
	 */
	public function delGoodsAttrByPid($product_id){
		if($product_id){
			return M("Product_attr")->where(array('product_id'=>$product_id))->delete();
		}
		return FALSE;
	}
	
	/**
	 *  新增货品
	 * @author Muke
	 * @param array $data
	 * @return number
	 */
	public function addProduct($data){
		if (is_array($data) && !empty($data)) {
			return M("product_stocks")->add($data);
		}
		return 0;
	}
	
	/**
	 * 删除货品
	 * ------根据商品ID
	 * @author Muke
	 * @param int $product_id 商品ID
	 * @return boolean
	 */
	public function delProductByPid($product_id){
		if($product_id){
			return M("product_stocks")->where(array('goods_id'=>$product_id))->delete();
		}
		return FALSE;
	}
	
	/**
	 * 查询商品扩展分类
	 * @author Muke
	 * @param unknown $pid
	 * @return multitype:
	 */
	public function CateExtendById($pid,$select=""){
		
		return M("product_class_extend")->field($select)->join("product_class on product_class_extend.class_id = product_class.class_id","LEFT")->where(array("goods_id"=>$pid))->select();
	}
	
	/**
	 * 新增商品扩展分类
	 * 
	 * @author Muke
	 * @param array $data
	 * @return number
	 */
	public function addCateExtend($data){
		if (is_array($data) && !empty($data)) {
			return M("product_class_extend")->add($data);
		}
		return 0;
	}
	
	
	/**
	 * 删除商品扩展分类
	 * --------根据商品ID
	 * @author Muke
	 * @param int $goodsid 商品ID
	 * @return boolean
	 */
	public function delCateExtend($goodsid){
		if($goodsid){
			return M("product_class_extend")->where(array('goods_id'=>$goodsid))->delete();
		}
		return FALSE;
	}
	
	/**
	 * 删除商品相册
	 * @author Muke
	 * @param int $goodsid 商品ID
	 * @return boolean
	 */
	public function delPhoto($goodsid){
		if($goodsid){
			return M("product_photo_relation")->where(array('goods_id'=>$goodsid))->delete();
		}
		return FALSE;
	}
	
	/**
	 * 新增商品相册
	 * @author Muke
	 * @param array $data
	 * @return number
	 */
	public function addPhoto($data){
		if (is_array($data) && !empty($data)) {
			return M("product_photo_relation")->add($data);
		}
		return 0;
	}
	
	/**
	 * 
	 * @author homter
	 * @param unknown_type $data
	 * @return multitype:number string |multitype:number string Ambigous <string, mixed>
	 */
	public function replaceContent($data){
		
		/*添加验证*/
		if (empty($data["replaceContent"]) || empty($data["newContent"])) {
			return array('status' => 0, 'info' => "被替换的内容与新内容都不能为空");
		}
		if(strlen($data["replaceContent"]) < 3){
			return array('status' => 0, 'info' => "被替换的内容不能少于3个字符");
		}
		if (empty($data["startId"])) {
			return array('status' => 0, 'info' => "请输入开始ID");
		}
		
		if (!empty($data["startId"]) && !empty($data["endId"])) {
			for($i=$data["startId"]; $i<=$data["endId"]; $i++){
				$procontent = $this->where("`product_id`= ".$i)->field('content')->find();
				$procontent = str_replace($data['replaceContent'], $data["newContent"], $procontent['content']);
				$arr1 = array('content'=>$procontent);
				$status1 = $this->where("`product_id`= ".$i)->save($arr1);
			}
			
		}
		else{
			$procontent = $this->where("`product_id`= ".$data['startId'])->field('content')->find();
			$procontent = str_replace($data['replaceContent'], $data["newContent"], $procontent['content']);
			$arr1 = array('content'=>$procontent);
			$status1 = $this->where("`product_id`= ".$data['startId'])->save($arr1);
		}
		

		if($status1){
			return array('status' => 1, 'info' => "产品内容替换成功", 'url' => U('System/replaceContent'));
		}
		else{
			return array('status' => 1, 'info' => "没有任何更新", 'url' => U('System/replaceContent'));
		}
	}
}
