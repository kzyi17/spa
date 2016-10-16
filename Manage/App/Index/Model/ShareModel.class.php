<?php
namespace Index\Model;

class ShareModel{
	
    
    /**
     * 获取列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getArticleList($where,$limit,$offset,$order){
        $model = M('sharearticle');
        
        $field = 'sharearticle.*';
        $field.= ',sharecate.cate_name';
        
        $model->field($field);
        
        if($where) $model->where($where);
        if($offset) $model->limit($limit,$offset);
        $model->order($order);
    
        $model->join('sharecate on sharecate.cate_id = sharearticle.cate_id','LEFT');
    
        return $model->select();
    }
    
    /**
     * 获取文章总数
     * @author Muke
     * @param unknown $where
     */
    public function getArticleCount($where){
        $model = M('sharearticle');
        return $model->where($where)->count();
    }
    
    /**
     * 获取文章详情
     * 
     * @author kezhen.yi                  
     * @date 2016年4月19日 下午4:46:36        
     *
     */
    public function getArticleInfo($id=0){
        if(!$id){
            return null;
        }
        $model = M('sharearticle');
        return $model->where("article_id=$id")->find();
    }
    
    
    /**
     * 保存文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月20日 下午2:36:46        
     *
     */
    public function saveArticle(){
        $aid = I('article_id',0);
        $model = M('sharearticle');
        $saveData = array(
            'title'=>I('post.title'),
            'cate_id'=>I('post.cate_id'),
            'cover'=>I('post.cover'),
            'point'=>I('post.point'),
            'sharepoint'=>I('post.sharepoint'),
            'is_recommend'=>I('post.is_recommend'),
            'content'=>I('post.content','',false)
        );
        if($aid){//修改文章
            if($model->where(array("article_id"=>$aid))->save($saveData)){
                return $aid;
            }
        }else{//添加文章
            $saveData['create_time'] = time();
            $aid = $model->add($saveData);
            if($aid){
                return $aid;
            }
        }
        
        return false;
    }
    
    /**
     * 删除文章
     * 
     * @author kezhen.yi                  
     * @date 2016年4月21日 上午9:49:17        
     *
     */
    public function delArticle(){
        $id = I("id",0,'int');
        if($id){
            return M('sharearticle')->where(array('article_id'=>$id))->delete();
        }
        return false;
    }
    
    
    /**
     * 获取文章分类列表
     * @author kezhen.yi                  
     * @date 2016年4月21日 上午9:49:17
     */
    public function getArticleCateList($where,$limit,$offset,$order){
        $model = M('sharecate');
        
        return $model->select();
    }
    
    /**
     * 获取分类详情
     *
     * @author kezhen.yi
     * @date 2016年4月19日 下午4:46:36
     *
     */
    public function getArticleCateInfo($id=0){
        if(!$id){
            return null;
        }
        $model = M('sharecate');
        return $model->where("cate_id=$id")->find();
    }
    
    /**
     * 保存分类
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午2:36:46
     *
     */
    public function saveArticleCate(){
        $cid = I('cate_id',0);
        $model = M('sharecate');
        $saveData = array(
            'cate_name'=>I('post.cate_name'),
            'is_system'=>I('post.is_system'),
        );
        if($cid){//修改分类
            if($model->where(array("cate_id"=>$cid))->save($saveData)){
                return $cid;
            }
        }else{//添加分类
            $cid = $model->add($saveData);
            if($cid){
                return $cid;
            }
        }
    
        return false;
    }
    
