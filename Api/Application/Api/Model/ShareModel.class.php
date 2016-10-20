<?php
namespace Api\Model;
/**
 * 分享模型
 *
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0
 */
class ShareModel{
    
    
    /**
     * 获取文章分类
     * 
     * @author kezhen.yi                  
     * @date 2016年4月10日 下午2:33:52        
     *
     */
    public function getArticleCate(){
        $model = M('sharecate');
        $where = array('is_system'=>0);
        return $model->where($where)->select();
    }
    
    
    /**
     * 获取文章列表
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     *
     */
    public function getArticleList($param){
    
        $model = M('sharearticle');
        
        $field.= "*,'".C('WEB_STATICS')."' as _url";
        $model->field($field);
        
        //查询条件
        if($param['where']){
            $model->where($param['where']);
        }
        
        //排序
        if($param['order']){
            $model->order($param['order']);
        }else{
            $model->order('create_time desc');
        }
        
        //分页
        if($param['page']){
            $model->page($param['page'],$param['limit']);
        }
        
        return $model->select();
        
    }
    
    
    /**
     * 获取文章详情
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午4:49:22        
     *
     */
    public function getArticleInfo($param){
        $model = M('sharearticle');
        $article_id = $param['article_id'];
        $model->where("article_id=$article_id");
        $art = $model->find();
        if ($art){
            $return['article']=$art;
            $return['ad_font'] = $this->getAdList($art['cate_id'],'font');
            $return['ad_top'] = $this->getAdList($art['cate_id'],'top');
            $return['ad_mid'] = $this->getAdList($art['cate_id'],'mid');
            $return['ad_bottom'] = $this->getAdList($art['cate_id'],'bottom');
            $return['_url'] = C('WEB_STATICS');
            return $return;
        }else{
            return array('errcode'=>1004,'errmsg'=>'查无记录');
        }
        
    }
    
    //
    public function getAdList($cate_id,$pos){
        $poscode = array(
            'type'       =>  'article',
            'pos'        =>  $pos,
            'artcate'    =>  $cate_id
        );
        $poscode = serialize($poscode);
        $pos = D('Ad')->getAdPos($poscode);
        if($pos){
            $adParam = array(
                'where'=>array('pos_id'=>$pos['pos_id']),
                'page'=>1,
                'limit'=>10,
            );
            return D('Ad')->getAdList($adParam);
        }else{
            return null;
        }
        
    }
    
    /**
     * 获取系统文章列表
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午5:32:54        
     *
     */
    public function getGuideList($param){
        $cate =  M('sharecate')->where("is_system=1")->getField('cate_id',true);
        if($cate){
            $model = M('sharearticle');
            $model->join('sharecate on sharecate.cate_id = sharearticle.cate_id','LEFT');
            return $model->where(array('sharearticle.cate_id'=>array('in',$cate)))->select();
        }else{
            return array('errcode'=>1004,'errmsg'=>'查无记录');
        }
       
    }
    
    /**
     * 获取分享系统用户信息
     * 
     * @author kezhen.yi                  
     * @date 2016年4月18日 上午1:32:00        
     *
     */
    public function getUserInfo($param){
        $user_id = $param['user_id'];
        $model_user = M('members');
        $data = $model_user->field('total_sharepoint,sharepoint')->where("user_id=$user_id")->find();
        if($data){
            return $data;
        }else{
            return array('errcode'=>1004,'errmsg'=>'查无用户信息');
        }
    }
    
    /**
     * 获取积分
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午6:14:44        
     *
     */
    public function getPoint($param){
        $article_id = $param['article_id'];
        $user_id = $param['user_id'];
        $model_log = M('share_log');
        
        $lastlog = $model_log->where(array('article_id'=>$article_id,'user_id'=>$user_id))->order('create_time desc')->limit(1)->find();
        if(!empty($lastlog)){
            if(date('Y-m-d',$lastlog['create_time'])==date('Y-m-d')){
                return array('errcode'=>3002,'errmsg'=>'您今日已领取，不能重复领取');
            }
        }
        
        $model_article = M('sharearticle');
        $model_user = M('members');
        
        $article = $model_article->where("article_id=$article_id")->find();
        $point = (int)$article['point'];
        
        //文章增加浏览量
        $model_article->where("article_id=$article_id")->setInc('hits');
        
        //用户增加积分
        $model_user->where("user_id=$user_id")->setInc('sharepoint',$point);
        $model_user->where("user_id=$user_id")->setInc('total_sharepoint',$point);
        
        //积分log
        $logDate = array(
            'article_id' => $article_id,
            'user_id' => $user_id,
            'create_time' => time(),
            'client_ip' => get_client_ip(),
            'point' => $point,
            'log_info' => '浏览文章【'.$article['title'].'】增加'.$point.'积分',
        );
        $model_log->add($logDate);
        
        return array('success'=>'浏览成功获得'.$article['point'].'积分');
    }
    
