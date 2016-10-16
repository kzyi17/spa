<?php
/**
 * HTTP 工具类
 * --Curl方式
 * --单例模式  
 *   
 * @author kezhen.yi <2015年7月22日 上午11:03:37>
 * @copyright Copyright (C) 2015 mywork99.com
 * @version 1.0 
 */
namespace Common\Util;

class HttpCurl
{
    
    //协议
    public static $protocol=array();
    
    public static function __callstatic($action,$params){
        $action = get_called_class().':'.$action;
        return "can't ask a not exists method '{$action}'";
    }

    //设置单例模式
    public static function getHttp(){
        if (!isset(HttpCurl::$protocol['http']) || HttpCurl::$protocol['http'] === null) {
            HttpCurl::$protocol['http'] = new HttpCurl();
        } 
        return HttpCurl::$protocol['http'];
    }
    
    /**
     * 发送GET请求
     * 
     * @author kezhen.yi                  
     * @date 2015年7月22日 上午11:15:15        
     *
     */
    public static function get($url)
    {
        return HttpCurl::getHttp()->curl_request($url);
    }
    
    /**
     * 发送POST请求
     *
     * @author kezhen.yi
     *
     */
    public static function post($url,$postData)
    {
        return HttpCurl::getHttp()->curl_request($url,$postData);
    }
    
    /**
     * 
     * 
     * @author kezhen.yi                  
     * @date 2015年12月18日 下午12:00:20
     * @param string $url              参数1：访问的URL
     * @param string $post             参数2：post数据(不填则为GET)
     * @param string $cookie           参数3：提交的$cookies
     * @param number $returnCookie     参数4：是否返回$cookies
     * @return string|unknown
     *
     */
    function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        //curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
//             curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }
    
}
