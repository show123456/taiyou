<?php
include_once '../includes/config.inc.php';
class special_info{
	private $info_type;
	private $info_id;
	public  $customer_id;
	public  $location_x;
	public  $location_y;
	public  $fromwhere;
	public  $fromuser;
	public  $keyword;
	public  $web_domain;
	public function getData($result){
		$this->info_type=$result['info_type'];
		$this->info_id=$result['info_id'];
		$this->customer_id=$result['customer_id'];
		$this->location_x=$result['location_x'];
		$this->location_y=$result['location_y'];
		$this->fromwhere=$result['fromwhere'];
		$this->fromuser=$result['fromuser'];
		$this->keyword=$result['keyword'];
		$this->web_domain=$_WGT['WEB_DOMAIN'];
		
		//快递
		$express_words = explode("+",$this->keyword);
		$express=array('EMS','圆通','顺丰','申通','中通','韵达','天天','汇通');
		if(in_array($express_words[0],$express) and is_numeric($express_words[1])){
			$express_words['0']=str_replace($express,array('ems','yt','sf','sto','zto','yd','tt','ht'),$express_words['0']);
			return $this->getExpress($express_words['0'],$express_words['1']);
		}
		//天气
		$area_words=explode("+",$this->keyword);
		if($area_words['1']=='天气'){
			return $this->getWeather($area_words['0']);
		}
		//wifi
		$wifi_words=explode("+",$this->keyword);
		if(strtolower($wifi_words['0'])=='wifi'){
			return $this->getWifi($wifi_words['1']);
		}
	}
	//快递
	public function getExpress($com,$no){
		$appRecord = new Model_AppRecord();
		//当前时间
        $now = date("Y-m-d H:i:s");
        $express_data = $appRecord->fetchRow("select * from app_record where app_id='13' and customer_id='$this->customer_id' and state='1' and expired_date>='$now' limit 1");
		
		if($express_data){
		//如果客户已开通此应用
            $express_url = "http://v.juhe.cn/exp/index?key=d35f5afd348439c1f1f4b246503553b6&com=" . "$com" . "&no=" . "$no";
			$get_result = file_get_contents($express_url);
            if (strstr($get_result, 'resultcode')) {
				$data['msgtype']='text';
                $result_array = json_decode($get_result, true);
                if ($result_array['resultcode'] == '200') {
                    if (empty($result_array['result']['list'])) {
                        $data['content'] = '未查找到该单号的快递信息,请确认输入的快递单号正确';
                    } else {
                        foreach ($result_array['result']['list'] as $key => $value) {
                            $line = $key != '0' ? "--------------\n" : '';
                            $data['content'] .= $line . $value['datetime'] . "：" . $value['remark'];
                            if (!empty($value['zone'])) {
                                $data['content'] .= "【$value[zone] 】\n";
                            } else {
                                $data['content'] .= "\n";
                            }
                        }
                    }
                } else if ($result_array['resultcode'] == '101') {
                    $data['content'] = 'Key值错误';
                } else if ($result_array['resultcode'] == '201') {
                    $data['content'] = '请填写正确的快递公司';
                } else if ($result_array['resultcode'] == '202') {
                    $data['content'] = '快递单号错误,请查证';
                } else if ($result_array['resultcode'] == '204') {
                    $data['content'] = '网络错误,请重试';
                } else if ($result_array['resultcode'] == '205') {
                    $data['content'] = '未查找到该单号的快递信息,请确认输入的快递单号正确';
                }
            } else {
                $data = '';
            }
            return $data;
		}
		
	}

	//天气
	public function getWeather($area) {
		$appRecord = new Model_AppRecord();
        $now = date("Y-m-d H:i:s");
        $weather_data = $appRecord->fetchRow("select * from app_record where app_id='11' and customer_id='$this->customer_id' and state='1' and expired_date>='$now' limit 1");
		
		if($weather_data){
		//如果客户已开通此应用
            $weather_url = "http://v.juhe.cn/weather/index?cityname=$area&key=0fed94aea09832a3528b8a6f2fdbb155";
			$get_result = file_get_contents($weather_url);
            if (strstr($get_result, 'resultcode')) {
				$data['msgtype']='text';
                $result_array = json_decode($get_result, true);
                if ($result_array['resultcode'] == '200') {
                    $data['content'] = $result_array['result']['today']['city'] . "天气\n" . $result_array['result']['today']['date_y'] . " " . $result_array['result']['today']['week'] . " " . $result_array['result']['sk']['time'] . "发布";
                    foreach ($result_array['result']['future'] as $key => $value) {
                        $data['content'] .= "\n--------------\n" . $value['week'] . "：" . $value['weather'] . "，" . $value['wind'] . "，" . $value['temperature'];
                    }
                } else if ($result_array['resultcode'] == '101') {
                    $data['content'] = 'Key值错误';
                } else if ($result_array['resultcode'] == '201') {
                    $data['content'] = '请输入正确的城市名';
                } else if ($result_array['resultcode'] == '202') {
                    $data['content'] = '未查找到该城市天气信息,请确认输入的城市名正确';
                } else if ($result_array['resultcode'] == '203') {
                    $data['content'] = '查询失败';
                }
            } else {
                $data = '';
            }
			return $data;
		}
	}
	

	//wifi
	function getWifi($wifi_keyword){
		
		$xmllat = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address=$wifi_keyword&output=xml&ak=eqvqjIL5vrqQtDMuuEmq9Yht");

		$dom = new DOMDocument('1.0','utf-8');
		$dom->loadXML($xmllat);
		$lat = $dom->getElementsByTagName('lat');
		$lat = $lat->item(0)->nodeValue;
		
		$lng = $dom->getElementsByTagName('lng');
		$lon = $lng->item(0)->nodeValue;

		$appRecord = new Model_AppRecord();
		//当前时间
        $now = date("Y-m-d H:i:s");
        $wifi_data = $appRecord->fetchRow("select * from app_record where app_id='12' and customer_id='$this->customer_id' and state='1' and expired_date>='$now' limit 1");
		if($wifi_data){
			$r	 = 1200;
			$type=1;
            $express_url = "http://apis.juhe.cn/wifi/local?key=2d13fe4d819be1e24e2f702993405c94&lon=$lon&lat=$lat&r=$r&type=$type";
			
			$get_result = file_get_contents($express_url);
            if (strstr($get_result, 'resultcode')) {
				$data['msgtype']='text';
                $result_array = json_decode($get_result, true);
				
                if ($result_array['resultcode'] == '200') {
                    if (empty($result_array['result']['data'])) {
                        $data['content'] = '未查到该地点附近的wifi';
                    } else {
                        foreach ($result_array['result']['data'] as $key => $value) {
                           
							$data['content'] .= "wifi : ".$value['name'];
							$data['content'] .= " 地址 : ".$value['address'];
							$data['content'] .= " 介绍 : ".$value['intro'];
							$data['content'] .="\n--------------\n";
                        }
                    }
                } else if ($result_array['error_code'] == '201801') {
                    $data['content']  = '错误的经纬度';
                } else if ($result_array['error_code'] == '201802') {
					$data['content']  = '城市区号不能为空';
				} else if ($result_array['error_code'] == '201803') {
					$data['content']  = '查询无结果';
				}
            } else {
                $data['content']  = '服务器无应答，请稍候再试！';
            }
            return $data;
		}
    }
}
?>