<?php
include_once '../includes/config.inc.php';
class base_info{
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
		$this->web_domain=$_WGT['WEB_DOMAIN'];
		$this->timesign=$result['timesign'];
		if($this->info_type=='text'){
			return $this->getText();
		}elseif($this->info_type=='single'){
			return $this->getSingle();
		}elseif($this->info_type=='multi'){
			return $this->getMulti();
		}elseif($this->info_type=='pic'){
			return $this->getPic();
		}elseif($this->info_type=='music'){
			return $this->getMusic();
		}elseif($this->info_type=='video'){
			return $this->getVideo();
		}elseif($this->info_type=='location'){
			return $this->getLocation();
		}
	}
	//文字回复
	public function getText(){
		$infocommonModel = new Model_InfoCommon();
		$filter['where'] = " id='{$this->info_id}' and customer_id='{$this->customer_id}' and state='1' ";
		$sql=$infocommonModel->select($filter,'info_intro');
		$inforesult = $infocommonModel->fetchRow($sql);
		$sql="update info_common set push_num=push_num+1 where id='{$this->info_id}' and customer_id='{$this->customer_id}' limit 1";
		$infocommonModel->query($sql);
		$data['msgtype']='text';
		if($this->info_id==183){//记得打开公众号接受消息
			$userModel=new Model_Subtable('sub_user');
			$scoreModel=new Model_SubScore('sub_score');
			$qdScore=$scoreModel->qd();
			$uRow=$userModel->where("fromuser='".$this->fromuser."'")->dataRow();
			$time=time();
			$startTime=strtotime(date('Y-m-d',$time).' 00:00:00');//今天0点的时间戳
			if($uRow['qd_time']>$startTime){
				$data['content']='已签到';
			}else if($uRow){
				//签到获取积分
				$data1['info'][id]=$uRow['id'];
				$data1['info'][score]=$qdScore+$uRow['score'];
				$data1['info'][score_all]=$qdScore+$uRow['score_all'];
				$data1['info'][qd_time]=$time;
				$userModel->add($data1);
				$data['content']='签到成功';
			}else{
				$data['content']='您还未注册';
			}
		}else{
			$data['content']=$inforesult['info_intro'];
		}//file_put_contents('a.txt',$data['content']);
		return $data;
	}
	//单图文回复
	public function getSingle(){
		$infocommonModel = new Model_InfoCommon();
		$filter['where'] = " id='{$this->info_id}' and customer_id='{$this->customer_id}' and state='1' ";
		$sql=$infocommonModel->select($filter);
		$inforesult = $infocommonModel->fetchRow($sql);
		$sql="update info_common set push_num=push_num+1 where id='{$this->info_id}' and customer_id='{$this->customer_id}' limit 1";
		$infocommonModel->query($sql);
		$data['msgtype']	= 'news';
		$data['title']		= $inforesult['info_title'];
		$data['info_intro']	= $inforesult['info_intro'];
		$data['description']= $inforesult['info_desc']!=''?$inforesult['info_desc']:cut_str(deletehtml($data['info_intro']), 80, $start = 0, $code = 'UTF-8');
		$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST']. "/data/image_c/" .$inforesult['info_pic'];
		$data['url']		= $inforesult['info_url'];
		if(!$data['url']){
			$data['url'] = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/info.php?t=single&id=" . $inforesult['id'];
		}else{
			//关键词有外链时
			if(strstr($data['url'],"?")){
				$data['url']=$data['url']."&fromuser=".$this->fromuser."&sign=".$this->timesign;
			}else{
				$data['url']=$data['url']."?fromuser=".$this->fromuser."&sign=".$this->timesign;
			}
			return $data;
		}
	}
	//音乐
	public function getMusic(){
		$infomusicModel	= new Model_InfoMusic();
		$filter['where'] = " id='{$this->info_id}' and customer_id='{$this->customer_id}' and state='1' ";
		$sql=$infomusicModel->select($filter);
		$inforesult = $infomusicModel->fetchRow($sql);
		$sql="update info_music set push_num=push_num+1 where id='{$this->info_id}' and customer_id='{$this->customer_id}' limit 1";
		$infomusicModel->query($sql);
		$data['msgtype']	= 'music';
		$data['title']		= $inforesult['music_name'];
		$data['description']= $inforesult['music_desc'];
		$data['url']		= $inforesult['url'];
		return $data;
	}
	//视频
    public function getVideo() {
        $infovideoModel = new Model_InfoVideo();
		$filter['where'] = " id='{$this->info_id}' and customer_id='{$this->customer_id}' and state='1' ";
		$sql=$infovideoModel->select($filter);
		$inforesult = $infovideoModel->fetchRow($sql);
		$sql="update info_video set push_num=push_num+1 where id='{$this->info_id}' and customer_id='{$this->customer_id}' limit 1";
		$infovideoModel->query($sql);
		$data['msgtype']	= 'news';
		$data['title']		= $inforesult['video_name'];
		$data['description']= $inforesult['video_desc'];
		$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST'] . "/data/image_c/" .$inforesult['video_pic'];
		$data['url']		= $inforesult['video_url'];
		$movie_play_url = null;
		$preg_content = "|v\.youku\.com/v_show/id_(.*?)\.html|s";
		preg_match($preg_content, $data['url'], $match);
		if (!empty($match)) {
			$movie_play_url = 'http://player.youku.com/embed/' . $match['1'];
		}

		$preg_content = "|http://v.qq.com/(.*)|s";
		preg_match($preg_content, $data['url'], $match);
		if (!empty($match)) {
			$movie_play_url = $data['url'];
		}

		$data['url']		= $movie_play_url;
		return $data;
		
    }
	//地理坐标回复
    public function getLocation() {
        $i = 0;
        $push_num = 1;
		$infolbsModel	= new Model_InfoLbs();
		$filter['where'] = " customer_id='{$this->customer_id}' and state='1' and x_dian!='' ";
		$sql=$infolbsModel->select($filter);
		$inforesult = $infolbsModel->fetchAll($sql);
		$infocount=count($inforesult);
		for($i=0;$i<$infocount;$i++){
            $inforesult[$i]['location_desc'] = strip_tags($inforesult[$i]['location_desc']);
            $short_intro = cut_str(strip_tags($inforesult[$i]['location_intro']), 120, $start = 0, $code = 'UTF-8');
            $inforesult[$i]['location_desc'] = $inforesult[$i]['location_desc'] != '' ? $inforesult[$i]['location_desc'] : $short_intro;
            $inforesult[$i]['faraway'] = $this->get_distance($this->location_x, $this->location_y, $inforesult[$i]['x_dian'], $inforesult[$i]['y_dian']);
		}
        $newlocallist = multi_array_sort($inforesult, 'faraway', $sort = SORT_ASC);
		$customerconfigModel	= new Model_CustomerConfig();
		$filter['where'] = " customer_id='{$this->customer_id}' and c_type='lbs_push' ";
		$sql=$customerconfigModel->select($filter);
		$inforesult = $customerconfigModel->fetchRow($sql);
		if($inforesult['c_value']){
            $push_num = $inforesult['c_value'];
        } else {
            if ($i >= 2) {
                $push_num = 2;
            }
        }
        $push_num = $push_num <= $i ? $push_num : $i;
        for ($j = 0; $j < $push_num; $j++) {
            if ($j >= 10) {
                $push_num = 10;
                break;
            }
            $sql = "update info_lbs set push_num=push_num+1 where id='" . $newlocallist[$j]['id'] . "' limit 1";
            $customerconfigModel->query($sql);
			$infolbsrecordModel	= new Model_InfoLbsRecord();
			$row['lbs_id']		= $newlocallist[$j]['id'];
			$row['customer_id']	= $this->customer_id;
			$row['fromuser']	= $this->fromuser;
			$row['fromwhere']	= $this->fromwhere;
			$row['ip']			= $_WGT['IP'];
			$row['create_date']	= date("Y-m-d H:i:s");
            $infolbsrecordModel->insert($row);
			$url = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/info.php?t=location&id=" . $newlocallist[$j]['id'];


            $itemlist.= "<item>
		 <Title><![CDATA[[".$newlocallist[$j]['faraway']."公里]". $newlocallist[$j]['location_name']."]]></Title>
		 <Description><![CDATA[". $newlocallist[$j]['location_desc'] . "]]></Description>
		 <PicUrl><![CDATA[http://".$_SERVER['HTTP_HOST'] . "/data/image_c/" . $newlocallist[$j]['location_pic'] . "]]></PicUrl>
		 <Url><![CDATA[$url]]></Url>
		 </item>";
        }
        if ($i > 0) {
            $Bodystr = "
			 <ArticleCount>$push_num</ArticleCount>
			 <Articles>
			 $itemlist
			 </Articles>";
			$data['msgtype']	= 'news';
			$data['bodystr']	= $Bodystr;
			return $data;
        }
		
    }
	//根据经纬度计算距离，单位公里
    public function get_distance($lat1, $lng1, $lat2, $lng2) {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2; //两纬度之差,纬度<90
        $b = $radLng1 - $radLng2; //两经度之差纬度<180
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return round($s, 2);
    }
	//多图文
    public function getMulti() {
        $i = 0;
		$infocommonModel = new Model_InfoCommon();
		$filter['where'] = " id='{$this->info_id}' and customer_id='{$this->customer_id}' and state='1' ";
		$sql=$infocommonModel->select($filter);
		$inforesult = $infocommonModel->fetchRow($sql);
        if($inforesult['id']){
			$sql = "update info_common set push_num=push_num+1 where id='" . $inforesult['id'] . "' limit 1";
            $infocommonModel->query($sql);
			$infocommondetailModel= new Model_InfoCommonDetail();
			$filter['where'] = " info_common_id='".$inforesult['id']."'";
			$filter['order'] = " order_num asc ";
			$sql=$infocommondetailModel->select($filter);
			$inforesult2 = $infocommondetailModel->fetchAll($sql);
			$infocount=count($inforesult2);
			for($i=0;$i<$infocount;$i++){
				if(!$inforesult2[$i]['url']){
					$url = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/info.php?t=multi&id=" . $inforesult2[$i]['id'];
				}else{
					$url = $inforesult2[$i]['url'];
				}
				$itemlist.= "<item>
			 <Title><![CDATA[".$inforesult2[$i]['title']."]]></Title>
			 <Description><![CDATA[". $inforesult2[$i]['location_desc'] . "]]></Description>
			 <PicUrl><![CDATA[http://".$_SERVER['HTTP_HOST'] . "/data/image_c/" . $inforesult2[$i]['pic'] . "]]></PicUrl>
			 <Url><![CDATA[$url]]></Url>
			 </item>";
			}
		}
        if ($i > 0) {
            $Bodystr = "
			 <ArticleCount>$infocount</ArticleCount>
			 <Articles>
			 $itemlist
			 </Articles>";
			$data['msgtype']	= 'news';
			$data['bodystr']	= $Bodystr;
			return $data;
        }
		
    }

}
?>