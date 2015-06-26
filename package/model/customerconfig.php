<?php

class Model_CustomerConfig extends Model_Table {
	
	protected $_name = 'customer_config';
	protected $customer_id = 1378;
	
	public function getRightToken(){
		$customer_config = new Model_CustomerConfig();
		$sql = "select customer_id,c_value,create_date from customer_config where customer_id='".$this->customer_id."' and c_type='token'";
		$configinfo = $customer_config->fetchRow($sql);
		//token 有记录
		if($configinfo){
			//token 未过期
			if(strtotime($configinfo['create_date'])>time()){
				$token = $configinfo['c_value'];
			//token 已经过期
			}else{
				$token = $this->gettoken($customer_config);
				if($token){	
					$configdata['c_value'] = $token;
					$configdata['create_date'] = date('Y-m-d H:i:s',time()+1800);	
					$customer_config->row_update($configdata,"customer_id='{$configinfo[customer_id]}' and c_type='token'");
				}
			}		
		}else{
		//没有记录
			$token = $this->gettoken($customer_config);
			if($token){
				$configdata['customer_id'] = $this->customer_id;
				$configdata['c_type'] = "token";
				$configdata['c_value'] = $token;
				$configdata['create_date'] = date('Y-m-d H:i:s',time()+1800);	
				$customer_config->insert($configdata);
			}	
		}
		return $token;
	}
	
	public function gettoken($customer_config){
		$sql = "select c_value from customer_config where customer_id='".$this->customer_id."' and c_type='appid'";
		$info = $customer_config->fetchRow($sql);
		$appinfo = explode(',',$info['c_value']);
		$appid = $appinfo[0];
		$appse = $appinfo[1];
		//获取token
		$jsoninfo = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appse");
		$json= json_decode($jsoninfo);
 	
		if($json->access_token){
			return $json->access_token;
		}
		return false;
	}

	public function https_request($url,$data = null){file_put_contents('cc.txt',$data,FILE_APPEND);
		/* $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output; */
	}
	
	/*发送客服消息
	 *$jsonContent string 发送内容
	 *$jsonTouser string 接收人openId
	 */
	public function sendCustomerMsg($jsonContent,$jsonTouser){
		$jsonMsgtype="text";
		$access_token=$this->getRightToken();
		$postUrl="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
		if(is_array($jsonTouser)){
			$jsonTouser=array_unique($jsonTouser);
			foreach($jsonTouser as $k=>$v){
				$jsonData="{
					\"touser\":\"$v\",
					\"msgtype\":\"$jsonMsgtype\",
					\"text\":
					{
						 \"content\":\"$jsonContent\"
					}
				}";
				$this->https_request($postUrl,$jsonData);
			}
		}else{
			$jsonData="{
				\"touser\":\"$jsonTouser\",
				\"msgtype\":\"$jsonMsgtype\",
				\"text\":
				{
					 \"content\":\"$jsonContent\"
				}
			}";
			return $this->https_request($postUrl, $jsonData);
		}
	}
}

