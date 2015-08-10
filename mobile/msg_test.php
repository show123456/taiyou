<?php
include_once("../includes/config.inc.php");
//send_phone_msg('18789251158','您的验证码是：568972');

$configModel=new Model_CustomerConfig();
$ss=$configModel->sendCustomerMsg("【云客生活部落】：【★】天气预报：【今天】8月1日，多云，27°到36°，南风3-4级转西南风3-4级。

【★】【心灵鸡汤】：一齐走的兄弟姐妹是一种安慰，一齐走的兄弟姐妹忠告是一种激励，一齐走的兄弟姐妹鼓励是一种力量，一齐走的兄弟姐妹想念是一种愉悦。祝福你兄弟姐妹！
<a href='http://mp.weixin.qq.com/s?__biz=MzA5ODQwMzI4Ng==&amp;mid=208574136&amp;idx=2&amp;sn=f978823cb2c33499635362154118e724#rd'>【★】【★】【★】“值得学习的思维模式”（←点击我哦^_^）</a>",array('ow6AGuAOUkUcUrjCyT2isDn9rRJc'));
var_dump($ss);