<?php
class baseapi {

    private $token; //令牌
    public $customer_id; //客户ID
    public $postObj; //
    public $rule;
    public $replyobj;
    public $resultStr;
    public $post_data;
    public $fromapi;

    function __construct($token, $customer_id, $fromapi = 'weixin') {
        $this->token = $token;
        $this->customer_id = $customer_id;
        $this->fromapi = $fromapi;
    }
	//解析微信post过来的数据
    public function getPostData() {
        $postObj = simplexml_load_string($GLOBALS["HTTP_RAW_POST_DATA"], 'SimpleXMLElement', LIBXML_NOCDATA);
        $post_data['FromUserName'] = str_inmysql($postObj->FromUserName);
        $post_data['ToUserName'] = str_inmysql($postObj->ToUserName);
        $post_data['FromMsgType'] = str_inmysql($postObj->MsgType);
        $post_data['Keyword'] = trim(str_inmysql($postObj->Content, '1'));
        $post_data['CreateTime'] = str_inmysql($postObj->CreateTime);
        $post_data['Location_X'] = str_inmysql($postObj->Location_X);
        $post_data['Location_Y'] = str_inmysql($postObj->Location_Y);
        $post_data['Label'] = str_inmysql($postObj->Label);
        $post_data['Event'] = str_inmysql($postObj->Event);
        $post_data['EventKey'] = str_inmysql($postObj->EventKey);
        $post_data['PicUrl'] = str_inmysql($postObj->PicUrl);
		$post_data['Recognition']  = str_inmysql($postObj->Recognition);
        if ($post_data['Event'] == 'CLICK') {
            $post_data['Keyword'] = str_inmysql($post_data['EventKey']);
        }
		
		//服务号用户自动上传地理位置的处理
		if (strtolower($postObj->Event) == 'location') {
			$memberLocationModel=new Model_Subtable('member_location');
			$memberLocationRow=$memberLocationModel->where("fromuser='".$postObj->FromUserName."'")->dataRow();
			if($memberLocationRow) $locationArr['info'][id]=$memberLocationRow['id'];
			$locationArr['info'][fromuser]=$postObj->FromUserName;
			$locationArr['info'][latitude]=$postObj->Latitude;
			$locationArr['info'][longitude]=$postObj->Longitude;
			$locationArr['info'][precision]=$postObj->Precision;
			$locationArr['info'][addtime]=$postObj->CreateTime;
			$memberLocationModel->add($locationArr);
		}
		
        return $post_data;
    }
	//回复微信
    public function responseMsg() {
        $this->post_data = $this->getPostData();
		$this->post_data['timesign']=time().rand(1000,9999);
		//语音信息
		if ($this->post_data['FromMsgType'] == 'voice') {
			$this->post_data['Keyword'] = $this->post_data['Recognition'];
		}
		//图片信息
		if ($this->post_data['FromMsgType'] == 'image') {
            $this->post_data['Keyword']='[发送图片]';
        }
		//位置
		if ($this->post_data['FromMsgType'] == 'location') {
            /* $this->post_data['Keyword']='[发送位置]'.$this->post_data['Label'];
			//include "base_info.php";
			//$infoobj = new base_info();
			$result['customer_id']	= $this->customer_id;
			$result['info_type']	= 'location';
			$result['location_x']	= $this->post_data['Location_X'];
			$result['location_y']	= $this->post_data['Location_Y'];
			$result['fromwhere']	= $this->post_data['Label'];
			$result['fromuser']		= $this->post_data['FromUserName'];
			$result['timesign']		= $this->post_data['timesign'];
			//$data= $infoobj->getData($result);
			//return $this->create_xmlcontent($data['msgtype'], $data['content'], $data['title'], $data['description'], $data['picurl'], $data['url'], $data['bodystr']);
			$memberLocationModel=new Model_Subtable('member_location');
			$locationArr['info'][fromuser]=$this->post_data['FromUserName'];
			$locationArr['info'][locate_x]=$this->post_data['Location_X'];
			$locationArr['info'][locate_y]=$this->post_data['Location_Y'];
			$memberLocationModel->add($locationArr); */
			$xmlcontent = " <xml>
			 <ToUserName><![CDATA[" . $this->post_data['FromUserName'] . "]]></ToUserName>
			 <FromUserName><![CDATA[" . $this->post_data['ToUserName'] . "]]></FromUserName>
			 <CreateTime>" . time() . "</CreateTime>
			 <MsgType><![CDATA[text]]></MsgType>
			 <Content><![CDATA[".$this->post_data['Location_X']."|".$this->post_data['Location_Y']."|".$this->post_data['Label']."]]></Content>
			 </xml> ";
        return $xmlcontent;
        }
		//菜单点击
		if($this->post_data['Event'] != '' and $this->post_data['Event']!='CLICK'){
			$this->post_data['Keyword'] = $this->post_data['EventKey'];
		}
		$this->addMessage();
		//关注+取消关注
		if (strtolower($this->post_data['Event']) == 'subscribe' or strtolower($this->post_data['Event']) == 'unsubscribe') {
			/*插入关注记录*/
			$carerecordModel	= new Model_CareRecord();
			$row['is_care']		= strtolower($this->post_data['Event']) == 'subscribe'?'1':'0';
			$row['customer_id']	= $this->customer_id;
			$row['fromuser']	= $this->post_data['FromUserName'];
			$row['create_date']	= date("Y-m-d H:i:s");
			$carerecordModel->insert($row);
			
			/*更新会员表*/
			$memberModel			= new Model_Member();
			$rowMem					= $this->getMemDetail();//获取用户详细信息
			$rowMem['customer_id']	= $this->customer_id;
			$rowMem['fromuser']		= $this->post_data['FromUserName'];
			$rowMem['status']		= strtolower($this->post_data['Event']) == 'subscribe'?'1':'0';
			if($rowMem['status']=='1'){
				$rowMem['subscribe_time']=time();
				$rowMem['create_date']=date("Y-m-d H:i:s");
			}else{
				$rowMem['unsubscribe_time']=time();
			}
			$rowMem['timesign']=$this->post_data['timesign'];
			$memberModel->upsert($rowMem);
			/*关注自动回复*/
			$replyModel  = new Model_AutoReply();
			$replyRow   = $replyModel->findByCustomerId($this->customer_id,'1');
			if(!$replyRow['id'] or $replyRow['state']!='1'){
				return false;
			}else{
				if($replyRow['is_keyword']=='1'){
					$this->post_data['Keyword']=$replyRow['reply_keyword'];
				}else{
					return $this->create_xmlcontent('text', $replyRow['reply_content']);
				}
			}
		}
		$returnxml=$this->getInfo();//file_put_contents('c.txt',$returnxml);
		return $returnxml;
    }
	//获取回复内容
	public function getInfo(){
		$KeywordlistModel=	new Model_KeywordList();
		$keyword=$this->post_data['Keyword'];
		$filter['where']= " customer_id='{$this->customer_id}' and keyword='{$keyword}' ";
		$filter['order']= " rand() ";
		$sql			= $KeywordlistModel->select($filter,'info_id,info_type');
		$keywordinfo	= $KeywordlistModel->fetchRow($sql);
		$base_infoarray = array('text','single','multi','pic','music','video');
		if($keywordinfo['info_type']){
			if(in_array($keywordinfo['info_type'],$base_infoarray)){
				$class='base_info';
			}else{
				$class=$keywordinfo['info_type'].'_info';
			}
			$classFile=$class.'.php';
			
			 if(file_exists($classFile)){
				include "$classFile";
				$infoobj = new $class;
				$result['info_id']		= $keywordinfo['info_id'];
				$result['info_type']	= $keywordinfo['info_type'];
				$result['customer_id']	= $this->customer_id;
				$result['fromuser']		= $this->post_data['FromUserName'];
				$result['timesign']		= $this->post_data['timesign'];
				$data= $infoobj->getData($result);
				$xmlcontent=$this->create_xmlcontent($data['msgtype'], $data['content'], $data['title'], $data['description'], $data['picurl'], $data['url'], $data['bodystr']);
				return $xmlcontent;
			}
		}else{
			include "special_info.php";
			$infoobj = new special_info();
			$result['info_id']		= $keywordinfo['info_id'];
			$result['info_type']	= $keywordinfo['info_type'];
			$result['customer_id']	= $this->customer_id;
			$result['fromuser']		= $this->post_data['FromUserName'];
			$result['timesign']		= $this->post_data['timesign'];
			$result['keyword']		= $this->post_data['Keyword'];
			$data= $infoobj->getData($result);
			
			if($data['msgtype']!=''){
				return $this->create_xmlcontent($data['msgtype'], $data['content'], $data['title'], $data['description'], $data['picurl'], $data['url'], $data['bodystr']);
			}else{
				//无匹配回复
				$replyModel  = new Model_AutoReply();
				$replyRow   = $replyModel->findByCustomerId($this->customer_id,'2');
				if(!$replyRow['id'] or $replyRow['state']!='1'){
					return false;
				}else{
					return $this->create_xmlcontent('text', $replyRow['reply_content']);
				}
			}
		}

	}
    //收到的消息入库
    protected function addMessage() {
		$MessageModel	= new Model_Message();
		$row['customer_id']	= $this->customer_id;
		$row['fromuser']	= $this->post_data['FromUserName'];
		$row['touser']		= $this->post_data['ToUserName'];
		$row['msgtype']		= $this->post_data['FromMsgType'];
		$row['msg_content']	= $this->post_data['Keyword'];
		$row['create_time']	= $this->post_data['CreateTime'];
		$row['create_date']	= date("Y-m-d H:i:s");
        $MessageModel->insert($row);
		if($this->post_data['Event'] != 'subscribe' and $this->post_data['Event'] != 'unsubscribe'){
			/*更新会员表*/
			$memberModel			= new Model_Member();
			$rowMem2				= $this->getMemDetail();//获取用户详细信息
			$rowMem2['customer_id']	= $this->customer_id;
			$rowMem2['fromuser']	= $this->post_data['FromUserName'];
			$rowMem2['timesign']	= $this->post_data['timesign'];
			$memberModel->upsert($rowMem2);
		}
    }
	//组合xml内容 $msgtype(text文字，news图文,music音乐)$content文字消息体 $title图文标题 $description图文简介 $picurl图文封面 $url图文链接 $bodystr 已组合好的格式体，通常用在多图文中
    public function create_xmlcontent($msgtype, $content = '', $title = '', $description = '', $picurl = '', $url = '', $bodystr = '') {
        $createtime = time();
        if ($msgtype == 'text') {
			$content=str_replace("\r","",$content);
            $bodystr = "<Content><![CDATA[$content]]></Content>";
        } else if ($msgtype == 'news') {
			$description=cut_str($description, 120, $start= 0, $code = 'UTF-8');
            if ($bodystr == '') {
                $bodystr = "<ArticleCount>1</ArticleCount>
				 <Articles>
				 <item>
				 <Title><![CDATA[$title]]></Title>
				 <Description><![CDATA[$description]]></Description>
				 <PicUrl><![CDATA[$picurl]]></PicUrl>
				 <Url><![CDATA[$url]]></Url>
				 </item>
				 </Articles>";
            }
        } else if ($msgtype == 'music') {
            $bodystr = "<Music>
			 <Title><![CDATA[$title]]></Title>
			 <Description><![CDATA[$description]]></Description>
			 <MusicUrl><![CDATA[$url]]></MusicUrl>
			 <HQMusicUrl><![CDATA[$url]]></HQMusicUrl>
			 </Music>";
        }
        $xmlcontent = " <xml>
			 <ToUserName><![CDATA[" . $this->post_data['FromUserName'] . "]]></ToUserName>
			 <FromUserName><![CDATA[" . $this->post_data['ToUserName'] . "]]></FromUserName>
			 <CreateTime>" . $createtime . "</CreateTime>
			 <MsgType><![CDATA[" . $msgtype . "]]></MsgType>
			 $bodystr
			 <FuncFlag>0</FuncFlag>
			 </xml> ";
        return $xmlcontent;
    }
    //公众平台校验
    public function valid() {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
			$custModel		= new Model_Customer();
			$data			= array();
			$data['id']		= $this->customer_id;
			$data['is_yz']	= 1;
			$custModel->upsert($data);

            return $echoStr;
        }
    }
	
	//服务号获取用户详细信息
	private function getMemDetail(){
		global $_WGT;
		$memberModel=new Model_Member();
		$rowMem2=array();
		//是否有权限获取信息
		//if(in_array($this->customer_id,$_WGT['Authen_customer_id'])){
			$sql = "select id,nickname from member where customer_id='".$this->customer_id."' and fromuser='".$this->post_data['FromUserName']."'";
			$meminfo = $memberModel->fetchRow($sql);
			if(!$meminfo || $meminfo['nickname']==''){
				$rowMem2 = $this->getMeminfo($this->post_data['FromUserName']);		
			}
		//}
		return $rowMem2;
	}
	
	public function getMeminfo($fromuser){
		$customer_config = new Model_CustomerConfig();
		$sql = "select customer_id,c_value,create_date from customer_config where customer_id='".$this->customer_id."' and c_type='token'";
		$configinfo = $customer_config->fetchRow($sql);	
		//获取access_token
		if($configinfo){
			if(strtotime($configinfo['create_date'])>time()){//token未过期
				$token = $configinfo['c_value'];
			}else{//token已经过期
				$token = $this->gettoken($customer_config);
				if($token){
					$configdata['c_value'] = $token;
					$configdata['create_date'] = date('Y-m-d H:i:s',time()+3600);	
					$customer_config->row_update($configdata,"customer_id='{$configinfo[customer_id]}' and c_type='token'");
				}
			}		
		}else{//没有记录
			$token = $this->gettoken($customer_config);
			if($token){
				$configdata['customer_id'] = $this->customer_id;
				$configdata['c_type'] = "token";
				$configdata['c_value'] = $token;
				$configdata['create_date'] = date('Y-m-d H:i:s',time()+3600);	
				$customer_config->insert($configdata);
			}
		}
		//获取详细信息
		$rowMem2=array();
		if($token){
			$memberjson = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$fromuser."&lang=zh_CN");
			$meminfo = json_decode($memberjson);
			if($meminfo->nickname){
				$rowMem2['nickname'] = $meminfo->nickname;
				$rowMem2['sex'] = $meminfo->sex;
				$rowMem2['language'] = $meminfo->language;
				$rowMem2['city'] = $meminfo->city;
				$rowMem2['province'] = $meminfo->province;
				$rowMem2['country'] = $meminfo->country;
				$rowMem2['headimgurl'] = $meminfo->headimgurl;
				return $rowMem2;
			}
		}
		return $rowMem2;
	}
	//获取access_token
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
}
?>
