<?php
require_once("includes/config.inc.php");
$gets=xml_to_array(send_phone_msg('18789251158','今天5:00开会。请及时到场！'));
var_dump($gets);exit;