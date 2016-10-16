<?php
/**
 * @copyright (c) 2011 jooyea.cn
 * @file countsum.php
 * @brief 计算购物车中的商品价格
 * @author chendeshan
 * @date 2011-02-24
 * @version 0.6
 */
class CountSum
{
	//用户ID
	public $user_id = '';

	//用户组ID
	public $group_id = '';

	//用户组折扣
	public $group_discount = '';

	/**
	 * 构造函数
	 */
	public function __construct($memberId)
	{
		$this->user_id = $memberId?$memberId:0;

		//获取用户组ID及组的折扣率
		if($this->user_id != null)
		{
			
		$member_points = M('Members')->where(array('member_id' => $this->user_id))->getField('my_points');
			
			$level =  M('distribution_level')->where(array('min_points'=>array('ELT',$member_points),array('max_points'=>array('EGT',$member_points))))->find();
			if(!$level){
				$level =  M('distribution_level')->where(array('min_points'=>array('ELT',$member_points)))->find();
			}
			if($level){
				$this->group_id       = $level['level_id'];
				$this->group_discount = $level['discount'] * 0.01;
			}
		}
	}

	/**
	 * 获取会员组价格
	 * @param $id   int    商品或货品ID
	 * @param $type string goods:商品; product:货品
	 * @return float 价格
	 */
	public function getGroupPrice($id,$type = 'goods')
	{

		if(!$this->group_discount){
			return null;
		}
		$where['product_id'] = $id;
		$where['is_del'] = 0;
		//促销价
		if($type == 'goods'){
			
			$promoteRow = D('Product')->where($where)->field('is_promote,promote_start_date,promote_end_date,promote_price')->find();
			if($promoteRow['is_promote']==1 && time()>$promoteRow['promote_start_date'] && time()<$promoteRow['promote_end_date']){
				return $promoteRow['promote_price'];
			}
		}else{
			$goodsid = D('product_stocks')->where(array('product_id'=>$id))->getField('goods_id');
			$where['product_id'] = $goodsid;
			$promoteRow = D('Product')->where($where)->field('is_promote,promote_start_date,promote_end_date,promote_price')->find();
			if($promoteRow['is_promote']==1 && time()>$promoteRow['promote_start_date'] && time()<$promoteRow['promote_end_date']){
				return $promoteRow['promote_price'];
			}
		}
		//1,查询特定商品的组价格
		$groupPriceDB = M('group_price');
		if($type == 'goods')
		{
			$discountRow = $groupPriceDB->where(array('goods_id'=>$id,'product_id'=>0,'distribution_level_id'=>$this->group_id))->find();
		}
		else
		{
			$discountRow = $groupPriceDB->where(array('product_id'=>$id,'distribution_level_id'=>$this->group_id))->find();
		}

		if($discountRow)
		{
			return $discountRow['price'];
		}
		//2,根据会员折扣率计算商品折扣
		if($this->group_discount)
		{
			if($type == 'goods')
			{
				$product  = D('Product');
				$goodsRow = $product->where(array('product_id' =>$id))->field('sell_price')->find();
				return $goodsRow ? ($goodsRow['sell_price'] * $this->group_discount) : null;
			}
			else
			{
				$productDB  = M('product_stocks');
				$productRow = $productDB->where(array('product_id' =>$id))->field('sell_price')->find();
				return $productRow ? ($productRow['sell_price'] * $this->group_discount) : null;
			}
		}
		return null;
	}

