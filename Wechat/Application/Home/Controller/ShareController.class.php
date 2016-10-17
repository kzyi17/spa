<?php
namespace Home\Controller;
use Think\Controller;
use Common\Util\WechatJSSDK;
use Common\Util\MyApi;
class ShareController extends Controller {
    
    public function _initialize()
    {
        
    }
    
    //首页
    public function index(){
        $this->_checkLogin();
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_home');
    }
    
    //新手学堂
    public function guide(){
        $this->_checkLogin();
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_guide');
    }
    
    //文章详情
    public function guideDetail(){
        $this->_checkLogin();
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_guide_detail');
    }
    
    //文章列表
    public function newsList(){
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_news_list');
    }
    
    //文章详情
    public function newsDetail(){
        //$this->_checkLogin();
        //微信分享
        $jssdk = new WechatJSSDK("wxa5a926dfcd79f564", "c0cf03e1d579e86503e2c95b0ce36b59");
//         echo $jssdk->GetSignPackage();die;
        //print_r($jssdk->GetSignPackage());die;
        $id = I('get.id');
        $uid = I('get.uid',0);
        if($uid){
           $this->visit_log($id,$uid);
        }
        
        $this->assign('signPackage',$jssdk->GetSignPackage());
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_news_detail');
    }
    
    private function visit_log($id,$uid){
        $ip = get_client_ip();
        $model_log = M('log_article_visit');
        $lastlog = $model_log->where(array('client_ip'=>$ip,'article_id'=>$id))->order('create_time desc')->limit(1)->find();
        if(!empty($lastlog)){
            if(date('Y-m-d',$lastlog['create_time'])==date('Y-m-d')){
                return false;
            }
        }
        
        $log_data = array();
        $log_data['article_id'] = $id;
        $log_data['client_ip'] = $ip;
        $log_data['create_time'] = time();
        $model_log->data($log_data)->add();
        
        //用户增加积分
        $model_user = M('members');
        $article = M('sharearticle')->where("article_id=$id")->find();
        $point = (int)$article['point'];
        
        $model_user->where("user_id=$uid")->setInc('sharepoint',$point);
        $model_user->where("user_id=$uid")->setInc('total_sharepoint',$point);
        //积分log
        $logDate = array(
            'article_id' => $id,
            'user_id' => $uid,
            'create_time' => time(),
            'client_ip' => $ip,
            'point' => $point,
            'log_info' => '分享文章好友浏览增加'.$point.'积分',
        );
        M('share_log')->add($logDate);
        
        
        //父级增加积分
        $userInfo = $model_user->where("user_id=$uid")->find();
        if($userInfo['parent_id']>0){
        	//增加积分
        	$pid= $userInfo['parent_id'];
        	$ppoint = $point*0.1;
        	$model_user->where("user_id=$pid")->setInc('sharepoint',$ppoint);
        	$model_user->where("user_id=$pid")->setInc('total_sharepoint',$ppoint);
        	//log
        	$logDate['user_id']=$pid;
        	$logDate['point']=$ppoint;
        	$logDate['log_info']='下级好友分享文章增加'.$ppoint.'积分';
        	M('share_log')->add($logDate);
        }
        
        
        return true;
    }
    
    //我的邀请
    public function myShare(){
        $this->_checkLogin();
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_myshare');
    }
    
    //收益明细
    public function pointDetail(){
        $this->_checkLogin();
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_earndetail');
    }
    
    //积分商城列表
    public function shopList(){
        $this->_checkLogin();
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_exchangeList');
    }
    
    //积分商城详情
    public function shopDetail(){
        $this->_checkLogin();
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_id',session('memberInfo.user_id'));
        $this->display('./share_exchangeDetail');
    }
    
    //兑换确认页面
    public function exchangeCommit(){
        $this->_checkLogin();
        $id = I('get.id');
        
        $postData = array('user_id'=>session('memberInfo.user_id'),'goods_id'=>$id);
        $res = MyApi::getContent('share/getExchangeInfo', $postData);
        //print_r(get_url());die;
        
        //TODO 错误处理
        if(isset($res['errcode'])){
            
        }
        
        $this->assign('goodsInfo',$res['goodsInfo']);
        $this->assign('userInfo',$res['userInfo']);
        $this->display('./share_exchange_commit');
    }
    
    private function _checkLogin(){
        if (!session('?memberInfo')){
            $url = U('user/login').'?url='.urlencode(get_url());
            redirect($url);
            //$this->redirect('user/login');
        }
    }
}