<?php
return array(
		'upload_maxSize'   => 3*1024*1024,
		'upload_exts'      => array('jpg', 'gif', 'png', 'jpeg'),
		'upload_rootPublicPath'  => str_replace('\\', '/', dirname(THINK_PATH).'/statics/public/'),
		'upload_rootPath'  => str_replace('\\', '/', dirname(THINK_PATH).'/statics/'),
		/*默认上传配置*/
		'upload_defalut' => array(
				'exts' => array('jpg', 'gif', 'png', 'jpeg'),
				'path' => '/uploads/',
				'maxSize' => 3*1024*1024
		),
		/*商品图片上传配置*/	
		'upload_goods'=>array(
				'exts' => array('jpg', 'gif', 'png', 'jpeg'),
				'path' => '/uploads/catalog/',
				'maxSize' => 3*1024*1024
		),
		
		'upload_cate'=>array(
				'exts' => array('jpg', 'gif', 'png', 'jpeg'),
				'path' => '/uploads/cate/',
				'maxSize' => 3*1024*1024,
		),
		
        'upload_merchant'=>array(
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'path' => '/uploads/merchant/',
            'maxSize' => 3*1024*1024,
        ),
    
        'upload_spa'=>array(
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'path' => '/uploads/spa/',
            'maxSize' => 3*1024*1024,
        ),
    
        'upload_share'=>array(
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'path' => '/uploads/share/',
            'maxSize' => 3*1024*1024,
        ),
		
);