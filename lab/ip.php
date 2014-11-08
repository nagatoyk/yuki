<?php
require 'config.php';
require 'saetv2.ex.class.php';
if(isset($_COOKIE['weibojs_'.WB_AKEY])){
	$cookie = urldecode($_COOKIE['weibojs_'.WB_AKEY]);
	echo $cookie;
	// $c = SaeTClient();
}else{
}
