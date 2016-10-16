<?php
/**
 * 短信验证码相关配置
 */
return array(
    'SMS_VALIDITY'  =>  600,        //短信验证码有效期，单位为秒
    'SMS_INTERVAL'  =>  60,        //短信发送间隔时间，单位为秒
    //短信接口配置
    'SMS_ACCOUNT_SID'           => 'aaf98f895147cd2a015157a372b227bb',  //主帐号
    'SMS_ACCOUNT_TOKEN'	        => 'fbd9c8f2f6674e759fa3faaea65b7ab0',  //主帐号Token
    'SMS_APP_ID'	            => '8a48b55152a0e97f0152a396f3cf0229',  //应用Id
    'SMS_SERVER_IP'				=> 'app.cloopen.com',                   //请求地址
    'SMS_SERVER_PORT'			=> '8883',                              //请求端口
    'SMS_SOFT_VERSION'			=> '2013-12-26',                        //版本号
    'SMS_TEMPID_COMM'			=> '69559',                             //短信模板ID
    
);