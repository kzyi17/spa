<?php
return array(
		//'配置项'=>'配置值'
		// 关闭多模块访问
	    'MULTI_MODULE'       =>  false,
		'DEFAULT_MODULE'     => 'Index', //默认模块
		'DEFAULT_CONTROLLER' => 'Default', // 默认控制器名称
		'DEFAULT_ACTION'     => 'index', // 默认操作名称
		'URL_MODEL'          => '2',     //URL模式
		'SESSION_AUTO_START' => true,    //是否开启session
		'LOAD_EXT_CONFIG'    => 'db,users,menu,upload,site,url,log',    //加载扩展配置文件
		'AUTH_CODE'          => 'U!T$iu(*)Q5@8K$b%p^3Rq#%quit',
		'PAGE_SIZE'          => 10,   // 分页每页最大数

		/** 短信接口配置 */
		'ACCOUNT_SID'           => 'aaf98f8947473c1301474f35d9b70225',  //主帐号
		'ACCOUNT_TOKEN'	        => '394a532d03de4bf49b2d6994a51180ff',  //主帐号Token
		'APP_ID'	            => '8a48b551473976010147516bea090921',  //应用Id
		'SERVER_IP'				=> 'app.cloopen.com',                   //请求地址
		'SERVER_PORT'			=> '8883',                              //请求端口
		'SOFT_VERSION'			=> '2013-12-26',
		'TPL_CONFIG' 			=> array(
			'deliver'  =>'3783',
			'forgetpassword'  =>'2866',
		),
		/** Auth认证配置  */
		'AUTH_ON' => true,          //认证开关
		'AUTH_TYPE' => 1,           //认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' => 'auth_group', //用户组数据表名
		'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户组明细表
		'AUTH_RULE' => 'auth_rule', //权限规则表
		'AUTH_USER' => 'users'      //用户信息表
		 

);