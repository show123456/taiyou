 <?php
	//25、快递查询
    public function get_express($express_keyword) {
        $now = date("Y-m-d H:i:s");
        $sql = "select * from wx_app_record where app_id=25 and user_id='$this->customer_id' and expired_date>='$now' limit 1";
        $this->db_obj->query($sql);
        $this->db_obj->nextRecord();
        $express_data = $this->db_obj->getRecord();
        if (($this->fromapi == 'weixin' && $express_data['state_weixin'] == '1') || ($this->fromapi == 'yixin' && $express_data['state_yixin'] == '1')) {//如果客户已开通此应用
            $express_contact = explode("+", $express_keyword);
            $com = $express_contact[0];
            $no = $express_contact[1];
            $express_url = "http://v.juhe.cn/exp/index?key=90bd534c3f38bf59cc2d9f98b55b3941&com=" . "$com" . "&no=" . "$no";
            $get_result = $this->post($express_url, $postType = 'GET', '');
            if (strstr($get_result, 'resultcode')) {
                $result_array = json_decode($get_result, true);
                if ($result_array['resultcode'] == '200') {
                    if (empty($result_array['result']['list'])) {
                        $data = '未查找到该单号的快递信息,请确认输入的快递单号正确';
                    } else {
                        foreach ($result_array['result']['list'] as $key => $value) {
                            $line = $key != '0' ? "--------------\n" : '';
                            $data .= $line . $value['datetime'] . "：" . $value['remark'];
                            if (!empty($value['zone'])) {
                                $data .= "【$value[zone] 】\n";
                            } else {
                                $data .= "\n";
                            }
                        }
                    }
                } else if ($result_array['resultcode'] == '101') {
                    $data = 'Key值错误';
                } else if ($result_array['resultcode'] == '201') {
                    $data = '请填写正确的快递公司';
                } else if ($result_array['resultcode'] == '202') {
                    $data = '快递单号错误,请查证';
                } else if ($result_array['resultcode'] == '204') {
                    $data = '网络错误,请重试';
                } else if ($result_array['resultcode'] == '205') {
                    $data = '未查找到该单号的快递信息,请确认输入的快递单号正确';
                }
            } else {
                $data = '快递无应答，请稍候再试！';
            }
            return $this->create_xmlcontent('text', $data);
        }
        return false;
    }

    //26、天气查询
    public function get_weather($weather_keyword) {
        $now = date("Y-m-d H:i:s");
        $sql = "select * from wx_app_record where app_id=26 and user_id='$this->customer_id' and expired_date>='$now' limit 1";
        $this->db_obj->query($sql);
        $this->db_obj->nextRecord();
        $weather_data = $this->db_obj->getRecord();
        if (($this->fromapi == 'weixin' && $weather_data['state_weixin'] == '1') || ($this->fromapi == 'yixin' && $weather_data['state_yixin'] == '1')) {//如果客户已开通此应用
            $weather_url = "http://v.juhe.cn/weather/index?cityname=$weather_keyword&key=7ac31fb63057d3aa88fb28b0d62d3cf9";
            $get_result = $this->post($weather_url, $postType = 'GET', '');
            if (strstr($get_result, 'resultcode')) {
                $result_array = json_decode($get_result, true);
                if ($result_array['resultcode'] == '200') {
                    $data = $result_array['result']['today']['city'] . "天气\n" . $result_array['result']['today']['date_y'] . " " . $result_array['result']['today']['week'] . " " . $result_array['result']['sk']['time'] . "发布";
                    foreach ($result_array['result']['future'] as $key => $value) {
                        $data .= "\n--------------\n" . $value['week'] . "：" . $value['weather'] . "，" . $value['wind'] . "，" . $value['temperature'];
                    }
                } else if ($result_array['resultcode'] == '101') {
                    $data = 'Key值错误';
                } else if ($result_array['resultcode'] == '201') {
                    $data = '请输入正确的城市名';
                } else if ($result_array['resultcode'] == '202') {
                    $data = '未查找到该城市天气信息,请确认输入的城市名正确';
                } else if ($result_array['resultcode'] == '203') {
                    $data = '查询失败';
                }
            } else {
                $data = '查询失败，请稍候再试！';
            }
            return $this->create_xmlcontent('text', $data);
        }
        return false;
    }
?>