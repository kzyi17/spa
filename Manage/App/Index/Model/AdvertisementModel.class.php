<?php
namespace Index\Model;
use Think\Model;
class AdvertisementModel extends Model {
	
	public function advertList(){
		$where['is_del'] = 0;
		$id = I('get.id');
		if($id>0){
			$where['position_id'] = $id;
		}
		$count      = $this->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,C('PAGE_SIZE'));  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($where)->order('start_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$adPosition = M('ad_position')->where('position_id = '.$val['position_id'])->find();
			$list[$key]['position_name'] = $adPosition['position_name'];
		}
		$lists = array('list'=>$list, 'page'=>$show);
		return $lists;
	}
	public function advertEdit(){
		$data = I('post.data');
		$data['start_time'] =  strtotime($data['start_time']);
		$data['end_time'] =  strtotime($data['end_time']);
		if($data['type']=='1' && $_FILES['photo']['error']<=0){
			import('ORG.Net.UploadFile');
			$config = array(
					'maxSize' => C('upload_maxSize'),   // 设置附件上传大小
					'exts'    => C('upload_exts'),    // 设置附件上传类型
					'rootPath'=> C('upload_rootPath').'uploads/advert/',  // 设置附件上传目录
					'autoSub'=> false,
					
			);
			$upload = new \Think\Upload($config);;// 实例化上传类
			
			$info   =   $upload->upload();
			$data['content'] = 'uploads/advert/'.$info['photo']['savename'];
		}else if($data['type'] =='2'){
			$data['content'] = I('post.content');
		}else{
			
		}
		/*添加验证*/
		if (empty($data["ad_name"])) {
			return array('status' => 0, 'info' => "广告名称不能为空哦");
		}
		
		if(!empty($data['id']) || intval($data['id']) > 0){
			$list = $this->where("`id`= ".$data['id'])->field('type,content')->find();
			if($list['type']=='1'){
				if((file_exists(C('upload_rootPath').$list['content'])  && $info) || $data['type']=='2'){
					unlink(C('upload_rootPath').$list['content']);
				}
			}
			
			$status1 = $this->where("`id`= ".$data['id'])->save($data);
		}
		else {
			$data['click_count'] = 0;
			$status2 = $this->add($data);
		}
		
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('System/advertList'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('System/advertList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/advertList'));
		}
		return $data;
	}
	
	public function advertPositionEdit(){
		$data = I('post.data');
		/*添加验证*/
		if (empty($data["position_name"])) {
			return array('status' => 0, 'info' => "广告位置不能为空哦");
		}
		
		if(!empty($data['position_id']) || intval($data['position_id']) > 0){
			
			$status1 = M('ad_position')->where("`position_id`= ".$data['position_id'])->save($data);
		}
		else {
			unset($data['position_id']);
			$status2 = M('ad_position')->add($data);
		}
		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('System/advertPosition'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('System/advertPosition'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/advertPosition'));
		}
		return $data;
	}
	public function advertDel($id){
		$list = $this->where("`id`= ".$id)->field('type,content')->find();
		if($list['type']=='1'){
			if((file_exists(C('upload_rootPath').$list['content']))){
				unlink(C('upload_rootPath').$list['content']);
			}
		}
		$status = $this->where("`id`= ".$id)->delete();
		if($status!=0){
			return array('status' => 1, 'info' => "删除成功", 'url' => U('System/advertList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/advertList'));
		}
		
	}
	public function advertPositionDel($id){
		$status = M('ad_position')->where("`position_id`= ".$id)->delete();
		if($status!=0){
			return array('status' => 1, 'info' => "删除成功", 'url' => U('System/advertList'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('System/advertList'));
		}
	
	}
	public function  advertupdate(){
		$id = I('get.id');
		if($id >0){
			$value = I('get.value');
			$name = I('get.name');
			$data[$name] = $value;
			$lastId = $this->where("id='{$id}'")->save($data);
			return ($lastId?array('status' =>1,'info' => '修改成功'):array('status' =>0,'info' => '修改失败'));
			return (array('status' =>0,'area_id' => 0,'info'=>'已存在地区名称'));
		
		}
		return (array('status' =>0,'id' => $id,'info'=>'更新失败'));
	}
	
	
}