    /**
     * 删除分类
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function delArticleCate(){
        $id = I("id",0,'int');
        if($id){
            return M('sharecate')->where(array('cate_id'=>$id))->delete();
        }
        return false;
    }
    
    /**
     * 获取积分商城商品列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getGoodsList($where,$limit,$offset,$order){
        $model = M('sharegoods');
        
        $field = 'sharegoods.*';
        $field.= ',merchant.merchant_name';
    
        $model->field($field);
    
        if($where) $model->where($where);
        if($offset) $model->limit($limit,$offset);
        $model->order($order);
    
        $model->join('merchant on merchant.merchant_id = sharegoods.merchant_id','LEFT');
    
        return $model->select();
    }
    
    /**
     * 获取积分商城商品总数
     * @author Muke
     * @param unknown $where
     */
    public function getGoodsCount($where){
        return M('sharegoods')->where($where)->count();
    }
    
    /**
     * 获取积分商品详情
     *
     * @author kezhen.yi
     * @date 2016年4月19日 下午4:46:36
     *
     */
    public function getGoodsInfo($id=0){
        if($id){
            $info = M('sharegoods')->where("sharegoods_id=$id")->find();
            if($info){
                //获取相册
                if($info['gallery']){
                    $info['_imglist'] = M('images')->where(array('img_id'=>array('in',$info['gallery'])))->select();
                }else{
                    $info['_imglist'] = null;
                }
                 
                return $info;
            }
        }
        return null;
    }
    
    /**
     * 保存积分商品
     *
     * @author kezhen.yi
     * @date 2016年4月21日 下午2:35:37
     *
     */
    public function saveGoods(){
        
        $gid = I('sharegoods_id',0);
        $model = M('sharegoods');
        
        $saveData = array(
            'goods_name'=>I('post.goods_name'),
            'merchant_id'=>I('post.merchant_id'),
            'cover'=>I('post.photo_id'),
            'gallery'=>I('post._imgList'),
            'intro'=>I('post.intro'),
            'exchange_point'=>I('post.exchange_point'),
            'stock'=>I('post.stock'),
            'introduce'=>I('post.introduce','',false),
            
        );
        if($gid){//修改文章
            if(!$model->where(array("sharegoods_id"=>$gid))->save($saveData)){
                return false;
            }
            
        }else{//添加文章
            $saveData['create_time'] = time();
            $gid = $model->add($saveData);
            if(!$gid){
                return false;
            }
        }
        
        return true;
    }

    /**
     * 删除积分商品
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function delGoods(){
        $id = I("id",0,'int');
        if($id){
            return M('sharegoods')->where(array('sharegoods_id'=>$id))->delete();
        }
        return false;
    }
    
    /**
     * 获取广告总数
     * @author Muke
     * @param unknown $where
     */
    public function getAdCount($where){
        return M('ad')->where($where)->count();
    }
    
    /**
     * 获取广告列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function geAdList($where,$limit,$offset,$order){
        $model = M('ad');
    
//         $field = 'sharegoods.*';
//         $field.= ',merchant.merchant_name';
    
//         $model->field($field);
    
        if($where) $model->where($where);
        if($offset) $model->limit($limit,$offset);
        $model->order($order);
    
        $model->join('ad_pos on ad_pos.pos_id = ad.pos_id','LEFT');
        $model->join('sharegoods on sharegoods.sharegoods_id = ad.sharegoods_id','LEFT');
    
        return $model->select();
    }
    
    /**
     * 获取广告位列表
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     */
    public function getAdPosList($where,$limit,$offset,$order){
        $model = M('ad_pos');
    
        return $model->select();
    }
    
    /**
     * 保存广告
     *
     * @author kezhen.yi
     * @date 2016年4月20日 下午2:36:46
     *
     */
    public function saveAd(){
        $aid = I('ad_id',0);
        $model = M('ad');
        $saveData = array(
            'ad_name'=>I('post.ad_name'),
            'pos_id'=>I('post.pos_id'),
            'content'=>I('post.cover'),
            'sharegoods_id'=>I('post.sharegoods_id'),
        );
        if($aid){//修改文章
            if($model->where(array("ad_id"=>$aid))->save($saveData)){
                return $aid;
            }
        }else{//添加文章
            $aid = $model->add($saveData);
            if($aid){
                return $aid;
            }
        }
    
        return false;
    }
    
