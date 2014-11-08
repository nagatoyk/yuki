<?php
require 'config.php';
require 'saetv2.ex.class.php';
if(isset($_COOKIE['weibojs_'.WB_AKEY])){
	echo 123;
}else{
	echo 456;
	setcookie('weibojs_'.WB_AKEY, 'access_token%3D2.00yc_LqBUTmzLBd034ebe359FIb5mB%26remind_in%3D157679999%26expires_in%3D157679999%26uid%3D1687199364', time() + 3600 * 24 *365);
}
