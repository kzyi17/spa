<?php
return array(
		'admin_big_menu'   => array(
				'Default'  => '首页',
		        'Merchant' => '商家管理',
		        'Adgoods'  => '广告管理',
		        'Share'    => '分享管理',
// 		        'Spa'      => '美容管理',
// 				'Product'  => '商品管理',
// 				'Order'    => '订单管理',
// 				'Member'   => '会员管理',
// 				'Supplier' => '供应商管理',
// 				'Market'   => '报表统计',
// 				'System'   => '系统设置',
				//	'Tool'     => '工具管理',
				'Auth'   => '权限管理',
		),
		'admin_sub_menu' => array(
				'Default' => array(
						'index'  => '系统信息',
						'myInfo' => '修改密码',
						'cache'  => '缓存清理',
						//	'actionLog' => '操作日志',
				),
    		    'Merchant' => array(
    		        'merchantList'    => '商家列表',
    		        'merchantAdd'     => '添加商家',
    		        'spaList'         => '美容项目列表',
    		        'spaAdd'          => '添加美容项目',
    		        
    		    ),
    		    'Adgoods'  => array(
    		        'adList'   => '广告商品列表',
    		        'adgoodsAdd'   => '添加广告商品',
    		    ),
    		    'Share'  => array(
    		        'articleList'   => '文章管理',
    		        'articleCateList'   => '文章分类管理',
    		        'goodsList'   => '积分商城管理',
    		        'admanage'   => '广告管理',
    		        'index'   => '文章访问统计',
    		        'exchange' => '兑换信息',
    		    ),
//     		    'Spa' => array(
//     		        'spaList'    => '美容项目列表',
//     		        'spaAdd'     => '添加美容项目',
//     		    ),
				'Product' => array(
						'productList' => '商品列表',
						'productAdd'  => '添加商品',
						'cateList'    => '商品分类',
						'cateAdd'     => '添加分类',
						'attribute'   => '商品属性',
						'specList'    => '商品规格',
						'brandList'   => '品牌列表',
						'brandCate'   => '品牌分类',
						'couponList'  => '优惠活动',
						'packageList'  => '优惠套餐'
				),
// 				'Order'  => array(
// 						'index'   => '订单列表',
// 						'orderEdit'   => '添加订单',
// 						'recycle'   => '回收站',
// 						'orderCollectionlist'   => '收款单',
// 						//'orderRefundmentlist'   => '退款单',
// 						'orderDeliveryList'   => '发货单',
// 						//'refundmentList'   => '退款申请列表',
// 						'afterSaleList'   => '售后列表',

// 				),
// 				'Member' => array(
// 						'index'     => '会员列表',
// 						'level'     => '会员等级',
// 						'disLevel'  => '分销等级',
// 						'points'    => '积分明细',
// 						'recycle'   => '会员回收站',
// 						'distribution'=> '返利管理',
// 						'withdraw'=> '提现管理',
// 				),
// 				'Supplier' => array(
// 						'index'       => '供应商列表',
// 						'supplierType'=> '供应商业务类型',
// 						'type'        => '供应商类型',
// 				),
// 				'Market' => array(
// 						'index'         => '会员统计',
// 						'member_consume'=> '会员注册统计',
// 						'detail'        => '订单统计',
// 						'consume'       => '消费统计',
// 						'members'       => '近期生日会员统计',
// 						'adminLog'       => '后台操作日志',
// 						),
// 				'System2' => array(
// 						'index'     => '网站基础设置',
						
// 						),
// 				'Auth' => array(
// 						'index'    => '管理员列表',
// 						'group'    => '用户组管理',
// 						'access'   => '用户/组关联',
// 						'rule'     => '规则管理',
// 						),
				'System'   => array(
						array(
								'info' => '网站管理',
								'list' => array(
										'index'     => '网站设置',
										'replaceContent'  => '产品内容替换',
								),
						),
						array(
								'info' => '支付管理',
								'list' => array(
										'paymentList'     => '支付方式',
								),
						),
						array(
								'info' => '配送管理',
								'list' => array(
										'deliveryList' => '配送方式',
										'freightList'  => '物流公司',
								),
						),
						array(
								'info' => '区域管理',
								'list' => array(
										'areaList' => '地区列表',
								),
						),
						array(
								'info' => '广告管理',
								'list' => array(
										'advertList' => '广告列表',
										'advertPosition' => '广告位',
								),
						),

				),
		),
);