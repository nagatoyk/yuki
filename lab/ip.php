<?php
require 'config.php';
require 'saetv2.ex.class.php';
if(isset($_COOKIE['weibojs_'.WB_AKEY])){
	echo 123;
}else{
	echo 456;
}
