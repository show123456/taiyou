<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	//****************
	include_once("../../includes/config.inc.php");
	
	include_once("./log_.php");
	//****************
	
	include_once "../weixinPay/WxPayPubHelper.php";

    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	
	//$log_name="./notify_url.log";//log文件路径
	
	//**************配置日志目录
	$log_name="../../data/image_c/notify_url_hpt.log";//log文件路径
	
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
	
	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//**************更改订单状态
			$orderTable=D('applist_hpt_shop_order');
			$resObj=simplexml_load_string($xml);
			$oid=$resObj->out_trade_no;
			$orderRow=$orderTable->find($oid);
			if($orderRow['is_lb']==0){
				//获得禁闭卡
				$cardData=array();
				$cardData['info']['type']=1;
				$cardData['info']['uid']=$orderRow['uid'];
				$cardData['info']['out_time']=time()+3600*24*30;
				D('sub_card')->add($cardData);
			}
			$orderTable->query("update applist_hpt_shop_order set is_pay=1 where id='{$oid}'");
			
			//增加累计消费
			$sumData=array();
			$sumModel=D('sub_sum');
			$sum_row=$sumModel->where("uid='".$orderRow['uid']."'")->dataRow();
			if($sum_row){
				$sumData['info']['id']=$sum_row['id'];
				$sumData['info']['money2']=$sum_row['money2']+$orderRow['money'];
				$sumData['info']['money3']=$sum_row['money3']+$orderRow['money'];
			}else{
				$sumData['info']['money2']=$orderRow['money'];
				$sumData['info']['money3']=$orderRow['money'];
			}
			if($sumData['info']['money2'] >= 368){
				//获得一张抢位卡
				$cardData=array();
				$cardData['info']['type']=2;
				$cardData['info']['uid']=$orderRow['uid'];
				$cardData['info']['out_time']=time()+3600*24*30;
				D('sub_card')->add($cardData);
				$sumData['info']['money2']-=368;
			}
			if($sumData['info']['money3'] >= 688){
				//获得一张提醒卡
				$cardData=array();
				$cardData['info']['type']=3;
				$cardData['info']['uid']=$orderRow['uid'];
				$cardData['info']['out_time']=time()+3600*24*30;
				D('sub_card')->add($cardData);
				$sumData['info']['money3']-=688;
			}
			$sumData['info']['uid']=$orderRow['uid'];
			$sumModel->add($sumData);
			//获取开乐迪券码
			$kldData=array();
			$kldData['info']['code']=strtolower(code_random(5));
			$kldData['info']['uid']=$orderRow['uid'];
			D('sub_kld')->add($kldData);
			
			$lbData['info']['uid']=$orderRow['uid'];
			D('sub_lb')->add($lbData);//今日礼包+1
			
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		}
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
?>