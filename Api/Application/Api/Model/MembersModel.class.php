<?php
namespace Api\Model;
/**
 * 用户模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
use Think\Model;
use Common\Extend\Base64Upload;
class MembersModel extends Model{
    
    
    //用户登录
    public function login($username,$password){
        if(!$username||!$password){
            return null;
        }
        
        $model = M('members');
        $where['user_name'] = $username;
        $user = $model->where($where)->find();
    
//         return $user;
        
        if(!$user||($user['password']!=md5($password.C('AUTHTOKEN')))){
            return null;
            //return array('code'=>402,'error'=>'用户名或密码错误');
        }
    
        return $user;
    }    
    
    /**
     * 用户注册
     * 
     * @author kezhen.yi                  
     * @date 2016年2月24日 下午5:44:33        
     *
     */
    public function reg($param){
        if(!isMobile($param['mobile'])){
            return array('errcode'=>2001,'errmsg'=>'手机号码不正确，请重新输入');
        }
        
        //检查账号是否注册
        $user = $this->where(array('user_name'=>$param['mobile']))->find();
        if($user){
            return array('errcode'=>2002,'errmsg'=>'用户已存在,请重新注册');
        }
        
        //检查验证码
        if(!D('Smscode')->checkCode($param['mobile'],$param['code']) AND $param['code']!="20162016"){
            return array('errcode'=>2002,'errmsg'=>'验证码错误或已过期，请重新输入');
        }
        
        $saveData = array(
            'user_name' => $param['mobile'],
            'phone' => $param['mobile'],
            'password'  => md5($param['password'].C('AUTHTOKEN')),
            'reg_time'  => time(),
            'parent_id' => $param['pid'],
        );
        
        if(isset($param['parent']) and !empty($param['parent'])){
            $saveData['parent_id'] = $param['parent'];
        }
        if($param['pid']>0){
	        //邀请奖励
	        $point = 10;
	        $user_id = $param['pid'];
	        $logDate = array(
	            'user_id' => $user_id,
	            'create_time' => time(),
	            'client_ip' => get_client_ip(),
	            'point' => $point,
	            'log_info' => '邀请好友【'.$param['mobile'].'】增加'.$point.'积分',
	        );
	        M('share_log')->add($logDate);
	        
	        //用户增加积分
	        M('members')->where("user_id=$user_id")->setInc('sharepoint',$point);
	        M('members')->where("user_id=$user_id")->setInc('total_sharepoint',$point);
        }
        
        $result = $this->add($saveData);
        if($result){
            return array('successmsg'=>'注册成功','userinfo'=>$result);
        }else{
            return array('errcode'=>5001,'errmsg'=>'注册失败，请重新注册');
        }

    }
    
    //用户信息
    public function getInfo($userId){
        
        $model = M('members');
        $where['user_id'] = $userId;
        $user = $model->where($where)->find();
        if(!empty($user['user_icon'])){
            $user['user_icon'] = C('upload_urlpath').$user['user_icon'];
        }
        
        return $user;
        
    }
    
    //用户券包
    public function getticket($param){
        //定义模型
        $adModel = M('adgoods');
        //定义条件
        $where= array(
            'user_id' => $param['user_id'],
        );
        //查询
        $adgoods = $adModel->where($where)->select();
        
        return $adgoods;
    }
    
    
    /**
     * 获取用户分销体系
     * @param unknown $username
     * @param unknown $password
     * @return NULL|unknown
     */
    public function getFXC($param){
        $model = M('members');
        $userId = $param['user_id'];
        //获取一级分销
        $fcusers_1 = $this->_getChild($userId);//$model->where("parent_id=$userId")->getField('user_id',true);
        $fcusers_2 = $this->_getChild($fcusers_1);
        $fcusers_3 = $this->_getChild($fcusers_2);
    
        return array('lv1'=>count($fcusers_1),'lv2'=>count($fcusers_2),'lv3'=>count($fcusers_3));
    }
    
    /**
     * 用户下级体系
     * 
     * @author kezhen.yi                  
     * @date 2016年3月12日 上午5:00:50        
     *
     */
    private function _getChild($userId){
        
        if(is_array($userId)){
            $where['parent_id'] = array('in',$userId);
            return M('members')->where($where)->getField('user_id',true);
        }else{
            return M('members')->where("parent_id=$userId")->getField('user_id',true);
        }
        
//         $map['id']  = array('not in',array('1','5','8'));
        
//         return M('members')->where("parent_id=$userId")->getField('user_id',true);
    }
    
    /**
     * 更新用户信息
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午10:40:36
     *
     */
    public function updateInfo($param){
        if(!isset($param['user_id']) || empty($param['user_id'])){
            return array('errcode'=>1001,'errmsg'=>'缺少参数');
        }
    
        $model = M('members');
        $userId = $param['user_id'];
        $updata = array();
    
        //更新用户昵称
        if(isset($param['nickname'])){
            $updata['nickname'] = $param['nickname'];
        }
    
        //更新用户密码
        if(isset($param['password'])){
            $updata['password'] = encrypt($param['password']);
        }
        
        //更新用户头像
        if(isset($param['userhead'])){
            //上传图片
            $upload = new Base64Upload();
            $upload->rootPath  = C('upload_rootPath');
            $upload->savePath  = 'usericon/';
            $imgInfo = $upload->upload($param['userhead']);
            if($imgInfo){
//                 $updata['user_icon'] = C('upload_urlpath').$imgInfo['savepath'].$imgInfo['savefullname'];
                $updata['user_icon'] = $imgInfo['savepath'].$imgInfo['savefullname'];
            }
            
        }
        
       /*  
        return $updata;
        //更新字段
        if(!empty($param[''])){
            $updata[''] = $param[''];
        }
        */
        
        //检查是否是否不包含更新字段
        if (empty($updata)){
            return array('errcode'=>1001,'errmsg'=>'缺少参数');
        }
    
        //保存信息
        if($model->where("user_id=$userId")->save($updata)){
            $userInfo = $this->getInfo($userId);
            unset($userInfo['password']);//隐藏密码字段
            return array('success'=>"注册成功","userInfo"=>$userInfo);
        }else{
            return array('errcode'=>5001,'errmsg'=>'更新用户数据失败');
        }
    }
    
    /**
     * 收货地址列表
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午10:40:36
     *
     */
    public function addressList($param){
        if(!isset($param['user_id']) || empty($param['user_id'])){
            return array('errcode'=>1001,'errmsg'=>'缺少参数');
        }
    
        $model = M('members_address');
        
        $field = 'members_address.*';
        $field.= ',a.area_name as province_name';
        $field.= ',b.area_name as city_name';
        $field.= ',c.area_name as area_name';

        $model->field($field);
        
        $where= array(
            'member_id' => $param['user_id'],
        );
        
        $model->join('areas as a on a.area_id = members_address.province','LEFT');
        $model->join('areas as b on b.area_id = members_address.city','LEFT');
        $model->join('areas as c on c.area_id = members_address.area','LEFT');
        
        //查询
        return $model->where($where)->select();
        
    }
    
    /**
     * 添加收货地址
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午10:40:36
     *
     */
    public function addressAdd($param){
        if(!isset($param['user_id']) || empty($param['user_id'])){
            return array('errcode'=>1001,'errmsg'=>'缺少参数');
        }
        $defaultAddress = $this->getAddressDefault($param['user_id']);
        if($defaultAddress){
            $is_default = 0;
        }else {
            $is_default = 1;
        }
        
        $saveData = array(
            'member_id' => $param['user_id'],
            'mobile' => $param['mobile'],
            'ship_user_name' => $param['ship_user_name'],
            'province' => $param['province'],
            'city' => $param['city'],
            'area' => $param['area'],
            'address' => $param['address'],
            'is_default' => $is_default,
        );
        
        $result = M('members_address')->add($saveData);
        if($result){
            return array('successmsg'=>'添加地址成功','address_id'=>$result);
        }else{
            return array('errcode'=>5001,'errmsg'=>'添加地址失败，请重新注册');
        }
    }
    
    
    
    /**
     * 删除收货地址
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function addressDel($param){
        $id = $param['address_id'];
        $user_id = $param['user_id'];
       
        $model = M('members_address');
        $model->where(array('id'=>$id,'member_id'=>$user_id));
        $adr = $model->find();
        
        if($model->delete()){
            if ($adr['is_default']==1){
                $this->_setDefultAddress($user_id);
            }
            return array('successmsg'=>"删除地址成功");
        }else{
            return array('errcode'=>5001,'errmsg'=>'删除地址失败，请重新操作');
        }
       
    }
    
    /**
     * 用户收货地址详情
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function addressInfo($param){
        $model = M('members_address');
        $where['member_id'] = $param['user_id'];
        return $model->where($where)->find();
    
    }
    
    /**
     * 设置默认收货地址
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function addressSetDefault($param){
        $id = $param['address_id'];
        $user_id = $param['user_id'];
        
        if($this->_setDefultAddress($user_id,$id)){
            return array('successmsg'=>"设置默认地址成功");
        }else{
            return array('errcode'=>5001,'errmsg'=>'操作失败，请重新操作');
        }
    
    }
    
    
    
    private function getAddressDefault($user_id){
        $model =  M('members_address');
        $where= array(
            'member_id' => $user_id,
            'is_default' => 1
        );
        
        return $model->where($where)->find();
    }
    
    /**
     * 设置默认收货地址
     * 
     */
    private function _setDefultAddress($user_id,$address_id=NULL){
        $model =  M('members_address');
        $model->where(array('member_id' => $user_id))->setField('is_default',0);
        
        if($address_id){
            return $model->where(array('member_id' => $user_id,'id'=>$address_id))->setField('is_default',1);
        }else{
            return $model->where(array('member_id' => $user_id))->limit(1)->setField('is_default',1);
        }
    }
    
    /**
     * 兑换列表
     *
     * @author kezhen.yi
     * @date 2016年2月28日 上午10:40:36
     *
     */
    public function exchangeList($param){
        if(!isset($param['user_id']) || empty($param['user_id'])){
            return array('errcode'=>1001,'errmsg'=>'缺少参数');
        }
    
        $model = M('share_exchange');
    
        $field = 'share_exchange.*';
        $field.= ',sharegoods.goods_name,sharegoods.goods_cover';
        $field.= ",'".C('WEB_STATICS')."' as _url";
    
        $model->field($field);
        $model->order('exchange_time desc');
        $where= array(
            'share_exchange.user_id' => $param['user_id'],
        );
        
        $model->join('sharegoods on sharegoods.sharegoods_id = share_exchange.goods_id','LEFT');
//         $model->join('images on images.img_id = sharegoods.cover','LEFT');
    
        return $model->where($where)->select();
    }
    
} 