	/**
	 * @brief 计算商品价格
	 * @param Array $buyInfo ,购物车格式
	 * @return array or bool
	 */
	public function goodsCount($buyInfo)
	{
		$this->sum           = 0;       //原始总额(优惠前)
		$this->priceone      = array();        //原始总额(优惠前)
		$this->final_sum     = 0;       //应付总额(优惠后)
    	$this->weight        = 0;       //总重量
    	$this->reduce        = 0;       //减少总额
    	$this->count         = 0;       //总数量
    	$this->promotion     = array(); //促销活动规则文本
    	$this->proReduce     = 0;       //促销活动规则优惠额
    	$this->point         = 0;       //增加积分
    	$this->exp           = 0;       //增加经验
    	$this->isFreeFreight = false;   //是否免运费

		$user_id      = $this->user_id;
		$group_id     = $this->group_id;
    	$goodsList    = array();
    	$productList  = array();

		/*开始计算goods和product的优惠信息 , 会根据条件分析出执行以下哪一种情况:
		 *(1)查看此商品(货品)是否已经根据不同会员组设定了优惠价格;
		 *(2)当前用户是否属于某个用户组中的成员，并且此用户组享受折扣率;
		 *(3)优惠价等于商品(货品)原价;
		 */
		$att = array();
		//获取商品或货品数据
		/*Goods 拼装商品优惠价的数据*/
    	if(isset($buyInfo['goods']['id']) && $buyInfo['goods']['id'])
    	{
    		//购物车中的商品数据
    		$goodsIdStr = join(',',$buyInfo['goods']['id']);
    		$goodsObj   = D('Product');
    		$where['product_id'] = array('in',$goodsIdStr);
    		$goodsList  = $goodsObj->where($where)->field('name,product_id,img,sell_price,point,weight,store_nums,exp,photo_id')->select();
			
    		//开始优惠情况判断
    		foreach($goodsList as $key => $val)
    		{
    			$groupPrice                = $this->getGroupPrice($val['product_id'],'goods');
    			$goodsList[$key]['reduce'] = $groupPrice === null ? 0 : $val['sell_price'] - $groupPrice;
    			$goodsList[$key]['count']  = $buyInfo['goods']['data'][$val['product_id']]['count'];
    			$current_sum_all           = $goodsList[$key]['sell_price'] * $goodsList[$key]['count'];
    			$current_reduce_all        = $goodsList[$key]['reduce']     * $goodsList[$key]['count'];
    			$goodsList[$key]['sum']    = $current_sum_all - $current_reduce_all;
				$photo = M('product_photo')->where('photo_id ='.$val['photo_id'])->find();
				$goodsList[$key]['img'] = $photo['folder'].$photo['name'].'.'.$photo['type'];
				//商品实付金额
				
				$att[] = $groupPrice ? $groupPrice : $val['sell_price'];
				$this->priceone = $att;
    			//全局统计
		    	$this->weight += $val['weight'] * $goodsList[$key]['count'];
		    	$this->point  += $val['point']  * $goodsList[$key]['count'];
		    	$this->exp    += $val['exp']    * $goodsList[$key]['count'];
		    	$this->sum    += $current_sum_all;
		    	$this->reduce += $current_reduce_all;
		    	$this->count  += $goodsList[$key]['count'];
		    }
    	}
		/*Product 拼装商品优惠价的数据*/
    	if(isset($buyInfo['product']['id']) && $buyInfo['product']['id'])
    	{
    		//购物车中的货品数据
    		$productIdStr = join(',',$buyInfo['product']['id']);
    		$productObj   = M('product_stocks');
    		$where['product_id'] = array('in',$productIdStr);
    		$productList  = $productObj->where($where)->select();
			//dump($productList);
    		//开始优惠情况判断
    		foreach($productList as $key => $val)
    		{
    			$groupPrice                  = $this->getGroupPrice($val['product_id'],'product');
				$productList[$key]['reduce'] = $groupPrice === null ? 0 : $val['sell_price'] - $groupPrice;

    			$productList[$key]['count']  = $buyInfo['product']['data'][$val['product_id']]['count'];

    			$current_sum_all             = $productList[$key]['sell_price']  * $productList[$key]['count'];
				
    			$current_reduce_all          = $productList[$key]['reduce']      * $productList[$key]['count'];
    			$productList[$key]['sum']    = $current_sum_all - $current_reduce_all;
				$goodsObj   = D('Product');
    			$where['product_id'] = $val['goods_id'];
    			$goodsLists  = $goodsObj->where($where)->field('point,exp,photo_id,name')->find();
				$photo = M('product_photo')->where('photo_id ='.$goodsLists['photo_id'])->find();
				$productList[$key]['img'] = $photo['folder'].$photo['name'].'.'.$photo['type'];
				//商品实付金额
				$att[] = $groupPrice ? $groupPrice : $val['sell_price'];
				$this->priceone = $att;
    			//全局统计
    			
		    	$this->weight += $val['weight'] * $productList[$key]['count'];
		    	$this->point  += $goodsLists['point']  * $productList[$key]['count'];
		    	$this->exp    += $goodsLists['exp']    * $productList[$key]['count'];
// 		    	$this->weight += $val['weight'] * $productList[$key]['count'];
// 		    	$this->point  += $val['point']  * $productList[$key]['count'];
// 		    	$this->exp    += $val['exp']    * $productList[$key]['count'];
		    	$this->sum    += $current_sum_all;
		    	$this->reduce += $current_reduce_all;
		    	$this->count  += $productList[$key]['count'];
		    }
    	}
    	$final_sum = $this->sum - $this->reduce;
    	//总金额满足的促销规则
    	if($user_id)
    	{
// 	    	$proObj = new ProRule($final_sum);
// 	    	$proObj->setUserGroup($group_id);
// 	    	$this->isFreeFreight = $proObj->isFreeFreight();
// 	    	$this->promotion = $proObj->getInfo();
// 	    	$this->proReduce = $final_sum - $proObj->getSum();
    		$this->isFreeFreight = false;
    		$this->promotion = array();
    		$this->proReduce = 0;
    	}
    	else
    	{
	    	$this->promotion = array();
	    	$this->proReduce = 0;
    	}

    	$this->final_sum = $final_sum - $this->proReduce;

    	return array(
    		'final_sum'  => $this->final_sum,
    		'promotion'  => $this->promotion,
    		'proReduce'  => $this->proReduce,
    		'sum'        => $this->sum,
    		'goodsList'  => $goodsList,
    		'productList'=> $productList,
    		'count'      => $this->count,
    		'reduce'     => $this->reduce,
    		'weight'     => $this->weight,
    		'freeFreight'=> $this->isFreeFreight,
    		'point'      => $this->point,
    		'exp'        => $this->exp,
			'priceone'        => $this->priceone,
    	);
	}

