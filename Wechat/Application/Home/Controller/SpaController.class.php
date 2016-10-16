<?php
namespace Home\Controller;
use Think\Controller;
use Org\Wechat\WxPay\JsApiPay;
class SpaController extends Controller {
    
    
    public function lists(){
        $this->display('./spaList');
    }
    
    public function detail(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->display('./spaDetail');
    }
    
    public function order(){
        $this->_checkLogin();
        
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./spaOrderCommit');
    }
    
    public function pay(){
        $this->_checkLogin();
        $order_sn = I('get.ordersn');
        $this->assign('order_sn',$order_sn);
//         $tools = new JsApiPay();
//         $openId = $tools->GetOpenid();
        
//         //print_r($openId);
//         $this->assign('openId',$openId);
        $this->display('./spaOrderPay');
    }
    
    private function _checkLogin(){
        if (!session('?memberInfo')){
            $this->redirect('user/login');
        }
    }
    
}