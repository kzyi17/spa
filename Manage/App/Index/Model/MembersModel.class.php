<?php
namespace Index\Model;
use Think\Model;

class MembersModel extends CommonModel{
	/**
	 * 会员列表
	 **/
	public function membersList($data=''){

		//列出会员
		//回收站条件
		$delete = $data['is_delete'];
		if(!empty($delete) && $delete =='1')
			$where='is_delete = 1';
		else//列出会员条件
			$where='is_delete = 0';
		if(I('post.keywords')){
			$where.=" AND (member_name = '".I('post.keywords','','trim')."' OR member_code = '".I('post.keywords','','trim')."' OR member_email = '".I('post.keywords','','trim')."' ) ";
		}
		if(I('post.mobile')){
			$where.=" AND (member_mobile = '".I('post.mobile','','trim')."' OR member_code = '".I('post.mobile','','trim')."' ) ";
		}
		if (I('post.starttime') || I('post.endtime')){
			$starttime=I('starttime').'00:00:00';
			$endtime=I('endtime').'23:59:59';
			if(I('starttime') == ""){
				$starttime=date('Y-m-d', 0);
			}
			if(I('endtime') == ""){
				$endtime=date("Y-m-d H:i:s");
			}
			$starttime_2=strtotime($starttime);
			$endtime_2=strtotime($endtime);
			$where.=" AND create_time between $starttime_2 and $endtime_2";
		}

		$count      = $this->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count, 30);  //载入分页类
		$show       = $Page->show();// 分页显示输出
		$list       = $this->where($where)->field('member_password',true)->order('member_id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key => $val){
			$Recommend = $this->where(array('member_id'=>$val['recommend_member_id']))->field('member_code')->find();
			$list[$key]['RecommendMemberCode'] = $Recommend['member_code'];   // 邀请人姓名
			$Level = M('members_level')->where(array('level_sn'=>$val['level_sn']))->field('level_name')->find();
			$list[$key]['levelName'] = $Level['level_name'];   // 邀请人姓名
			$list[$key]['distribution_level'] = D('DistributionLevel')->getLevel($val['my_points']);
		}
		$list_arr = array('list'=>$list, 'page'=>$show);
		return $list_arr;

	}
	/**
	 * 会员等级添加编辑
	 * @param string $data
	 * @author  haichao
	 */
	public function levelEdit($data=''){
		$M = M('members_level');
		/*添加验证*/
		if (empty($data["level_name"])) {
			return array('status' => 0, 'info' => "会员等级姓名不能为空哦");
		}

		if(!empty($data['level_id']) || intval($data['level_id']) > 0){

			$status1 = $M->where("`level_id`= ".$data['level_id'])->save($data);
		}
		else {
			$status2 = $M->add($data);
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('member/level'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('member/level'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('member/level'));
		}
		return $data;
	}
	/**
	 * 会员等级删除
	 **/
	public function levelDel($level_id){
		$data['is_delete'] = 1;
		return (M('members_level')->where("level_id = '{$level_id}'")->delete()) ? array('status' => 1, 'info' => '会员等级 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '会员  删除失败');
	}
	/**
	 * 会员删除
	 **/
	public function del($member_id){

		$M = D('Members');
		$data['is_delete'] = 1;
		$datas = $M->where("member_id = '{$member_id}'")->find();
		$status = $M->where("member_id = '{$member_id}'")->save($data);
		if($status){
			$sql = $M->getLastSql();
			$log = '会员'.$datas["member_code"].'移到回收站';
			$this->sql_log($log,$sql,4,4);
		}
		return ($status) ? array('status' => 1, 'info' => '会员 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '会员  删除失败');
	}
	/**
	 * 会员恢复
	 **/
	public function regain_member($member_id){

		$M = D('Members');
		$data['is_delete'] = 0;
		$datas = $M->where("member_id = '{$member_id}'")->find();
		$status = ($M->where("member_id = '{$member_id}'")->save($data));
		if($status){
			$sql = $M->getLastSql();
			$log = '会员'.$datas["member_code"].'恢复';
			$this->sql_log($log,$sql,4,5);
		}
		return $status ? array('status' => 1, 'info' => '会员 已经成功恢复', 'url' => "") : array('status' => 0, 'info' => '会员  删除恢复');
	}
	/**
	 * 回收站 会员删除
	 **/
	public function recycle_del($member_id){
		
		$M = M('Members');
		$data['is_delete'] = 1;
		$datas = $M->where("member_id = '{$member_id}'")->find();
		$status = $M->where("member_id = '{$member_id}'")->delete();
		if($status){
			$sql = $M->getLastSql();
			$log = '会员'.$datas["member_code"].'删除';
			$this->sql_log($log,$sql,4,2);
		}
		return ($status) ? array('status' => 1, 'info' => '会员 已经成功删除', 'url' => "") : array('status' => 0, 'info' => '会员  删除失败');
	}
	/**
	 * 会员信息
	 **/
	public function membersinfo($member_id){
		$M = M('Members');
		$data = $this->where("member_id = '{$member_id}'")->find();
		$Recommend = $this->where(array('member_id'=>$data['recommend_member_id']))->field('member_name')->find();
		$data['RecommendMemberName'] = $Recommend['member_name'];   // 邀请人姓名
		return $data;
	}
	/**
	 * 会员编辑
	 **/
	public function memberEdit($datas){
		$M = M('Members');
		/*添加验证*/
		
		/*会员密码*/
		if (empty($datas["member_password"])) {
			unset($datas["member_password"]);
		}else{
			$member_password = $datas["member_password"];
			$datas["member_password"] = encrypt($member_password);
		}
		if(!empty($datas['member_id']) || intval($datas['member_id']) > 0){
			$member_code = $this->where("`member_id`= ".$datas['member_id'])->getField('member_code');
			$status1 = $this->where("`member_id`= ".$datas['member_id'])->save($datas);
			if($status1){
				$sql = $M->getLastSql();
				$log = $member_code.'会员修改';
				$this->sql_log($log,$sql,4,1);
			}
		}
		else {
			$status2 = $this->add($datas);
			if($status2){
				$sql = $M->getLastSql();
				$log = '添加'.$datas["member_code"].'会员';
				$this->sql_log($log,$sql,4,3);
			}
		}

		if($status1!=0){
			return array('status' => 1, 'info' => "成功更新", 'url' => U('member/index'));
		}
		elseif($status2!=0){
			return array('status' => 1, 'info' => "添加成功", 'url' => U('member/index'));
		}
		else{
			return array('status' => 1, 'info' => "您未更新任何信息", 'url' => U('member/index'));
		}
		return $data;
	}
	
	/**
	 * 其他会员列表
	 **/
	public function getothermember($member_id){
		$list = D("Members")->field('member_id,member_name')->where("member_id != $member_id AND is_delete = 0")->select();
		return $list;
	}
	//会员购买信息* @author haichao
	public function shopDetail($memberId,$wheres=null){
		if($wheres['create_time'] || $wheres['time'])
			$where['create_time'] = $wheres['create_time'] ? $wheres['create_time'] : $wheres['time'];
		$memberData = $this->where('member_id = '.$memberId)->find();
		$where['member_id'] = $memberId;
		$where['pay_status'] = 1;
		$where['status'] = array(array('neq',3),array('neq',4),'and');
		$order = M('order')->where($where)->order('create_time')->select();
		$amount = 0;
		foreach($order as $val){
			$amount += $val['order_amount'];
		}
		$data['points'] = $memberData['my_points'];
		$data['count'] = count($order);
		$data['amount'] = $amount;
		$data['last_time'] = $order[(count($order)-1)]['create_time'];
		return $data;
	}
	

	/**
	 * 返利账户操作
	 * @author Muke
	 */
	public function accountDistribution($member_id,$amount,$note=''){
		//账户操作
		$this->where(array('member_id'=>$member_id));
		$this->setInc('distribution',$amount);
		//日志驱动
		D('accountLog')->log();
	
	}
	
	/**
	 * 获取推荐人信息
	 * @param int $member_id 用户ID
	 * @author Muke
	 */
	public function getRecommendInfo($member_id){
		if(!$member_id)
			return array();

		$recommend_member = $this->where("members.member_id=".$member_id)
								->join("members as re_members on members.recommend_member_id=re_members.member_id","INNER")
								->field("re_members.member_id,re_members.my_points as points,re_members.member_name")
								->find();
		if(!$recommend_member)
			return array();
		
		$level = D('DistributionLevel')->getLevel($recommend_member['points']);
		$recommend_member['level_id'] = $level['level_id'];
		$recommend_member['level_name'] = $level['level_name'];
		$recommend_member['level_discount'] = $level['discount'];
				
		return $recommend_member;
		
	}
	//我推荐的用户列表
	public function recommendMembersInfo($member_id){
		$list = $this->where('recommend_member_id ='.$member_id)->select();
		
		foreach($list as $key => $val){
			$amount = 0;
			$list[$key]['level_name'] = $this->getMemberLevel($val['member_id']);
			$memberData = M('distribution')->where(array('order_member_id'=>$val['member_id'],'member_id'=>$member_id))->field('order_amount,amount')->select();
			//dump($memberData);
			foreach($memberData as $k => $v){
				$amount += $v['amount'];
			}
			$list[$key]['amount'] = $amount;
		}
		return $list;
	
	}
	public function getMemberLevel($member_id){
		$member_points = M('Members')->where(array('member_id' => $member_id))->getField('my_points');
		$level =  M('distribution_level')->where(array('min_points'=>array('ELT',$member_points),array('max_points'=>array('EGT',$member_points))))->field('level_name')->find();
		return $level['level_name'];
	}
	/**
	 * 会员统计
	 * @param int $type 0分页，1全部（用于导入）
	 * @author haichao
	 */
	public function statistics($type='0'){
		if(I('member_code')!=null && I('member_code') !=''){
			$member_code = I('member_code');
			$where['member_code'] = array('like',"$member_code");
		}
		if(I('starttime')!=null && I('starttime') !=''){
			$wheres['time'] = array('gt',strtotime(I('starttime')));
		}
		if(I('endtime')!=null && I('endtime') !=''){
			$wheres['time'] = array('lt',strtotime(I('endtime')));
		}
		if(I('starttime')!='' && I('endtime') !=''){
			$wheres['time'] = array(array('gt',strtotime(I('starttime'))),array('lt',strtotime(I('endtime'))),'and');
		}
		if(I('level_sn')!=''){
			$level = M('distribution_level')->where('level_id = '.I('level_sn'))->find();
			if($level){
				$where['my_points'] = array(array('gt',$level['min_points']),array('lt',$level['max_points']),'and');
			}
		}
		$where['is_delete'] = 0;
		if($type=='0'){
			$count      = $this->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count, 30);  //载入分页类
			$show       = $Page->show();// 分页显示输出
			$list       = $this->where($where)->field('member_id,member_code,create_time')->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$list       = $this->where($where)->field('member_id,member_code,create_time')->order('create_time DESC')->select();
		}	
		foreach($list as $key => $val){
			$list[$key]['level'] = $this->getMemberLevel($val['member_id']);
			$list[$key]['recommendMembers'] = count($this->recommendMembersInfo($val['member_id']));
			$shopDetail = $this->shopDetail($val['member_id'],$wheres);
			$list[$key]['amount'] = $shopDetail['amount'];
			$list[$key]['count'] = $shopDetail['count'];
			$list[$key]['last_time'] = $shopDetail['last_time'];
			$list[$key]['distribution_amount'] = $this->getdistribution($val['member_id'],2,$wheres);
			
			$list[$key]['liveness'] = (floatval($list[$key]['amount']) + floatval($list[$key]['distribution_amount']))/1000;
		}
		$list_arr = array('list'=>$list, 'page'=>$show);
		return $list_arr;
	}
	//获取返利总金额
	public function getdistribution($member_id,$status=2,$wheres=null){
		if($wheres['create_time'] || $wheres['time'])
			$where['time'] = $wheres['create_time'] ? $wheres['create_time'] : $wheres['time'];
		$where['member_id'] = $member_id;
		$where['status'] = $status;
		$list = M('distribution')->where($where)->field('amount')->select();
		foreach($list as $k => $v){
			$amount += $v['amount'];
		}
		return $amount ? $amount : 0;
	}
	//时间段内 的生日列表
	public function memberbirthdayList($lag){
		$lag =$lag ? $lag: 7;
		$current_date=getdate();
		$day = $current_date['mday'];
		$month = $current_date['mon'];
		$year = $current_date['year'];
		$con = array();
		$j=0;
		$days = D('Market')->getday_count($month,$current_date['year']);
		for($i=$day;$i<$day+$lag;$i++){
			if($i<=$days){
				$m = $this->formatday($month);
				if($i<10)
					$con[] =array('like',"%-$m-0$i%");
				else
					$con[] =array('like',"%-$m-$i%");
				$j++;
			}else{
				break;
			}
		}
		if($j<$lag){
			if($month==12){
				$month=1;
				$year = $year+1;
				$myyear = $year;
				$day = D('Market')->getday_count($month,$year);
			}else{
				$month = $month+1;
				$mymonth = $month;
				$day = D('Market')->getday_count($month,$year);
			}
			for($t=1;$t<$lag-$j;$t++){
				$m = $this->formatday($month);
				if($t<10)
					$con[] =array('like',"%-$m-0$t%");
				else
					$con[] =array('like',"%-$m-$t%");
				$myday = $lag-$j;
			}
		}
		$con[] = 'or';
		$where['birthday'] = $con;
		$list = D('Members')->where($where)->field('member_code,member_name,birthday,my_points')->order('birthday')->select();
		foreach($list as $key => $val){
			$arr = explode('-',$val['birthday']);
			$list[$key]['time'] = $year.'/'.$arr[1].'/'.$arr[2].' 00:00:00';
			$list[$key]['years_old'] = $year - $arr[0];
			$list[$key]['distribution_level'] = D('DistributionLevel')->getLevel($val['my_points']);
		}
		//print_r($list);
		return $list;
	}
	//处理月份，保证月份的位数为二
	public function formatday($a){
		if($a<10){
			return '0'.intval($a);
		}else{
			return intval($a);
		}
	}
	//增减积分
	public function updatePoints($member_id,$points,$type,$note=''){
		if($type==1){
			$result = D('Members')->where('member_id = '.$member_id)->setInc('my_points',intval($points));
			$type=1;
			$title = '增加积分';
			$val = 7;
		}else{
			$result = D('Members')->where('member_id = '.$member_id)->setDec('my_points',intval($points));
			$type=2;
			$title = '降低积分';
			$val = 6;
		}
		$sql = D('Members')->getLastSql();
		$data['member_id'] = $member_id;
		$member_code = D('Members')->where('member_id = '.$member_id)->getField('member_code');
		$data['type'] = $type;
		$data['value'] = abs($points);
		$data['note'] = $note;
		$data['time'] = time();
		M('points_log')->add($data);
		$log = $title.$member_code.'的积分：'.$data['value'].'（备注：'.$note.'）';
		$this->sql_log($log,$sql,4,$val);
		$points = D('Members')->where('member_id = '.$member_id)->getField('my_points');
		return ($result ? (array('status'=>1,'info'=>'修改积分成功','url'=>'','points'=>$points)) : (array('status'=>0,'info'=>'修改积分失败','url'=>'')));
	}
	
}