<?php
namespace Index\Model;
use Think\Model;

/**
 * 分销等级模型
 * @author haichao
 *
 */
class MarketModel extends  Model{
	
	/**
	 * 会员数量统计
	 * @author haichao
	 * @param int $point
	 */
	public function members_count($where){
		if(is_array($where) && $where!=null){
			$list = D('Members')->where($where)->count();
		}else{
			$list = D('Members')->count();
		}
		return $list ? $list :0;
	}
	//订单费用统计
	public function order_amounts($where){
		if(is_array($where) && $where!=null){
			$list = D('Order')->where($where)->field('order_amount')->select();
		}else{
			$list = D('Order')->field('order_amount')->select();
		}
		$order_amounts = 0;
		foreach($list as $val){
			$order_amounts += floatval($val['order_amount']);
		}
		return $order_amounts ? $order_amounts :0;
	}
	public function member_consume($where){
		$order_amounts  = $this->order_amounts($where);
		$members_count  = $this->members_count($where);
		return $order_amounts/$members_count ? $order_amounts/$members_count :0;
	}
	/**
	 * 订单统计
	 * @author haichao
	 * @param int $point
	 */
	public function detail(){
		$type = I('post.type') ? I('post.type') : 'day';
		$current_date=getdate();
		$arr = array();
		$where = array();
		$j = 0;
		if($type=='day'){
			$unit = 86400;
			$time = I('post.endtime') ? (strtotime(I('post.endtime'))+86400) :time();
			$mystarttime = strtotime($current_date['year'].'-'.$current_date['mon']);
			$starttime = I('post.starttime') ? strtotime(I('post.starttime')) :$mystarttime;
			
			for($i=$starttime;$i<$time;$i=$i+$unit){
				$where = null;
				$where = array();
				$arr[$j]['time'] = date('Y-m-d',$i);
				$where['create_time'] = array('between',array($i,$i+$unit));
				
				//$arr[$j]['member_count'] = $this->getOrderMembers($where);
				$arr[$j]['order_count'] = count(D('Order')->where($where)->select());
				//print_r($where);
				$where['status'] = array(array('neq',3),array('neq',4),'and');
				$arr[$j]['amount'] = $this->order_amounts($where);
				$arr[$j]['valid_order_count'] = count(D('Order')->where($where)->select());
				$arr[$j]['no_order_count'] = $arr[$j]['order_count'] - $arr[$j]['valid_order_count'];
				$arr[$j]['member_consume'] = floatval($arr[$j]['amount'])/floatval($arr[$j]['valid_order_count']) ? floatval($arr[$j]['amount'])/floatval($arr[$j]['valid_order_count']) :0;
				$where['pay_status'] = 1;
				
				$arr[$j]['pay_amount'] = $this->order_amounts($where);
				//print_r($where);
				$where['pay_status'] = 0;
				$arr[$j]['no_pay_amount'] = $this->order_amounts($where);
				$where['pay_status'] = 2;
				$arr[$j]['refund_amount'] = $this->order_amounts($where);
				//print_r($where);
				$j++;
			}
		}else if($type=='mon'){
			for($m=1;$m<=12;$m++){
				$days = $this->getday_count($m,$current_date['year']);
				$unit[$m] = $days * 86400;
			}
			$where = array();
			$endtime = strtotime($current_date['year'].'-'.($current_date['mon']));
			$time = I('post.endtime') ? (strtotime(I('post.endtime'))) :$endtime;
			$mystarttime = strtotime($current_date['year'].'-'.'1');
			
			$starttime = I('post.starttime') ? strtotime(I('post.starttime')) :$mystarttime;
			$i=$starttime;
			while($i<=$time){
				$arr[$j]['time'] = date('Y-m',$i);
				$current_date=getdate($i);
				$artime = $unit[$current_date['mon']];
				$where['create_time'] = array('between',array($i,$i+$artime));
				$arr[$j]['amount'] = $this->order_amounts($where);
				$arr[$j]['order_count'] = count(D('Order')->where($where)->select());
				$arr[$j]['member_count'] = $this->getOrderMembers($where);
				$arr[$j]['member_consume'] = floatval($arr[$j]['amount'])/floatval($arr[$j]['member_count']) ? floatval($arr[$j]['amount'])/floatval($arr[$j]['member_count']) :0;
				$j++;
				$i=$i+$artime;
			}
			
		}else{
			
		}
		
		return $arr;
	}
	//注册会员
	public function memberList(){
		$type = I('post.type') ? I('post.type') : 'day';
		$current_date=getdate();
		$memberLevel = D('DistributionLevel')->order('min_points')->select();
		$arr = array();
		$where = array();
		$j = 0;
		if($type=='day'){
			$unit = 86400;
			$time = I('post.endtime') ? (strtotime(I('post.endtime'))+86400) :time();
			$mystarttime = strtotime($current_date['year'].'-'.$current_date['mon']);
			$starttime = I('post.starttime') ? strtotime(I('post.starttime')) :$mystarttime;
			
			for($i=$starttime;$i<$time;$i=$i+$unit){
				$where = null;
				$where = array();
				$arr[$j]['time'] = date('Y-m-d',$i);
				$where['create_time'] = array('between',array($i,$i+$unit));
				$arr[$j]['start_time'] =$where['create_time'][1][0];
				$arr[$j]['end_time'] =$where['create_time'][1][1];
				$arr[$j]['count'] = count(D('Members')->where($where)->select());
				$count = 0;
				foreach($memberLevel as $key =>$val){
					$where['my_points'] = array('between',array($val['min_points'],$val['max_points']));
					$curcount = count(D('Members')->where($where)->select());
					$arr[$j]['level_'.$val['level_id']] = $curcount;
					$count = $count + intval($curcount);
				}
				$arr[$j]['min_level'] = $arr[$j]['count'] - $count;
				$where['is_lock'] = 1;
				$arr[$j]['lock'] = count(D('Members')->where($where)->select());
				$j++;
			}
		}else if($type=='mon'){
			for($m=1;$m<=12;$m++){
				$days = $this->getday_count($m,$current_date['year']);
				$unit[$m] = $days * 86400;
			}
			
			$endtime = strtotime($current_date['year'].'-'.($current_date['mon']));
			$time = I('post.endtime') ? (strtotime(I('post.endtime'))) :$endtime;
			$mystarttime = strtotime($current_date['year'].'-'.'1');
			
			$starttime = I('post.starttime') ? strtotime(I('post.starttime')) :$mystarttime;
			$i=$starttime;
			while($i<=$time){
				$where = array();
				$arr[$j]['time'] = date('Y-m',$i);
				$current_date=getdate($i);
				$artime = $unit[$current_date['mon']];
				$where['create_time'] = array('between',array($i,$i+$artime));
				$arr[$j]['start_time'] =$where['create_time'][1][0];
				$arr[$j]['end_time'] =$where['create_time'][1][1];
				$arr[$j]['count'] = count(D('Members')->where($where)->select());
				$arr[$j]['where_count'] = D('Members')->getLastSql();
				$count = 0;
				foreach($memberLevel as $key =>$val){
					$where['my_points'] = array('between',array($val['min_points'],$val['max_points']));
					$curcount = count(D('Members')->where($where)->select());
					$arr[$j]['level_'.$val['level_id']] = $curcount;
					$count = $count + intval($curcount);
				}
				$arr[$j]['min_level'] = $arr[$j]['count'] - $count;
				$where['is_lock'] = 1;
				$arr[$j]['lock'] = count(D('Members')->where($where)->select());
				$j++;
				$i=$i+$artime;
			}
			
		}else{
			
		}
		return $arr;
	}
	public function getOrderMembers($where){
		$list = D('Order')->where($where)->field('member_id')->select();
		$arr = array();
		foreach($list as $key => $val){
			$arr[$val['member_id']] = 1;
		}
		
		return count($arr);
	}
	function getisrunnian($year){
		if (($year % 4 == 0) && ($year % 100 != 0) || ($year % 400 == 0)) {
			return true;
		} else {
			return false;
		}
	}
	function getday_count($mon,$year){
		if($mon==1 || $mon==3 || $mon==5 || $mon==7 || $mon==8 || $mon==10 || $mon==12){
			return 31;
		}else{
			if($mon==2){
				return $this->getisrunnian($year) ? 29 :28;
			}else{
				return 30;
			}
		}
	}
	
	
}