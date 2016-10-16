<?php
return array(
	'LOG'=>array(
		'1'   => array(
				'name'  => '商品操作',
				'type'  => array(
						'1'  => '修改',
						'2'  => '彻底删除',
						'3'  => '添加',
						'4'  => '上架',
						'5'  => '下架',
						'6'  => '移到回收站',
						'7'  => '恢复商品',
				)
		),
		'2'   => array(
				'name'  => '订单操作',
				'type'  => array(
						'1'  => '修改',
						'2'  => '删除',
						'3'  => '添加',
						'4'  => '移到回收站',
						'5'  => '恢复订单',
						'6'  => '完成',
						'7'  => '作废',
						'8'  => '支付',
						'9'  => '发货',
						'10'  => '退款',
				)
		),
		'3'   => array(
				'name'  => '售后操作',
				'type'  => array(
						'1'  => '退款',
						'2'  => '退货',
						'3'  => '换货',
						'4'  => '售后失败',
				)
		),
		'4'   => array(
				'name'  => '会员操作',
				'type'  => array(
						'1'  => '修改',
						'2'  => '彻底删除',
						'3'  => '添加',
						'4'  => '移到回收站',
						'5'  => '恢复会员',
						'6'  => '降低积分',
						'7'  => '增加积分',
						'8'  => '充值',
						'9'  => '消费',
						'10'  => '增加积分',
						'11'  => '分销等级修改',
						'12'  => '分销等级添加',
						'13'  => '分销等级删除',
						'14'  => '提现删除',
						'15'  => '处理',
				)
		),
		'5'   => array(
				'name'  => '导出操作',
				'type'  => array(
						'1'  => '供应商导出',
						'2'  => '会员统计导出',
				)
		),
		'6'   => array(
				'name'  => '配置操作',
				'type'  => array(
						'1'  => '支付方式修改',
						'2'  => '配送方式修改',
						'3'  => '配送方式添加',
				)
		),
		'7'   => array(
				'name'  => '权限操作',
				'type'  => array(
						'1'  => '用户组启用',
						'2'  => '用户组禁用',
						'3'  => '用户组修改',
						'4'  => '用户组添加',
						'5'  => '用户组删除',
						'6'  => '用户组中规则授权',
						'7'  => '用户组中规则禁用',
						'8'  => '用户/组关联中用户组授权',
						'9'  => '用户/组关联中用户组禁用',
						'10'  => '规则启用',
						'11'  => '规则禁用',
						'12'  => '规则修改',
						'13'  => '规则添加',
						'14'  => '规则删除',
				)
		),
		'8'   => array(
				'name'  => '管理员操作',
				'type'  => array(
						'1'  => '管理员修改',
						'2'  => '管理员添加',
						'3'  => '管理员删除',
						'4'  => '管理员类型修改',
						'5'  => '管理员类型添加',
						'6'  => '管理员类型删除',
						'7'  => '管理员启用',
						'8'  => '管理员禁用',
						
				)
		),
	),
	//日志记录状态，0为取消记录，其他null,1等为记录
	'LOG_STATUS'=>array(
		'1'   => array(
				'status'  => '1',
				'val_status'  => array(
						'1'  => '1',
						'2'  => '1',
						'4'  => '1',
						'5'  => '1',
						'6'  => '1',
						'7'  => '1',
				)
		),
		
	)	
);