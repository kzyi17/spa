<?php
namespace Api\Model;
/**
 * 广告商品模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
use Think\Model;
class AdgoodsModel extends Model{
    
    
    /**
     * 获取广告商品列表集
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     * @param string&&array $condition
     * @param array $order array('field'=>'desc',....)
     * @param string $page ‘page,limit’
     *
     */
    public function getList($condition=null,$order=null,$page=null,$limit=null){
    
        
        $this->field('adgoods.*,merchant.merchant_name,merchant.phone,merchant.address');
        //查询条件
        if($condition){
            $this->where($condition);
        }
    
        //排序
        if($order){
            $this->order($order);
        }
    
        //分页
        if($page){
            $this->page($page,$limit);
        }
        
        $this->join('merchant ON merchant.merchant_id = adgoods.merchant_id','LEFT');
    
//         $this->select();
//         return $this->_sql();
        
        return $this->select();
    }
    
    /**
     * 获取广告商品详情
     * 
     * @author kezhen.yi                  
     * @date 2015年12月19日 下午9:06:16        
     *
     */
    public function getInfo($adgoodsId){
        if(!$adgoodsId){
            return null;
        }
        
        $this->join('merchant ON merchant.merchant_id = adgoods.merchant_id','LEFT');
        
        return $this->where("adgoods_id=$adgoodsId")->find();
    }
    
    /**
     * 领取广告积分
     * 
     * @author kezhen.yi                  
     * @date 2016年2月18日 下午1:32:55        
     *
     */
    public function getPoint($param){
        $where['adgoods_id'] = $param['adgoods_id'];
        $where_user['user_id'] = $param['user_id'];
        $adgoods = $this->where($where)->find();//查询广告商品信息
        
        if(!$adgoods){
            return array('errcode'=>1004,'errmsg'=>'查无此记录');
        }
        
        //检查商品总积分是否为0
        if($adgoods['total_point']>0 and $adgoods['total_point']>=$adgoods['point']){
            $point = $adgoods['point'];
            $adLogModel = M('adgoods_log');
            
            //检查用户兑换限制
            //--每个用户每天只能领取同一个广告一次积分
            //--每个用户每天只能领取50积分
            $where_log['adgoods_id'] = $param['adgoods_id']; 
            $where_log['user_id'] = $param['user_id'];
            $lastlog = $adLogModel->where($where_log)->order('create_time desc')->limit(1)->find();
            if(!empty($lastlog)){
                if(date('Y-m-d',$lastlog['create_time'])==date('Y-m-d')){
                    return array('errcode'=>3002,'errmsg'=>'您今日已领取，不能重复领取');
                }
            }
            
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $day_start = mktime(0,0,0,$m,$d,$y);
            $day_end= mktime(23,59,59,$m,$d,$y);
            
            $where_today['create_time'] = array(array('gt',$day_start),array('lt',$day_end)) ;
            $where_today['user_id'] = $param['user_id'];
            $point_today = $adLogModel->where($where_today)->sum('point');
            
            if($point_today>=100){
                return array('errcode'=>3002,'errmsg'=>'您已超过今日能领取的最大限制');
            }
            
            //处理积分变化
            $this->where($where)->setDec('total_point',$point); //扣除商品总积分
            M('members')->where($where_user)->setInc('point',$point);//增加用户积分
            
            if($point>=$adgoods['total_point']){
                $this->where($where)->setField('status',1);
            }
            
            //增加用户积分获取记录
            $logData = array(
                'user_id'       => $param['user_id'],
                'adgoods_id'    => $param['adgoods_id'],
                'point'         => $point,
                'create_time'   => time(),
                'client_ip'     => get_client_ip(),
                'log_info'      => '增加'.$point.'积分',
            );
            
            $adLogModel->add($logData);
            
            //返回获取的积分数
            return array('successmsg'=>"恭喜您获得".$point."积分");
            
        }else{
            return array('errcode'=>3001,'errmsg'=>'该商品全部积分已被领取');
        }
        
    }
    
    /**
     * 兑换商品
     * 
     * @author kezhen.yi                  
     * @date 2016年2月18日 下午3:35:20        
     *
     */
    public function exchangeAd($param){
        //定义模型
        $adModel = M('adgoods');
        $userModel = M('members');
        //定义条件
        $where_ad = array(
            'adgoods_id' => $param['adgoods_id'],
        );
        $where_user = array(
            'user_id' => $param['user_id'],
        );
        
        //查询广告商品信息
        $adgoods = $adModel->where($where_ad)->find();
        
        if(!$adgoods or $adgoods['total_point']>0){
            return array('errcode'=>1004,'errmsg'=>'查无此记录');
        }
        
        //查询用户信息
        $user = $userModel->where($where_user)->find();
        
        //用户积分不能小于商品兑换积分
        if($user['point']<$adgoods['exchange_point']){
            return array('errcode'=>3011,'errmsg'=>'您的积分不够');
        }else{
            //更新广告信息
            $adUpData = array(
                'exchange_time' => time(),
                'consume_code'  => generate_rand(6),
                'user_id'       => $param['user_id'],
                'status'        => 2,
            );
            
            $adModel->where($where_ad)->save($adUpData);
            
            //更新用户积分,用户劵包
            $userModel->where($where_user)->setDec('point',$adgoods['exchange_point']); //扣除积分
            
            
            
            //返回处理结果
            return array('successmsg'=>"恭喜您成功兑换该商品");
        }
        
    }
    
    
    
} 