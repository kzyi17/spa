<?php
namespace Index\Controller;
use Index\Controller\CommonController;
class SystemController extends CommonController{
	/**
	 * 网址配置
	 * @author  haichao
	 */
	public function index(){
		
		$config = include(realpath('App/Common/Conf/site.php'));
		$guide = M('guide')->select();
		$this->assign('config',$config);
		$this->assign('guide',$guide);
		$this->display();
		if(IS_POST){
			$info = D('ShopConf')->saveConfig();
			if($info['status'] ==1){
				echo '<script>popupalert("'.$info['info'].'","'.$info['url'].'");</script>';
			}
		}
	}
	/**
	 * 邮件发送测试
	 *
	 * @author  haichao
	 */
	public function testSendMail()
	{
		$data = I('data');
		$mailconfig = $data;
		unset($mailconfig['test_address']);
		import("Common.Extend.Mail");
		$mail = new \Mail($mailconfig);
		if($error = $mail->getError())
		{
			$result = array('isError'=>true,'message' => $error);
		}
		else
		{
			$title    = 'email test';
			$content  = 'success';
			if($mail->send($data['test_address'],$title,$content))
			{
				$result = array('isError'=>false,'message' => '恭喜你！测试通过');
			}
			else
			{
				$result = array('isError'=>true,'message' => '测试失败，请确认您的邮箱已经开启的smtp服务并且配置信息均填写正确');
			}
		}
		echo json_encode($result);
	}
	/**
	 * 清除缓存
	 *
	 * @author  haichao
	 */
	public  function clearCache(){
		 $Manage_caches=array(
     			"ManageCache" => array("name" => "网站后台缓存文件", "path" => "App/Runtime/Cache"),
     			"ManageData" => array("name" => "网站后台数据库字段缓存文件", "path" => "App/Runtime/Data"),
     			"ManageLog" => array("name" => "网站后台日志缓存文件", "path" => "App/Runtime/Logs"),
     			"ManageTemp" => array("name" => "网站后台临时缓存文件", "path" => "App/Runtime/Temp"),
     	);
          $flag=delDirAndFile($Manage_caches['ManageCache']['path']);
          $flag=delDirAndFile($Manage_caches['ManageData']['path']);
          $flag=delDirAndFile($Manage_caches['ManageLog']['path']);
          $flag=delDirAndFile($Manage_caches['ManageTemp']['path']);

          if($flag==false){
              $key=0;
          }
          if($key==0){
          	 echo 1;
          }else{
          	 echo -1;
          }
	}


	/**
	 * 配送方式列表
	 *
	 * @author  haichao
	 */
	public function deliveryList(){
		$data = I('data');
		$list = D('Delivery')->deliveryList($data);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();

	}



	/**
	 * 地区列表
	 *
	 * @author  haichao
	 */
	public function areaList(){
		$data = I('get.data');
		$list = D('Areas')->AreasList();
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('keywords',$data['area_name']);
		$this->display();
	}
	/**
	 * 地区数显示JSON
	 *
	 * @author  haichao
	 */
	public function areaJson(){
		$areaId = I('get.id',0,'intval');
		$data = D('Areas')->AreasList($areaId);
		echo json_encode($data['list']);
	}
	/**
	 * 修改添加地区
	 *
	 * @author  haichao
	 */
	public function areaUpdate(){
		echo json_encode(D('Areas')->areaUpdate());
	}
	/**
	 * 删除地区
	 *
	 * @author  haichao
	 */
	public function areaDel(){
		$area_id = I('get.area_id',0,'intval');
		echo json_encode(D('Areas')->del($area_id));
	}

	/**
	 * 物流公司列表
	 *
	 * @author  homter
	 */
	public function freightList(){
		$list = D('FreightCompany')->getFreightCompanyList();
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display('freightList');
	}
	
	/**
	 * 新增物流公司
	 * @author Muke
	 */
	public function freightAdd(){
		$this->freightEdit();
	}
	
	/**
	 * 编辑物流公司
	 * @author  Muke
	 */
	public function freightEdit(){
		$id = I('get.id',0,'int');
		if($id){
			$this->assign('data',D('FreightCompany')->loadData($id));
		}
		
		$this->display('freightEdit');
	}
	
	/**
	 * 保存物流公司信息
	 * @author Muke
	 */
	public function freightSave(){
		//检查表单提交状态
		if(!IS_POST)
		{
			$this->error('请确认表单提交正确');
		}
		 
		$id = I('post.id',0,'int');
		$saveData['name'] = I('post.name');
		$saveData['code'] = I('post.code');
		$saveData['url'] = I('post.url');
		$saveData['sort'] = I('post.sort',99,'int');
		
		if($id){//编辑
			$result = D('FreightCompany')->editData($id,$saveData);
		}else{//新增
			$result = D('FreightCompany')->addData($saveData);
		}
		
		if($result){
			$this->success('物流公司信息保存成功',U('system/freightList'));
		}else{
			$this->error('保存失败');
		}
		
	}
	