	//购物车计算
	public function cart_count()
	{

    }

    //计算非购物车中的商品
    public function direct_count($id,$type,$buy_num = 1,$promo='',$active_id='')
    {
    	/*正常购买流程*/

		return $result;
    }

    /**
     * 计算订单信息,其中部分计算都是以商品原总价格计算的$goodsSum
     * @param $goodsSum float 商品原总金额
     * @param $goodsFinalSum 商品应付总金额
     * @param $goodsWeight float 商品总重量
     * @param $province_id int 省份ID
     * @param $delievery_id int 配送方式ID
     * @param $payment_id int 支付ID
     * @param $is_freeDelievery boolean 是否免运费
     * @param $is_insured boolean 是否有保价
     * @param $is_invoice boolean 是否要发票
     * @param $discount float 订单的加价或者减价
     * @return $result 最终的返回数组
     */
    public function countOrderFee($goodsSum,$goodsFinalSum,$goodsWeight,$province_id,$delivery_id,$payment_id,$is_freeDelievery,$is_insured,$is_invoice,$discount = 0)
    {
    	//最终的返回数组
		
    	$result = array(
    		//原本运费
    		'deliveryOrigPrice' => 0,

    		//实际运费
    		'deliveryPrice' => 0,

    		//保价
    		'insuredPrice' => 0,

    		//税金
    		'taxPrice' => 0,

    		//支付手续费
    		'paymentPrice' => 0,

    		//最终订单金额
    		'orderAmountPrice' => 0
    	);

		//计算运费和保价
    	$Delivery = D('Delivery');
    	$deliveryList = $Delivery->getDeliverys($province_id,$goodsWeight,$goodsSum);

    	//运费设置
    	$result['deliveryOrigPrice'] = $deliveryList[$delivery_id]['price'];
    	$result['deliveryPrice']     = $deliveryList[$delivery_id]['price'];

    	//免运费
    	if($is_freeDelievery == true)
    	{
	    	$result['deliveryPrice'] = 0;
    	}
		//dump($is_insured);
    	//需要保价
    	if($is_insured == 1)
    	{
    		$result['insuredPrice'] = $deliveryList[$delivery_id]['protect_price'];
    	}
		//dump($is_invoice);
    	//获取税率$tax
    	if($is_invoice == 1)
    	{
    		$result['taxPrice'] = self::getGoodsTax($goodsSum);
    	}
		$paymentData = M('payment')->where(array('id' => $payment_id))->field('type')->find();
		//非货到付款的线上支付方式手续费
		if($paymentData['type'] != 0)
		{
			$result['paymentPrice'] = self::getGoodsPaymentPrice($payment_id,$goodsSum);
		}else if($paymentData['type']==0){
			$result['paymentPrice'] = self::getGoodsPaymentPrice($payment_id,$goodsSum) + $goodsFinalSum *C("amount_tax")*0.01;
			//echo 'ttt';
		}

		//最终订单金额计算
		$order_amount = $goodsFinalSum + $result['deliveryPrice'] + $result['insuredPrice'] + $result['taxPrice'] + $result['paymentPrice'] + $discount;
		$result['orderAmountPrice'] = $order_amount;

		return $result;
    }

    /**
     * 获取商品的税金
     * @param $goodsSum float 商品总价格
     * @return $goodsTaxPrice float 商品的税金
     */
    public static function getGoodsTax($goodsSum)
    {
    	$goodsTaxPrice = 0;
		$tax_per = C("tax");
		$goodsTaxPrice = $goodsSum * ($tax_per * 0.01);
		return $goodsTaxPrice;
    }

    /**
     * 获取商品金额的支付费用
     * @param $payment_id int 支付方式ID
     * @param $goodsSum float 商品总价格
     * @return $goodsPayPrice
     */
    public static function getGoodsPaymentPrice($payment_id,$goodsSum)
    {
		$paymentObj = D('payment');
		$paymentRow = $paymentObj->where(array('id' => $payment_id))->field('poundage,poundage_type')->find();
		if($paymentRow)
		{
			if($paymentRow['poundage_type'] == 1)
			{
				//按照百分比
				return $goodsSum * ($paymentRow['poundage']);
			}
			//按照固定金额
			return $paymentRow['poundage'];
		}
		return 0;
    }
}