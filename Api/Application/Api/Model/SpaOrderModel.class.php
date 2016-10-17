<?php
namespace Api\Model;
/**
 * 美容订单模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
use Think\Model;
class SpaOrderModel extends Model{
    
    /**
     * 
     * 
     * @author kezhen.yi                  
     * @date 2016年1月15日 上午4:50:04        
     *
     */
    public function getListByUserId($user_id){
        if(!$user_id or $user_id==""){
            return null;
        }
        
        return $this->where("user_id=$user_id")->select();
        
    }
    
   /**
    * 生成订单
    * 
    * @author kezhen.yi                  
    * @date 2015年12月29日 上午5:32:53        
    *
    */
    public function createOrder($user_id,$spa_id,$order_type){
        
        $order_sn = $this->build_order_no();//生成唯一订单号
        $user_name = M('members')->where("user_id=$user_id")->getField('nickname');//查询用户名称
        $spaInfo = M('spa')->where("spa_id=$spa_id")->find();//查询美容项目
        
        
        $saveData = array();
        $saveData['order_sn'] = $order_sn;//订单号
        $saveData['create_time'] = time();
        $saveData['user_id'] = $user_id;//用户ID
        $saveData['user_name'] = $user_name;//用户名称
        $saveData['order_type'] = $order_type;//预约类型
        $saveData['spa_id'] = $spaInfo['spa_id'];//项目ID
        $saveData['spa_name'] = $spaInfo['spa_name'];//项目名称
        $saveData['merchant_id'] = $spaInfo['merchant_id'];//商家ID
        $saveData['order_amount'] = $spaInfo['sale_price'];//订单总价
        $saveData['pay_amount'] = $spaInfo['sale_price'];//支付总价
        
        //$order_id = $this->add($saveData);
        
        if($order_id=$this->add($saveData)){
            return array('order_id'=>$order_id,'order_sn'=>$order_sn);
        }
        //return $this->add($saveData);
        //return $this->add($orderInfo);
    }
    
    /**
     * 订单详情
     * 
     * @author kezhen.yi                  
     * @date 2015年12月29日 下午3:48:24        
     *
     */
    public function getInfo($order_sn){
        if(!$order_sn or $order_sn==""){
            return null;
        }
        
        return $this->where("order_sn=$order_sn")->find();
    }
    
    /**
     * 微信支付码
     * 
     * @author kezhen.yi                  
     * @date 2015年12月30日 上午12:21:07        
     * @param unknown $order_sn
     * @param unknown $subject
     * @param unknown $total_fee
     * @param string $notify_url
     * @return 成功时返回，其他抛异常
     */
    public function getWxpay($order_sn,$subject,$total_fee,$notify_url=null){
        
        require_once MODULE_PATH."Common/wxpayv3/WxPay.Api.php";
        require_once MODULE_PATH."Common/wxpayv3/WxPay.Data.php";
        
        //return $total_fee;
        
        $unifiedOrder = new \WxPayUnifiedOrder();
        $unifiedOrder->SetBody($subject);
        $unifiedOrder->SetOut_trade_no($order_sn);
        $unifiedOrder->SetTotal_fee($total_fee);
        //$unifiedOrder->SetNotify_url($notify_url);
        $unifiedOrder->SetTrade_type("APP");
        $result = \WxPayApi::unifiedOrder($unifiedOrder);
        
        return $result;
        
        //return $this->getRandom(32);
        
//         $input = new \Org\Wechat\WxPay\WxPayUnifiedOrder(); 
//         //商品描述
//         $input->SetBody('测试');
//         //商户订单号
//         $input->SetOut_trade_no($order_sn);
//         //总金额
//         $input->SetTotal_fee(1);
//         //通知地址
//         $input->SetNotify_url('http://www.mywork99.com/notify/wxpay.php');
//         //交易类型
//         $input->SetTrade_type("APP");
        
//         $order = \Org\Wechat\WxPay\WxPayApi::unifiedOrder($input);
        
        
//         // 统一下单接口返回正常的prepay_id，再按签名规范重新生成签名后，将数据传输给APP。
//         // 参与签名的字段名为appId，partnerId，prepayId，nonceStr，timeStamp，package。注意：package的值格式为Sign=WXPay
//         $time_stamp = time();
//         $pack	= 'Sign=WXPay';
//         //输出参数列表
//         $prePayParams =array();
//         $prePayParams['appid']		=$order['appid'];
//         $prePayParams['partnerid']	=$order['mch_id'];
//         $prePayParams['prepayid']	=$order['prepay_id'];
//         $prePayParams['noncestr']	=$order['nonce_str'];
//         $prePayParams['package']	=$pack;
//         $prePayParams['timestamp']	=$time_stamp;
//         //echo json_encode($prePayParams);
        
//         $result = \Org\Wechat\WxPay\WxPayResults::InitFromArray($prePayParams,true)->GetValues();
        
        
//         return $result;
        
        
//         //生成APP端调起支付请求的参数
//         $tools = new \Org\Wechat\WxPay\AppPay();
//         $payCode = $tools->GetAppPayParameters($order);
//         //$payCode['retcode'] = 0;
//         //$payCode['retmsg'] = 'ok';
//         return $order;
    }
    
   
//     //产生随机字符串
//     private function getRandom($param){
//         $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
//         $key = "";
//         for($i=0;$i<$param;$i++)
//         {
//             $key .= $str{mt_rand(0,32)};    //生成php随机数
//         }
//         return $key;
//     }
    
    /**
     * 支付宝支付码
     * 
     * @author kezhen.yi                  
     * @date 2015年12月30日 上午12:21:27        
     *
     */
    public function getAlipay(){
        
    }
    
    /**
     * 根据订单ID查询订单号
     * 
     * @author kezhen.yi                  
     * @date 2015年12月29日 上午5:36:26        
     *
     */
    public function getOrderNo($orderId){
        
    }
    
    /**
     * 根据订单号查询订单ID
     *
     * @author kezhen.yi
     * @date 2015年12月29日 上午5:36:26
     *
     */
    public function getOrderId($orderNo){
    
    }
    
    /**
     * 生成唯一订单号
     * 
     * @author kezhen.yi                  
     * @date 2015年12月29日 上午5:40:05        
     *
     */
    private function build_order_no(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    
    
} 