    /**
     * 删除广告
     *
     * @author kezhen.yi
     * @date 2016年4月21日 上午9:49:17
     *
     */
    public function delAd(){
        $id = I("id",0,'int');
        if($id){
            return M('ad')->where(array('ad_id'=>$id))->delete();
        }
        return false;
    }
    
    /**
     * 获取广告详情
     *
     * @author kezhen.yi
     * @date 2016年4月19日 下午4:46:36
     *
     */
    public function getAdInfo($id=0){
        if(!$id){
            return null;
        }
        $model = M('ad');
        return $model->where("ad_id=$id")->find();
    }
    
    //访问统计
    public function hitsLog(){
        $model = M('share_log');
        $where = array();
        $where['type'] = 1;
        
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $day_start = mktime(0,0,0,$m,$d,$y);
        $day_end= mktime(23,59,59,$m,$d,$y);
        $where['create_time'] = array(array('gt',$day_start),array('lt',$day_end));
        
        $model->where($where);
        return $model->count();
    }
    
    /**
     * 获取访问明细列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getLogArtList($where,$limit,$offset,$order){
        $model = M('sharearticle');
    
        $field = 'sharearticle.article_id,sharearticle.title,sharearticle.hits';
        $field.= ',sharecate.cate_name';
        
        $model->field($field);
    
        if($where) $model->where($where);
        if($offset) $model->limit($limit,$offset);
        $model->order($order);
        $model->join('sharecate on sharecate.cate_id = sharearticle.cate_id','LEFT');
    
        $return = array();
        foreach ($model->select() as $k=>$v){
            $return[$k] = $v;
            $return[$k]['logCount'] = $this->getLogCountFromArt($v['article_id']);
            $return[$k]['logCount_visitor'] = $this->getArtLogTodayCount($v['article_id']);
        }
        
        return $return;
    }
    
    /**
     * 获取文章总数
     * @author Muke
     * @param unknown $where
     */
    public function getLogArticleCount($where){
        $model = M('sharearticle');
        return $model->where($where)->count();
    }
    
    //
    public function getLogCountFromArt($article_id){
        $model = M('share_log');
        $where = array();
        $where['article_id'] = $article_id;
        
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $day_start = mktime(0,0,0,$m,$d,$y);
        $day_end= mktime(23,59,59,$m,$d,$y);
        $where['create_time'] = array(array('gt',$day_start),array('lt',$day_end));
        
        $model->where($where);
        return $model->count();
    }
    
    public function getArtLogTodayCount($id){
        $current_date=getdate();
        $time = strtotime($current_date['year'].'-'.$current_date['mon'].'-'.$current_date['mday']);
        $where['create_time'] = array('gt',$time);
        $where['article_id'] = $id;
        return M('log_article_visit')->where($where)->count();
    }
    
    /**
     * 获取兑换信息列表
     * @author Muke
     * @param unknown $where
     * @param unknown $limit
     * @param unknown $offset
     * @param unknown $order
     * @param unknown $select
     */
    public function getExchangeList($where,$limit,$offset,$order){
        $model = M('share_exchange');
    
        $field = 'share_exchange.*';
        $field.= ',sharegoods.goods_name';
        $field.= ',members.nickname,members.user_name';
    
        $model->field($field);
    
        if($where) $model->where($where);
        if($offset) $model->limit($limit,$offset);
        $model->order($order);
    
        $model->join('sharegoods on sharegoods.sharegoods_id = share_exchange.goods_id','LEFT');
        $model->join('members on members.user_id = share_exchange.user_id','LEFT');
    
        return $model->select();
    }
    
    /**
     * 获取兑换信息总数
     * @author Muke
     * @param unknown $where
     */
    public function getExchangeCount($where){
        $model = M('share_exchange');
        return $model->where($where)->count();
    }
    
}

