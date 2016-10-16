<?php
return array(
		/* 调试模式 */
		'APP_STATUS' => 'debug',
		'SHOW_RUN_TIME' => FALSE, // 运行时间显示
		'SHOW_ADV_TIME' => true, // 显示详细的运行时间
		'SHOW_DB_TIMES' => true, // 显示数据库查询和写入次数
		'SHOW_CACHE_TIMES' => true, // 显示缓存操作次数
		'SHOW_USE_MEM' => true, // 显示内存开销
		'SHOW_LOAD_FILE' => true, // 显示加载文件数
		'SHOW_FUN_TIMES' => true, // 显示函数调用次数
		'SHOW_PAGE_TRACE' => true, // 显示页面Trace信息
		'TRACE_PAGE_TABS' => array('base' => '基本', 'file' => '文件', 'think' => '流程', 'error' => '错误', 'sql' => 'SQL', 'debug' => '调试', 'object' => '变量'),
		'TRACE_ERROR_WAIT_TIME' => 150,  //成功，失败跳转调试时间
		'TMPL_STRIP_SPACE'   => true,    // 是否去除模板文件里面的html空格与换行
		'TRACE_EXCEPTION'    => true,
);