	/**
	 * 配送方式编辑
	 *
	 * @author  haichao
	 */
	public function deliveryEdit(){
		if (IS_POST) {
			$data = I('data');
			echo json_encode(D('Delivery')->Edit($data));
			exit;
		} else {
			$id = I("get.id",'','intval');
			$dataRow = D('Delivery')->getDelivery($id);
			$areasData = D('Areas')->where('parent_id = 0')->field('area_id,area_name')->select();
			$list = D('FreightCompany')->getFreightCompanyList();
			$weightLevel = D('Delivery')->getWeightLevel();
			$this->assign('list',$list['list']);
			$this->assign('data',$dataRow);
			$this->assign('areasData',$areasData);
			$this->assign('weightLevel',$weightLevel);
			$this->display('deliveryEdit');
		}
	}


	/**
	 * 配送方式批量删除
	 **/
	public function deliveryDelAll(){
		$Delivery = D("Delivery");
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$type = I('get.type');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			if($type =='index')
				$arry = $Delivery->del($ids[$i]);
			else
				$arry = $Delivery->recycleDel($ids[$i]);
		}
		echo json_encode($arry);exit;

	}
	/**
	 * 配送方式   恢复    删除
	 **/
	public function deliveryUpdateStatus(){
		$Delivery = D("Delivery");
		$id = I('get.id');
		$type = I('get.type');
		if($type =='index'){
			echo json_encode($Delivery->Del($id));
		}else{
			echo json_encode($Delivery->regainDelivery($id));
		}


	}
	/**
	 *  配送方式 回收站 彻底删除
	 **/
	public function deliveryRecycleDel(){
		$Delivery = D("Delivery");
		$id = I('get.id');
		echo json_encode($Delivery->recycleDel($id));

	}
	/**
	 * 配送方式 回收站列表
	 **/
	 public function deliveryRecycle(){
	 	$data = I('data');
	 	$data['is_delete'] = 1;
	 	$list = D('Delivery')->deliveryList($data);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display('deliveryList');
	 }
	 /**
	  * 支付方式列表
	  *
	  * @author  haichao
	  */
	 public function paymentList(){
	 	$list = M('Payment')->select();
	 	$this->assign('list',$list);
	 	$this->display();
	 }
	 public function paymentEdit(){
	 	if (IS_POST) {
	 		$data = I('data');
	 		echo json_encode(D('Payment')->Edit($data));
	 		exit;
	 	}else{
		 	$id = I("get.id",'','intval');
			$dataRow = D('Payment')->getPayment($id);
			$this->assign('data',$dataRow);
		 	$this->display();
	 	}
	 }
	 
	 public function replaceContent(){
	 	if(IS_POST){
	 		$data = I('data');
	// 		print_r($data);
	// 		exit;
	 		echo json_encode(D('Product')->replaceContent($data));
	 		exit;
	 	}
	 	else{
	 		$this->display();
	 	}
	 }
	 //广告列表
	 public function advertList(){
	 	$id = I('id');
	 	$lists = D('Advertisement')->advertList();
	 	$list = M('ad_position')->select();
	 	$this->assign('list',$lists['list']);
	 	$this->assign('page',$lists['page']);
	 	$this->assign('position',$list);
	 	$this->assign('id',$id);
	 	$this->display();
	 }
	 
	 //广告列表
	 public function advertEdit(){
	 	if(IS_POST){
	 		$info = D('Advertisement')->advertEdit();
	 		if($info['status']==1){
	 			echo '<script>alert("'.$info['info'].'");window.location.href="'.$info['url'].'";</script>';
	 		}else{
	 			echo '<script>alert("'.$info['info'].'");</script>';
	 		}
	 		
	 	}
	 	$id = I('get.id');
	 	$data = D('Advertisement')->where('id = '.$id)->find();
	 	$position = M('ad_position')->select();
	 	$this->assign('position',$position);
	 	$this->assign('data',$data);
	 	$this->display();
	 }
	 public function advertPosition(){
	 	$list = M('ad_position')->select();
	 	$this->assign('list',$list);
	 	$this->display();
	 }
	 public function advertDel(){
	 	$id = I('get.id');
	 	$info = D('Advertisement')->advertDel($id);
	 	echo json_encode($info);
	 }
	 public function advertPositionDel(){
	 	$id = I('get.id');
	 	$info = D('Advertisement')->advertPositionDel($id);
	 	echo json_encode($info);
	 }
	 public function advertPositionEdit(){
	 	if(IS_POST){
	 		$info = D('Advertisement')->advertPositionEdit();
	 		echo json_encode($info);exit();
	 	}
	 	$id = I('get.id');
	 	$data = M('ad_position')->where('position_id = '.$id)->find();
	 	$this->assign('data',$data);
	 	$this->display();
	 }
	 public function advertUpdate(){
	 	echo json_encode(D('Advertisement')->advertUpdate());
	 }
	 public function freightDel(){
		$id = I('get.id');
	 	$info = M('freight_company')->where(array('id'=>$id))->delete();
	 	echo json_encode(array('status'=>$info));
	 }

}

