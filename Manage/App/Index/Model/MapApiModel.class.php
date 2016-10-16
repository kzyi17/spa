<?php
namespace Index\Model;
use Common\Util\HttpCurl;
/**
 * 高德地图API调用
 * 
 * @author kezhen.yi <2015年12月18日 上午4:26:46>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
class MapApiModel{
    
    public $tableid = '56dd00597bbf197f399d0f90';
    public $key = 'aafa40e33e550415219f8f21013bcd06';
    
    /**
     * 附近检索
     * 
     * @author kezhen.yi                  
     * @date 2015年12月18日 上午11:25:04        
     *
     */
    public function getAround($params){
        $tableid = '56dd00597bbf197f399d0f90';
        $key = 'aafa40e33e550415219f8f21013bcd06';
        
        //http_build_query();
        $postdata = array();
        $postdata['tableid'] = $tableid;
        $postdata['key'] = $key;
        $postdata['center'] = $params['currentpos'];
        $postdata['radius'] = $params['radius'];
        $postdata['limit'] = empty($params['limit'])?'10':$params['limit'];
        $postdata['page'] = empty($params['page'])?'1':$params['page'];
        
        
        $url = "http://yuntuapi.amap.com/datasearch/around";
        $url .='?'.http_build_query($postdata);
        
        $data = HttpCurl::get($url); 
        $data = json_decode($data,true);
        
        if($data['status']==1){
            return $data['datas'];
        }else{
            return array();
        }
        
    }
    
    /**
     * 创建单条数据
     * 
     * @author kezhen.yi                  
     * @date 2016年1月11日 上午1:41:22        
     *
     */
    public function createOne($data){
        $url = 'http://yuntuapi.amap.com/datamanage/data/create';
        $postdata = array();
        $postdata['key'] = $this->key;
        $postdata['tableid'] = $this->tableid;
        $postdata['loctype'] = 2;
        $postdata['data'] = json_encode($data);
        
        $data = HttpCurl::post($url,$postdata);
        
        return $data;
    }
    
    
    
}