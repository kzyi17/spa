<?php
namespace Index\Model;
use Think\Model;
class ProductSpecModel extends Model{
	/**
	 * 规格列表
	 * @return multitype:string Ambigous <string, \Think\mixed, boolean, NULL, unknown, multitype:>
	 */
	public function specList(){
		
		$count = $this->where("`status`=1")->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, 25);  //载入分页类   
		$showPage = $page->show();
		$list = $this->where("`status`=1")->field('note,status',true)->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['spec_value']  = json_decode($v['spec_value']);
			$list[$k]['specTypeTxt'] = $v['spec_type'] == 1 ? '文字' : '图片'; 
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);
		return $result;
	}
	
	
	public function specEdit(){
		$datas = array();		
		$datas   = I('data');			    
		
		/*添加验证*/
		if (empty($datas["spec_name"])) {
			return array('status' => 0, 'info' => "规格名称不能为空哦");
		}
		/*显示类型--文字  需填写规格值*/
		if($datas["spec_type"] == 1){
			if (empty($datas["spec_value"]) || $datas["spec_value"] === null) {
				return array('status' => 0, 'info' => "请设置规格值哦～～");
			}
		}
		else{
			if (empty($datas["spec_value"]) || $datas["spec_value"] === null) {
				return array('status' => 0, 'info' => "请选择图片上传哦～～");
			}
		}
		
		$datas['spec_value'] = json_encode($datas['spec_value']);
		if(!empty($datas['spec_id']) || intval($datas['spec_id']) > 0){
			$status1 = $this->where("`spec_id`= ".$datas['spec_id'])->save($datas);
		}
		else {
			$status2 = $this->add($datas);		
		}
		
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U(''));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U(''));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息");
		}
	}
	/**
	 * 规格删除
	 */
	public function specDel($spec_id = 0){
		$data['status'] = '0';
		return $this->where("spec_id =" . $spec_id)->save($data);
	}
	
	
	/**
	 * 回收站规格列表
	 * @return multitype:string Ambigous <string, \Think\mixed, boolean, NULL, unknown, multitype:>
	 */
	public function specRecycleList(){
		
		$count = $this->where("`status`=0")->count();// 查询满足要求的总记录数
		$page = new \Think\Page($count, 25);  //载入分页类   
		$showPage = $page->show();
		$list = $this->where("`status`=0")->field('note,status',true)->limit("$page->firstRow, $page->listRows")->select();
		foreach ($list as $k => $v) {
			$list[$k]['spec_value']  = json_decode($v['spec_value']);
			$list[$k]['specTypeTxt'] = $v['spec_type'] == 1 ? '文字' : '图片'; 
		}
		$result = array('list'=>$list, 'showPage'=>$showPage);
		return $result;
	}
	
	/**
	 * 回收站商品规格恢复
	 */
	public function regain_spec($spec_id = 0){
		$data['status'] = '1';
		return $this->where("spec_id =" . $spec_id)->save($data);
	}
	/**
	 * 回收站商品规格删除
	 */
	public function specRecycleDel($spec_id = 0){
		$data['status'] = '0';
		$data['spec_id'] = $spec_id;
		return $this->where($data)->delete();
	}
	
}