<?php
namespace Index\Controller;
use Think\Controller;

class MemberController extends CommonController{

	/**
	 * 会员列表
	 */
	public function index(){

		$members = D("Members")->membersList();
		$this->assign("page",   $members['page']);
		$this->assign("list",   $members['list']);
		$this->assign("keywords",I("post.keywords"));
		$this->assign("mobile",I("post.mobile"));
		$this->assign("starttime",I("post.starttime"));
		$this->assign("endtime",I("post.endtime"));
		$this->assign("today",date("Y-m-d"));
		$this->display();

	}
	/**
	 * 会员编辑
	 *
	 * @author  haichao
	 */
	public function memberEdit(){

		$member_id = I("get.id");

    	if (IS_POST) {
			$data = I('data');
    		echo json_encode(D("Members")->memberEdit($data));
    	} else {
			$members = D("Members")->membersinfo($member_id);
			$level = D("DistributionLevel")->getLevel($members['my_points']);
			$members['level_sn'] = $level['level_sn'];
			$list = D("DistributionLevel")->select();
			$this->assign("data",   $members);
			$this->assign("list",   $list);
			$this->display();
		}
	}
	/**
	 * 会员等级类别
	 *
	 * @author  haichao
	 */
	public function level(){
		$list = M("members_level")->select();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 会员等级编辑
	 */
	public function levelEdit(){
		$level_id = I("get.id");

		if (IS_POST) {
			$data = I('data');
			echo json_encode(D("Members")->levelEdit($data));
		} else {
			$data = $level = M("members_level")->where("level_id = ".$level_id)->find();
			$this->assign("data",   $data);
			$this->display();
		}
	}
	/**
	 * 会员等级删除
	 **/
	public function levelDel(){
		$Members = D("Members");
		$level_id = I("get.id");
		echo json_encode($Members->levelDel($level_id));
	}
	/**
	 * 会员等级批量删除
	 **/
	public function levelDelAll(){
		$Members = D("Members");
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = $Members->levelDel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	/**
	 * 会员删除
	 **/
	public function memberDel(){
		$Members = D("Members");
		$member_id = I("get.id");
		echo json_encode($Members->del($member_id));
	}
	/**
	 * 会员批量删除
	 **/
	public function memberDelAll(){
		$Members = D("Members");
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = $Members->del($ids[$i]);
		}
		echo json_encode($arry);exit;

	}
	/**
	 * 回收站 会员批量恢复
	 **/
	public function memberRecycleRegain(){
		$Members = D("Members");
		$id = I('get.id');
		echo json_encode($Members->regain_member($id));

	}
	/**
	 * 回收站 会员批量恢复
	 **/
	public function memberRecycleDel(){
		$Members = D("Members");
		$id = I('get.id');
		echo json_encode($Members->recycle_del($id));

	}
	/**
	 * 回收站列表
	 **/
	 public function recycle(){
		$members = D("Members")->membersList(array('is_delete'=>1));
		$this->assign("page",   $members['page']);
		$this->assign("list",   $members['list']);
		$this->assign("keywords",I("post.keywords"));
		$this->assign("starttime",I("post.starttime"));
		$this->assign("endtime",I("post.endtime"));
		$this->assign("today",date("Y-m-d"));
		$this->display('index');
	 }
	
	 /**
	  * 积分明细
	  *
	  * @author  homter
	  */
	 public function points(){

	 	$this->display();
	 }
	 /**
	  * 分销等级
	  * @author homter
	  */
	 public function disLevel(){
	 	$list = M("distribution_level")->select(); 
	 	$this->assign('list',$list);
	 	$this->display('disLevel');
	 }

	 /**
	  * 分销等级编辑
	  * @author haichao
	  */
	 public function disLevelEdit(){
		if(IS_POST){
			echo json_encode(D('Distribution')->disLevelEdit());exit();
		}
		$id = I('get.id',0,'intval');
		$data = M("distribution_level")->where(array('level_id'=>$id))->find(); 
	 	$this->assign('data',$data);
	 	$this->display('disLevelEdit');
	 }
	 /**
	  * 分销等级删除
	  * @author haichao
	  */
	 public function disLevelDel(){
		$id = I('get.id');
		echo json_encode(D('Distribution')->disLevelDel($id));exit();
	 }
	 /**
	  * 分销等级批量删除
	  * @author haichao
	  */
	 public function dislevelDelAll(){
		
		$ids = array();
		//$datas   = I('data');
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('Distribution')->disLevelDel($ids[$i]);
		}
		echo json_encode($arry);exit;
	 }
	 /**
	  * 预付款管理
	  * @author Muke
	  */
	 public function balance(){
	 	$members = I('check');//获取需操作的用户
	 	$this->assign('members',$members);
	 	$this->display();
	 }
	 /**
	  * 预付款记录
	  * @author haichao
	  */
	 public function balanceShow(){
	 	$id = I('id');
	 	$list = D('Account')->accountList($id);
	 	$this->assign('list',$list);
	 	$this->display();
	 	
	 }
	 /**
	  * 会员推荐的用户列表
	  * @author haichao
	  */
	 public function myRecommend(){
	 	$id = I('id');
	 	$list = D('Members')->recommendMembersInfo($id);
	 	//dump($list);
	 	$this->assign('list',$list);
	 	$this->assign('id',$id);
	 	$this->display();
	 }
	 /**
	  * 预付款批量处理
	  * @author Muke
	  */
	 public function member_recharge(){
	 	$members = I('check');//获取需操作的用户
	 	if(!$members){
	 		$data['msg'] = '';
	 		exit(json_encode(array('flag' => 0,'msg' => '请选择要操作的用户')));
	 	}
	 	 
	 	$amount =  I('amount'); //获取金额
		$type =  I('type'); //获取金额
	 	//充值模型
	 	foreach ($members as $v){
			if($type==1){
				D('account')->addAccount($v,$amount);
			}else{
				D('account')->reduceAccount($v,$amount);
			}
	 	}
	 	
	 	exit(json_encode(array('flag' => 1,'msg' => '充值成功')));
	 }
	 
