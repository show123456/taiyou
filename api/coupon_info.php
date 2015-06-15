<?php
include_once '../includes/config.inc.php';
class coupon_info{
	private $info_type;
	private $info_id;
	public  $customer_id;
	public  $location_x;
	public  $location_y;
	public  $fromwhere;
	public  $fromuser;
	public  $web_domain;
	public  $timesign;
	public function getData($result){
		$this->info_type=$result['info_type'];
		$this->info_id=$result['info_id'];
		$this->customer_id=$result['customer_id'];
		$this->location_x=$result['location_x'];
		$this->location_y=$result['location_y'];
		$this->fromwhere=$result['fromwhere'];
		$this->fromuser=$result['fromuser'];
		$this->timesign=$result['timesign'];
		$this->web_domain=$_WGT['WEB_DOMAIN'];
		if($this->info_type=='coupon'){
			return $this->getCoupon();
		}
	}

	//优惠券回复
	public function getCoupon(){
		$CouponRecordTable = new Model_ApplistCouponRecord();	
		$CouponListTable = 	 new Model_ApplistCouponList();
		$cfromuser = $this->fromuser;
		$customer_id = $this->customer_id;
		//搜索条件
		$filter['where'] = " customer_id='$customer_id' and state='1' ";
		$sql=$CouponListTable->select($filter);
		//解析结果集
		$couponInfo = $CouponListTable->fetchRow($sql);
		
		$filter['where'] = "fromuser='$cfromuser' and pid='$couponInfo[id]'";
		$times = $CouponRecordTable->count($filter);
		//如果领取次数未满
		if($couponInfo && $couponInfo['end_date']>date("Y-m-d") && $times<$couponInfo['times'] && $couponInfo['stock']>$couponInfo['use_num']){
			//插入领取成功记录
			
			$data['pid'] = (int)$couponInfo['id'];
			$data['end_date'] = $couponInfo['end_date'];
			$data['state'] = 2;
			$data['customer_id'] = $customer_id;
			$data['fromuser'] = $cfromuser;
			$data['create_date'] = date("Y-m-d H:i:s");
			$data['coupon_name'] = str_inmysql($couponInfo['denomination']);
			$memberTable = new Model_Member();
			$nickname = $memberTable->fetchRow("select nickname from member where fromuser='$cfromuser' and customer_id='$customer_id'");
			$data['nickname'] = $nickname['nickname'];
			$id = $CouponRecordTable->upsert($data);
			$CouponListTable->query("update applist_coupon_list set use_num=use_num+1 where id='$couponInfo[id]' and customer_id='$customer_id'");
			//获取优惠券 规则信息 返回微信单图文格式
			$CouponTable = 	 new Model_ApplistCoupon();
			$info = $CouponTable->fetchRow("select * from applist_coupon where customer_id='$customer_id'");
		//返回微信数据
			$data['msgtype']	= 'news';
			$data['title']		= $info['title'];
			$data['description']= $info['info'];
			$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST']. "/data/image_c/" .$info['pic'];
			$data['url'] = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/coupon/index.php?id=".$id."&pid=".$data['pid']."&fromuser=".$this->fromuser."&sign=".$this->timesign;

			return $data;
		}else{
			$data['msgtype'] = "text";
			$data['content'] = $couponInfo['cue'];
			return $data;
		}
		
		
	}

}
?>