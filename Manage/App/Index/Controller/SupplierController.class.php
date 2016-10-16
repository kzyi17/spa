<?php
namespace Index\Controller;
use Index\Controller\CommonController;


class SupplierController extends CommonController{
	
	 public function index(){
		$type = I('post.type');
		$type_sn = I('post.type_sn');
		$supplier_type = I('post.supplier_type');
	 	if($type=='0'){
			$this->csv();exit();
		}else{
			$lists = D('Supplier')->getList(0);
			$Typelist = D('Supplier')->getSupplierTypeList(2);
			$SupplierTypelist = D('Supplier')->getSupplierTypeList(1);
			//print_r($lists);
			$this->assign('list',$lists['list']);
			$this->assign('page',$lists['page']);
			$this->assign('type_sn',$type_sn);
			$this->assign('supplier_type',$supplier_type);
			$this->assign('SupplierTypelist',$SupplierTypelist);
			$this->assign('Typelist',$Typelist);
			$this->display();
		}
	 	
	 }
	 public function csv(){
		$type_sn = I('post.type_sn');
		$supplier_type = I('post.supplier_type');
		// create a simple 2-dimensional array
		$data = D('Supplier')->getList(1);
		$title = C('name');
		if($type_sn!=''){
			$typename = M('supplier_type')->where(array('type_sn'=>$type_sn,'type'=>1))->getField('supplier_typename');
			$title .= ($typename ? '('.$typename.')业务类型-' : '(全部业务类型)');
		}
		if($supplier_type!=''){
			$suppliername = M('supplier_type')->where(array('type_sn'=>$supplier_type,'type'=>2))->getField('supplier_typename');
			$title .= ($suppliername ? '('.$suppliername.')供应商类型-' : '(全部供应商类型)');
		}
		$title .=date('Y-m-d',time());
		$sql = D('Supplier')->getLastSql();
		$log = '导出csv'.$title;
		$datas['log'] = $log;
		$datas['ip'] = get_client_ip();
		$adminInfo = session('UserInfo');
		$datas['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		$datas['create_time'] = time();
		$datas['sql_log'] = $sql;
		$datas['log_type'] = 5;
		$datas['log_type_val'] = 1;
		M('admin_log')->add($datas);
		import("Common.Extend.Excel_XML");
		$list = array();
		$list[0]['company'] = '公司名称/个人';
		$list[0]['contacts'] = '联系人';
		$list[0]['weixin'] = '微信号';
		$list[0]['url'] = '网址';
		$list[0]['telephone'] = '联系方式';
		$list[0]['create_time'] = '申请时间';
		$list[0]['status'] = '处理状态';
		$list[0]['supplierType'] = '供应商类型';
		$list[0]['type_name'] = '业务类型';
		$list[0]['main_business'] = '主营业务';
		$list[0]['address'] = '地址';
		$i=1;
		for($j=0;$j<count($list[0]);$j++){
			$widths[$j]['width'] = 80;
		}
		$widths[9]['width'] = 300;
		$widths[10]['width'] = 150;
		foreach($data['list'] as $key => $val){
			$list[$i]['company'] = $val['company'];
			$list[$i]['contacts'] = $val['contacts'];
			$list[$i]['weixin'] = $val['weixin'];
			$list[$i]['url'] = $val['url'];
			$list[$i]['telephone'] = $val['telephone'];
			$list[$i]['create_time'] = date('Y-m-d:H:i',$val['create_time']);
			$list[$i]['status'] = $this->getStatusName($val['status']);
			$list[$i]['supplierType'] = $val['supplier_type'];
			$list[$i]['type_name'] = $val['type_name'];
			$list[$i]['main_business'] = $val['main_business'];
			$list[$i]['address'] = $val['address'];
			$i++;
			
		}
		$height = 30;
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($list,$widths,$height);
		$xls->generateXML($title);
		//$xls->export_csv($data['list'],'导出csv','test');
	 }
	 public function getStatusName($status){
		if($status==0){
			return '审核未通过';
		}else if($status==1){
			return '未审核';
		}else if($status==2){
			return '审核中';
		}else if($status==3){
			return '审核通过';
		}else if($status==4){
			return '取消资格';
		}else{
			return '未知';
		}
	 }
	 /**
	  * 供应商删除
	  * @author haichao
	  */
	 public function supplierDel(){
	 	$id = I('id');
	 	echo json_encode(D('Supplier')->supplierDel($id));
	 }
	 /**
	  * 供应商列表
	  * @author haichao
	  */
	 public function supplierShow(){
		$id = I('get.id');
		if(IS_POST){
	 		$datas['status'] = $_POST['status'];//默认审核中
			$datas['admin_note'] = $_POST['admin_note'] ? $_POST['admin_note'] :'';
			$datas['type_sn'] = $_POST['type_sn'];
			$datas['supplier_type'] = $_POST['supplier_type'];
			$datas['examine_time'] = time();//
	 		D('Supplier')->where('id = '.$id)->save($datas);
		}
	 	$data = D('Supplier')->where('id = '.$id)->find();
		$province = M('Areas')->where('area_id = '.$data['province'])->getField('area_name');
		$city = M('Areas')->where('area_id = '.$data['city'])->getField('area_name');
		$area = M('Areas')->where('area_id = '.$data['area'])->getField('area_name');
		$data['areas'] = $province.' '.$city.' '.$area.' ';
		$list = D('Supplier')->getSupplierTypeList(1);
		$supplierTypeList = D('Supplier')->getSupplierTypeList(2);
	 	$this->assign('data',$data);
		$this->assign('list',$list);
		$this->assign('supplierTypeList',$supplierTypeList);
	 	$this->display();
	 	if(IS_POST){
	 		echo '<script>popupalert("'.'修改成功'.'","'.U('Supplier/supplierShow',array('id'=>$id)).'");</script>';
	 		exit();
	 	}
	 }
	 public function supplierType(){
		$list = D('Supplier')->getSupplierTypeList(1);
		$this->assign('list',$list);
		$this->assign('type',1);
		$this->display();
	 }
	 public function supplierTypeEdit(){
		$id = I('get.id');
		$type=I('get.type');
		if(IS_POST){
			$type=I('post.type');
			$data = D('Supplier')->supplierTypeEdit($type);
			echo json_encode($data);exit();
		}else{
			if(intval($id)>0){
				$this->setcurrentname('修改供应商业务类型');
			}else{
				$this->setcurrentname('添加供应商业务类型');
			}
			$data = M('supplier_type')->where(array('id'=>$id))->find();
			$data['type'] = $type;
			$this->assign('data',$data);
			$this->display();
		}
	 }
	 public function type(){
		$list = D('Supplier')->getSupplierTypeList(2);
		$this->assign('list',$list);
		$this->assign('type',2);
		$this->display('supplierType');
	 }
	  public function supplierTypedel(){
		$id = I('get.id');
		$result = M('supplier_type')->where(array('id'=>$id))->delete();
		echo $result ? json_encode(array('status'=>1,'info'=>'删除成功','url'=>U('Supplier/supplierType'))) : json_encode(array('status'=>0,'info'=>'删除失败','url'=>U('Supplier/supplierType')));
	 }
	 public function supplierUpdate(){
		$id = I('get.id');
		$name = I('get.name');
		$value = I('get.value');
		$data["$name"] = $value;
		$result = M('supplier_type')->where(array('id'=>$id))->save($data);
		echo $result ? json_encode(array('status'=>1,'info'=>'修改成功','url'=>U('Supplier/supplierType'))) : json_encode(array('status'=>0,'info'=>'修改失败','url'=>U('Supplier/supplierType')));
	 }
}