    /**
     * 获取收益明细
     * 
     * @author kezhen.yi                  
     * @date 2016年4月12日 上午7:53:20        
     *
     */
    public function getShareLog($param){
        $user_id = $param['user_id'];
        $model = M('share_log');
        
        return $model->where("user_id=$user_id")->order('create_time desc')->select();
    }
    
    /**
     * 
     * 
     * @author kezhen.yi                  
     * @date 2016年4月14日 下午3:13:22        
     *
     */
    public function getAd($param){
        $article_id = $param['article_id'];
        $article = M('sharearticle')->where("article_id=$article_id")->find();
        if(!$article){
            return array('errcode'=>1004,'errmsg'=>'查无文章记录');
        }
        
        $code = array(
            'type'       =>  'article',
            'pos'        =>  $param['pos'],
            'artcate'    =>  $article['cate_id']
        );
        
        $code = serialize($code); //echo $code;die;
        $pos = D('Ad')->getAdPos($code);

        if(!$pos){
            return array('errcode'=>1004,'errmsg'=>'查无广告位记录');
        }
        
        $adParam = array(
            'where'=>array('pos_id'=>$pos['pos_id']),
            'page'=>$param['page'],
            'limit'=>$param['limit'],
        );
        
        return D('Ad')->getAdList($adParam);
    }
    
    /**
     * 获取积分商品列表集
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     * @param string&&array $condition
     * @param array $order array('field'=>'desc',....)
     * @param string $page ‘page,limit’
     *
     */
    public function getGoodsList($param){
        $model = M('sharegoods');
        
        $field = 'sharegoods.*';
        $field.= ',merchant.merchant_name,merchant.phone,merchant.address';
        $field.= ',images.folder as cover_folder';
        $field.= ',images.name as cover_name';
        $field.= ',images.type as cover_type';
        $field.= ",'".C('WEB_STATICS')."' as _url";
        
        $model->field($field);
        
        
        //分页
        if($param['page']){
            $model->page($param['page'],$param['limit']);
        }
    
        $model->join('merchant ON merchant.merchant_id = sharegoods.merchant_id','LEFT');
        $model->join('images on images.img_id = sharegoods.cover','LEFT');
    
        return $model->select();
//         return $model->_sql();
    }
    
    /**
     * 获取积分商城商品详情
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     *
     */
    public function getGoodsInfo($param){
        $model = M('sharegoods');
        $model->join('merchant ON merchant.merchant_id = sharegoods.merchant_id','LEFT');
    
        $return = $model->where(array('sharegoods_id'=>$param['goods_id']))->find();
        if($return){
            if($return['gallery']){
                $where['img_id'] = array('in',$return['gallery']);
                $return['gallery'] = M('images')->where($where)->select();
            }else{
                $return['gallery'] = array();
            }
            $return['_url'] = C('WEB_STATICS');
            
            return $return;
        }else{
            return array('errcode'=>1004,'errmsg'=>'查无记录');
        }
         
    }
    
