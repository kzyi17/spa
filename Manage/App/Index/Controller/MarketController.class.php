<?php
namespace Index\Controller;
use Index\Controller\CommonController;

class MarketController extends CommonController{
	
	 /**
	  * 会员统计
	  *
	  * @author  haichao
	  */
	 public function index(){
		$lists = D('Members')->statistics();
		$levelList = M('distribution_level')->select();
		$search = I('post.');
		if(IS_POST){
			if($search['type']==0){
				$this->csv();exit();
			}
		}
		$starttime = I('starttime');
		$endtime = I('endtime');
		$this->assign('list',$lists['list']);
		$this->assign('page',$lists['page']);
		$this->assign('levelList',$levelList);
		$this->assign('search',$search);
	 	$this->display();
	 }
	 /**
	  * 导入csv
	  *
	  * @author  haichao
	  */
	 public function csv(){
		$search = I('post.search');
		
		// create a simple 2-dimensional array
		$data = D('Members')->statistics(1);
		if(I('starttime')!=null && I('starttime') !=''){
			$time = (I('starttime'));
		}
		if(I('endtime')!=null && I('endtime') !=''){
			$time = (I('endtime'));
		}
		if(I('starttime')!='' && I('endtime') !=''){
			$time = I('starttime').'--'.I('endtime');
		}
		if(I('level_sn')!=''){
			$level = M('distribution_level')->where('level_id = '.I('level_sn'))->find();
			$title = $level ? C('name').$level['level_name']:C('name').'全部分销商';
		}else{
			$title = C('name').'全部分销商';
		}
		if($time){
			$title.=$time;
		}
		$sql = D('Members')->getLastSql();
		$datas['log'] = '导出分销商统计'.$title;
		$datas['ip'] = get_client_ip();
		$adminInfo = session('UserInfo');
		$datas['admin_id'] = $adminInfo['uid']? $adminInfo['uid'] : 0;
		$datas['create_time'] = time();
		$datas['sql_log'] = $sql;
		$datas['log_type'] = 5;
		$datas['log_type_val'] = 2;
		M('admin_log')->add($datas);
		import("Common.Extend.Excel_XML");
		$list = array();
		$list[0]['member_code'] = '分销商';
		$list[0]['create_time'] = '注册时间';
		$list[0]['level'] = '分销等级';
		$list[0]['recommendMembers'] = '推荐的分销商数量';
		$list[0]['amount'] = '消费的金额';
		$list[0]['count'] = '交易次数';
		$list[0]['last_time'] = '最后一次交易记录';
		$list[0]['distribution_amount'] = '返利成功金额';
		$i=1;
		foreach($data['list'] as $key => $val){
			$list[$i]['member_code'] = $val['member_code'];
			$list[$i]['create_time'] = date('Y-m-d H-i-s',$val['create_time']);
			$list[$i]['level'] = $val['level'];
			$list[$i]['recommendMembers'] = $val['recommendMembers'];
			$list[$i]['amount'] = $val['amount'];
			$list[$i]['count'] = $val['count'];
			$list[$i]['last_time'] = $val['last_time'];
			$list[$i]['distribution_amount'] = $val['distribution_amount'];
			$i++;
		}
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($list);
		$xls->generateXML($title);
	 }

	//近期生日会员统计
	public function members(){
		
		$lock_count = D('Market')->members_count(array('is_lock'=>1));
		$lock_delete = D('Market')->members_count(array('is_delete'=>1));
		
		$memberbirthday = D('Members')->memberbirthdayList();
		$this->assign('lock_count',$lock_count);
		
		$this->assign('lock_delete',$lock_delete);
		
		if(IS_POST){
			$lag = I('post.lag');
			$memberbirthday = D('Members')->memberbirthdayList($lag);
		}
		$member_count = D('Market')->members_count();
		$this->assign('member_count',$member_count);
		$this->assign('lag',$lag);
		$this->assign('list',$memberbirthday);
		$this->display();
	}
	//消费统计
	public function consume(){
		$order_amounts = D('Market')->order_amounts();
		$member_consume = D('Market')->member_consume();
		$this->assign('order_amounts',$order_amounts);
		$this->assign('member_consume',$member_consume);
		$this->display();
	}
	//订单统计
	public function detail(){
		$list = D('Market')->detail();
		$type = I('post.type') ? I('post.type') : 'day';
		$starttime = I('post.starttime') ? I('post.starttime') : '';
		$endtime = I('post.endtime') ? I('post.endtime') : '';
		$this->assign('list',$list);
		$this->assign('type',$type);
		$this->assign('starttime',$starttime);
		$this->assign('endtime',$endtime);
		$this->display();
	}
	//
	public function getjson(){
		$type = I('get.type');
		$starttime = strtotime(I('get.starttime'));
		$endtime = strtotime(I('get.endtime'));
		if($starttime!='' && $endtime!=''){
			$where['create_time'] = array('between',array($starttime,$endtime));
		}else{
			if($starttime!=''){
				$where['create_time'] = array('gt',$starttime);
			}
			if($endtime!=''){
				$where['create_time'] = array('lt',$endtime);
			}
		}
		echo json_encode(D('Market')->$type($where));
	}
	//今天的订单统计
	public function todayOrderList(){
		$current_date=getdate();
		$time = strtotime($current_date['year'].'-'.$current_date['mon'].'-'.$current_date['mday']);
		$where['create_time'] = array('gt',$time);
		$orderList = D('Order')->where($where)->select();
		$this->assign('list',$orderList);
		$this->display();
	}
	
	public function member_consume(){
		$memberLevel = D('DistributionLevel')->order('min_points')->select();
		
		$levelCount = count($memberLevel);
		$list = D('Market')->memberList();
		$this->assign('list',$list);
		$this->assign('memberLevel',$memberLevel);
		$this->assign('levelCount',$levelCount);
		$this->assign('g',1409134132);
		$this->display();
	}
	public function adminLog(){
		$typeList = C('LOG');
		$lists = D('AdminLog')->getList();
		$this->assign('typeList',$typeList);
		$this->assign('list',$lists['list']);
		$this->assign('page',$lists['page']);
		$search['starttime'] = I('starttime');
		$search['endtime'] = I('endtime');
		$search['log_type'] = I('log_type');
		$search['log_type_val'] = I('log_type_val');
		$this->assign('search',$search);
		$this->display();
	}
	public function adminLogDelAll(){
		$Members = D("Members");
		$ids = I('get.del_id');
		$ids = explode(',',$ids);
		for($i=0;$i<count($ids);$i++){
			$arry = D('AdminLog')->del($ids[$i]);
		}
		echo json_encode($arry);
	}
	public function logTypeValList(){
		$type = I('type');
		$typeList = C('LOG');
		$list = $typeList[$type]['type'];
		echo json_encode($list);
	}
	//日志删除
	public function admindel(){
		$id = I('id');
		echo json_encode(D('AdminLog')->del($id));
	}
	public function test(){
		$where['create_time'] = array('between',array(1406822400,1409500800));
		dump(count(D('Members')->where($where)->select()));
	}
}