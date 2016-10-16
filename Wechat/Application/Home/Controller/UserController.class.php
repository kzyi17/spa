<?php
namespace Home\Controller;
use Think\Controller;
use Common\Util\MyApi;
class UserController extends Controller {
    
    
    public function index(){
        if ($this->_checkLogin()){
            $this->assign('userInfo',session('memberInfo'));
            $this->display('./userCenter');
        }else{
            $this->redirect('user/login');
        }
    }
    
    public function login(){
        if(IS_POST){
            $postData = I('post.');
            if($postData['user_id']){
                session('memberInfo', $postData);
                //获取URL
                $url = I('get.url')?I('get.url'):U('user/index');
                //redirect($url);
                $this->success('登录成功',$url,true);
            }else{
                $this->error('登录失败','',true);
            }
        }else{
            $this->display('./login');
        }
    }
    
    public function logout(){
        session('memberInfo', NULL);
        $this->success('退出成功，即将跳转',U('user/login'),true);
    }
    
    public function reg(){
        $pid = I('userid',0);
        $this->assign('userid',$pid);
        $this->display('./reg');
    }
    
    public function address(){
        if ($this->_checkLogin()){
            $this->assign('userInfo',session('memberInfo'));
            $this->display('./userAddress');
        }else{
            $this->redirect('user/login');
        }
    }
    
    public function addressAdd(){
        if ($this->_checkLogin()){
            if(IS_POST){
                $postData = I('post.');
                $userInfo = session('memberInfo');
                $postData['user_id'] = $userInfo['user_id'];
                
                $res = MyApi::getContent('user/addAddress', $postData);
                
                $this->success('test',$res ,true); 
                
                /* $url = I('get.url')?I('get.url'):U('user/index');
                $this->success('登录成功',$url,true); */
            }else{
                $this->assign('userInfo',session('memberInfo'));
                $this->display('./userAddressAdd');
            }
        }else{
            $this->redirect('user/login');
        }
    }
    
    /**
     * 我的兑换
     */
    public function exchange(){
        if ($this->_checkLogin()){
            $this->assign('userInfo',session('memberInfo'));
            $this->display('./userExchange');
        }else{
            $this->redirect('user/login');
        }
        
    }
    
    
    private function _checkLogin(){
        return session('?memberInfo');
    }
    
}