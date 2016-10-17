<?php
/**
 * 用户相关API
 * 
 * @author kezhen.yi <2015年12月16日 下午6:46:33>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Api\Controller;
class UserController extends CommonController {
    
    /**
     * 用户登录
     * 
     * @author kezhen.yi                  
     * @date 2016年2月24日 下午3:11:32        
     *
     */
    public function login(){
        //检查参数
        $this->checkParam('account',true);
        $this->checkParam('password',true);
    
        $username = $this->params['account'];
        $password = $this->params['password'];
    
        $responseData = D('Members')->login($username,$password);
        
        if($responseData){
            $this->success($responseData);
        }else{
            $this->error(1006, '用户名或密码错误');
        }
    
    }
    
    /**
     * 用户注册
     * 
     * @author kezhen.yi                  
     * @date 2016年2月24日 下午3:11:51        
     *
     */
    public function register(){
        //检查参数
        $this->checkParam('mobile',true);
        $this->checkParam('password',true);
        $this->checkParam('code',true);
        $this->checkParam('pid',false,0);
        
        $data = D('Members')->reg($this->params);
        $this->jsonReturn($data);
    }
    
    
    
    /**
     * 我的预约
     *
     * @author kezhen.yi
     * @date 2015年12月28日 下午3:55:34
     *
     */
    public function getmyorderlist(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $user_id = $this->params['user_id'];
    
        $data = D('SpaOrder')->getListByUserId($user_id);
    
        $this->success($data);
    }
    
    /**
     * 我的信息
     *
     * @author kezhen.yi
     * @date 2015年12月28日 下午3:55:34
     *
     */
    public function getmyinfo(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $user_id = $this->params['user_id'];
    
        $data = D('Members')->getInfo($user_id);
    
        $this->success($data);
    }
    
    /**
     * 我的券包
     * 
     * @author kezhen.yi                  
     * @date 2016年2月18日 下午4:48:54        
     *
     */
    public function gettickets(){
        //检查参数
        $this->checkParam('user_id',true);
        
        $data = D('members')->getticket($this->params);
         
        $this->jsonReturn($data);
    }
    
    /**
     * 获取下级用户信息
     *
     * @author kezhen.yi
     * @date 2016年2月18日 下午4:48:54
     *
     */
    public function getchild(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->getChild($this->params);
         
        $this->jsonReturn($data);
    }
    
    /**
     * 修改用户信息
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function updateinfo(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->updateInfo($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 获取用户收货地址
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function getAddressList(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->addressList($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 增加用户收货地址
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function addAddress(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->addressAdd($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 删除用户收货地址
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function delAddress(){
        //检查参数
        $this->checkParam('user_id',true);
        $this->checkParam('address_id',true);
    
        $data = D('members')->addressDel($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 设置默认收货地址
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function setDefaultAddress(){
        //检查参数
        $this->checkParam('user_id',true);
        $this->checkParam('address_id',true);
    
        $data = D('members')->addressSetDefault($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 获取用户分销体系
     *
     * @author kezhen.yi
     *
     */
    public function getfxc(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->getFXC($this->params);
        $this->jsonReturn($data);
    }
    
    /**
     * 获取用户兑换列表
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午12:00:43
     *
     */
    public function getExchangeList(){
        //检查参数
        $this->checkParam('user_id',true);
    
        $data = D('members')->exchangeList($this->params);
        $this->jsonReturn($data);
    }
    
}