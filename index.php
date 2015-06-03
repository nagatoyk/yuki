<?php
defined('__ROOT__') || define('__ROOT__', dirname(__FILE__));
require 'r/fun.php';
require 'r/saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_GET['login'])){
	$url = $o->getAuthorizeURL($wb_url);
	header('Location: '.$url);
	exit();
}
