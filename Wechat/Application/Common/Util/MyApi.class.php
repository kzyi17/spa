<?php
/**
 * API基类
 *
 * @author kezhen.yi 
 * @copyright Copyright (C) 
 * @version 1.0
 */
namespace Common\Util;

use Think\Log;
class MyApi{
    //协议
    public static $protocol=array();
    
    public static function __callstatic($action,$params){
        $action = get_called_class().':'.$action;
        return "can't ask a not exists method '{$action}'";
    }
    
    public static function getHttp($httpConfig=array()){
        if (!isset(MyApi::$protocol['http']) || MyApi::$protocol['http'] === null) {
            MyApi::$protocol['http'] = new HttpCurl($httpConfig);
        } else {
            MyApi::$protocol['http']->setConfig($httpConfig);
        }
        return MyApi::$protocol['http'];
    }
    
    /**
     * 接口调用静态方法
     *
     * @author kezhen.yi
     *
     */
    public static function getContent($api,$postData)
    {
//         $APPID = C('APIAPPID');
        $jsonData = json_encode($postData);
//         $SIGN = MyApi::getSign($jsonData);
    
        $url = C('API_SERVER')."$api?&token=asdqwe";
        
        $result = MyApi::getHttp()->post($url, json_encode($postData,JSON_UNESCAPED_UNICODE));
        $result = json_decode($result, true);
        
        if($result['errcode']){
            Log::record("API:$url");
            Log::record("POSTDATA:".json_encode($postData));
            Log::record("RESULT:errcode(".$result['errcode']."),errmsg(".$result['errmsg'].")");
        }
    
        return $result;
    }
    
    /**
     * 加密验证字符串
     *
     * @author kezhen.yi
     *
     */
    public static function getSign($data=null){
        $APPID = C('APIAPPID');
        $KEY = C('APIKEY');
    
        $SIGN = md5("app=$APPID&body=$data&key=$KEY");
        $SIGN = strtoupper($SIGN);
    
        return $SIGN;
    }

    
    
}