	 /**
	  * 返利管理
	  * @author Muke
	  */
	 public function distribution(){
	 	
	 	$list = D('distribution')->getList();
	 	
	 	$this->assign('list',$list);
	 	$this->display();
	 }
	 public function distributionShow(){
	 	$member_id = I('get.member_id');
	 	$order_member_id = I('get.order_member_id');
	 	$recommendMemberData = D('Members')->where(array('member_id = '.$member_id))->field('member_code')->find();
	 	$memberData = D('Members')->where(array('member_id = '.$order_member_id))->field('member_code')->find();
	 	$list = D('distribution')->getList();
	 	$this->assign('recommend_member',$recommendMemberData['member_code']);
	 	$this->assign('member',$memberData['member_code']);
	 	$this->assign('list',$list);
	 	$this->display();
	 }
	 /**
	  * 用户升降积分操作
	  * @author haichao
	  */
	 public function points_manage(){
		$member_id = I('get.id');
		if(IS_POST){
			$points = I('post.points','0','intval');
			$type = I('post.type');
			$note = I('post.note');
			$member_id = I('post.id');
			echo json_encode(D('Members')->updatePoints($member_id,$points,$type,$note));exit();
		}
		$data = D('Members')->where(array('member_id = '.$member_id))->field('member_code,my_points')->find();
		$list = D('DistributionLevel')->select();
		$level = D('DistributionLevel')->getLevel($data['my_points']);
		$data['level_name'] = $level['level_name'];
		$this->assign('data',$data);
		$this->assign('list',$list);
		$this->display();
	 }
	 /**
	  * 分销等级
	  * @author haichao
	  */
	 public function getDistributionLevel(){
		$points = I('points','0','intval');
		$level = D('DistributionLevel')->getLevel($points);
		echo json_encode($level['level_name']);
	 }
	 /**
	  * 提现管理
	  * @author haichao
	  */
	 public function withdraw(){
		$lists = D('Withdraw')->getList();
		$this->assign('page',$lists['page']);
		$this->assign('list',$lists['list']);
		$this->display();
	 }
	 /**
	  * 提现管理删除
	  * @author haichao
	  */
	 public function withdrawDel(){
		$id = I('get.id');
		echo json_encode(D('Withdraw')->withdrawDel($id));exit();
	 }
	 /**
	 * 会员等级批量删除
	 **/
	public function withdrawDelAll(){
		$Members = D("Withdraw");
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('Withdraw')->withdrawDel($ids[$i]);
		}
		echo json_encode($arry);exit;
	}
	 /**
	  * 提现管理处理
	  * @author haichao
	  */
	 public function withdrawEdit(){
		if(IS_POST){
			$data['status'] = I('post.status','0','intval');
			$data['re_note'] = I('post.re_note');
			if($data['status']==-1){
				$title = '失败';
			}else if($data['status']==0){
				$title = '未处理';
			}else if($data['status']==1){
				$title = '处理中';
			}else{
				$title = '成功';
			}
			$id = I('post.id');
			$datas = D('Withdraw')->where('id = '.$id)->find();
			$member_code = M('Members')->where(array('member_id'=>$datas['member_id']))->getField('member_code');
			D('Withdraw')->where('id = '.$id)->save($data);
			$sql = D('Withdraw')->getLastSql();
			$log = '处理会员'.$member_code.'提现申请状态，更改为（'.$title.'）';
			$this->sql_log($log,$sql,4,15);
			
			
			echo json_encode(array('flag'=>1,'info'=>'处理成功','url'=>U('Member/withdraw')));exit();
		}
		$id = I('get.id');
		$data = D('Withdraw')->where('id = '.$id)->find();
		$data['member_code'] = M('Members')->where(array('member_id'=>$data['member_id']))->getField('member_code');
		$data['my_distribution'] = M('Members')->where(array('member_id'=>$data['member_id']))->getField('my_distribution');
		$data['au_distribution'] = D('distribution')->getAudit($data['member_id']);
		$this->assign('data',$data);
		$this->display();
	 }
	 public function test(){
	 	$model = M('distribution');
	 	$result = $model->where('id=1')->setInc('order_amount',2.20);
	 	
	 	dump($result);
	 	
	 }

}