    /**
     * 获取商品兑换信息
     *
     * @author kezhen.yi
     * @date 2015年12月19日 下午9:06:16
     *
     */
    public function getExchangeInfo($param){
        
        $goods_id = $param['goods_id'];
        $user_id = $param['user_id'];
        //查询商品信息
        $goodsInfo = M('sharegoods')->field('sharegoods_id,goods_name,exchange_point,stock,cover')->where("sharegoods_id=$goods_id")->find();
        if(!$goodsInfo){
            return array('errcode'=>1004,'errmsg'=>'查无商品记录');
        }
        $goodsInfo['_url'] = C('WEB_STATICS');
        //查询用户信息、收货地址        
        $user = M('members')->field('user_id,sharepoint,address_id')->where("user_id=$user_id")->find();
//         $res =  D('Members')->addressList(array('user_id'=>$user_id));
//         $address_default = array();
//         $addressList = array();
//         if($res){
//             foreach ($res as $k=>$v){
//                 if(1==$v['is_default']){
//                     $address_default = $v; 
//                 }
//                 $addressList[$k] = array(
//                     'value'=> $v['id'],
//                     'text'=> $v['ship_user_name'].$v['province_name'].$v['city_name'].$v['area_name'].$v['address'].$v['address'].$v['address'].$v['address'].$v['address'],
//                 );
//             }
//         }
        
//         $user['address_default'] = $address_default;
//         $user['addressList'] = $addressList;//D('Members')->addressList(array('user_id'=>$user_id));
        $user['addressList'] = D('Members')->addressList(array('user_id'=>$user_id));
        
        return array('goodsInfo'=>$goodsInfo,'userInfo'=>$user);
    }
    
    
    /**
     * 兑换商品
     *
     * @author kezhen.yi
     * @date 2016年2月18日 下午3:35:20
     *
     */
    public function exchangeGoods($param){
        //查询商品信息
        $goodsID = $param['goods_id'];
        $goodsModel = M('sharegoods');
        $goodsInfo = $goodsModel->field('sharegoods_id,goods_name,exchange_point,stock,cover')->where("sharegoods_id=$goodsID")->find();
        if(!$goodsInfo){
            return array('errcode'=>1004,'errmsg'=>'兑换失败，查无商品记录');
        }
        //查询用户信息
        $userModel = M('members');
        $userID = $param['user_id'];
        $userInfo = $userModel->field('user_id,sharepoint,address_id')->where("user_id=$userID")->find();
        if(!$userInfo){
            return array('errcode'=>1004,'errmsg'=>'兑换失败，查无用户信息');
        }
        //查询收货地址
        $addressID = $param['address_id'];
        $addressInfo = M('members_address')
                        ->field('members_address.*,a.area_name as province_name,b.area_name as city_name,c.area_name as area_name')
                        ->where("id=$addressID")
                        ->join('areas as a on a.area_id = members_address.province','LEFT')
                        ->join('areas as b on b.area_id = members_address.city','LEFT')
                        ->join('areas as c on c.area_id = members_address.area','LEFT')
                        ->find();
        if(!$addressInfo){
            return array('errcode'=>1004,'errmsg'=>'兑换失败，查无收货地址信息');
        }
        
        //用户积分不能小于商品兑换积分
        if($userInfo['sharepoint'] < $goodsInfo['exchange_point']){
            return array('errcode'=>3011,'errmsg'=>'兑换失败，您的积分不够');
        }else{
//             return array('errcode'=>5001,'errmsg'=>'兑换失败,请重新操作');
//             return array('successmsg'=>"恭喜您成功兑换该商品");
            $exchangeData = array(
                'user_id' => $userID,
                'goods_id' => $goodsID,
                'exchange_point' => $goodsInfo['exchange_point'],
                'exchange_time' => time(),
                'shiper' => $addressInfo['ship_user_name'],
                'postcode' => $addressInfo['postcode'],
                'mobile' => $addressInfo['mobile'],
                'address' => $addressInfo['province_name'].$addressInfo['city_name'].$addressInfo['area_name'].$addressInfo['address'],
            );
            
            //保存兑换信息
            if(!M('share_exchange')->add($exchangeData)){
                return array('errcode'=>5001,'errmsg'=>'兑换失败,请重新操作');
            }
            
            //减库存
            $goodsModel->where("sharegoods_id=$goodsID")->setDec('stock',1);
            
            //扣除积分
            $userModel->where("user_id=$userID")->setDec('sharepoint',$goodsInfo['exchange_point']);
            
            //积分log
            $logDate = array(
                'user_id' => $userID,
                'create_time' => time(),
                'client_ip' => get_client_ip(),
                'point' => $goodsInfo['exchange_point'],
                'type' => 0,
                'log_info' => '兑换商品【'.$goodsInfo['goods_name'].'】消耗'.$goodsInfo['exchange_point'].'积分',
            );
            M('share_log')->add($logDate);
            
            //返回处理结果
            return array('successmsg'=>"恭喜您成功兑换该商品");
        }
    
    }
    
} 