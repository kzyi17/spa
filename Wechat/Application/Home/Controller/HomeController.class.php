<?php
namespace Home\Controller;
use Think\Controller;
use Common\Util\WechatJSSDK;
class HomeController extends Controller {
    
    
    public function index(){
        $this->display('./home');
    }
    
    public function about(){
        $this->display('./about');
    }
    
    public function shareTest(){
        $jssdk = new WechatJSSDK("wx66948d5e6a30281d", "36fcc203487983237b538bfc70fdf770");
        $assign['signPackage'] = $jssdk->GetSignPackage();
        $this->assign($assign);
        $this->display('./test');
    }
    
}