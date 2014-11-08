<?php
require 'config.php';
require 'saetv2.ex.class.php';
if(isset($_COOKIE['weibojs_'.WB_AKEY])){
	echo $_COOKIE['weibojs_'.WB_AKEY];
	// $c = SaeTClient();
}else{
}
