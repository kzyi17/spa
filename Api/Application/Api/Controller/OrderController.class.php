<?php
/**
 * 订单相关API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Api\Controller;
class OrderController extends CommonController {
    
    
    /**
     * 提交spa订单
     * 
     * @author kezhen.yi                  
     * @date 2015年12月28日 下午3:55:23        
     *
     */
    public function spacommit(){
        //检查参数
        $this->checkParam('spa_id',true,'int');//项目ID
        $this->checkParam('user_id',true,'int');//用户ID
        $this->checkParam('order_type',true,'int');//订单类型
        $this->checkParam('coupon_id',false,'int');//优惠券ID
        $data = D('SpaOrder')->createOrder($this->params['user_id'],$this->params['spa_id'],$this->params['order_type']);
        $this->success($data);
    }
    
    /**
     * spa订单详情
     * 
     * @author kezhen.yi                  
     * @date 2015年12月28日 下午3:55:34        
     *
     */
    public function spainfo(){
        //检查参数
        $this->checkParam('order_sn',true);
        
        $order_sn = $this->params['order_sn'];
        
        $data = D('SpaOrder')->getInfo($order_sn);
        
        if($data){
            $this->success($data);
        }else{
            $this->error(1004, '查无此记录');
        }
    }

    /**
     * spa订单预支付信息
     *
     * @author kezhen.yi
     * @date 2015年12月28日 下午3:58:58
     *
     */
    public function spaprepay(){
        //检查参数
        $this->checkParam('order_sn',true);
        $this->checkParam('pay_type',true);
        
        //获取参数
        $order_sn = $this->params['order_sn'];
        $pay_type = $this->params['pay_type'];
        
        
        //查询订单信息
        $orderInfo = D('SpaOrder')->getInfo($order_sn);
        
        
        if(!$orderInfo) $this->error(1004, '该订单不存在'); 
        if($orderInfo['order_status']==1) $this->error(1005, '该订单已完成');
        if($orderInfo['order_status']==2) $this->error(1005, '该订单已取消，请重新下单');
        if($orderInfo['pay_status']==1) $this->error(1005, '该订单已支付');
      
        
        $paycode = null;
        
        switch ($pay_type){
            case "alipay":
                $paycode = D('SpaOrder')->getAlipay();
                break;
            case "wxpay":
                $total_fee = (int)$orderInfo['pay_amount']*100;
                $paycode = D('SpaOrder')->getWxpay($order_sn,$orderInfo['spa_name'],$total_fee,null);
                break;
            default:
                $paycode = null;
        }

        if(!empty($paycode)){
            $this->success($paycode);
        }else{
            $this->error(1006, '支付信息有误');
        }
        
        
        //$this->success($this->params);
        
    }
    
    
    
   
    
    
}