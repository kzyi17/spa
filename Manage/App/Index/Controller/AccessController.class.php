<?php 
namespace Index\Controller;
use Index\Controller\CommonController;

class AccessController extends CommonController{
	
	public function index(){
		
		$this->display();
	}
	
	public function group(){
		
		$this->display();
	}
	
	
	public function groupEdit(){ 
		
		$M = D('AuthGroup');
		if (IS_POST) {
		
			echo json_encode($M->groupEdit());
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
	
	
}

?>