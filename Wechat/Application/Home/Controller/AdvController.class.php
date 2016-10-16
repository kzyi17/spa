<?php
namespace Home\Controller;
use Think\Controller;
class AdvController extends Controller {
    
    public function _initialize()
    {
        $this->_checkLogin();
    }
    
    //首页
    public function index(){
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./ad_home');
    }
    
    //
    public function adList(){
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./ad_adList');
    }
    
    //详情
    public function adDetail(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./ad_adDetail');
    }
    
    //
    public function exchangeList(){
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./ad_exchangeList');
    }
    
    //详情
    public function exchangeDetail(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_adDetail');
    }
    
    private function _checkLogin(){
        if (!session('?memberInfo')){
            $this->redirect('user/login');
        }
    }
}