<?php

session_start();
require 'r/fun.php';
require 'r/saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_REQUEST['code'])){
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = $wb_url;
	try{
		$token = $o->getAccessToken('code', $keys);
	}catch(OAuthException $e){
	}
}
if($token){
	$_SESSION['token'] = $token;
	setcookie('weibojs_'.$o->client_id, http_build_query($token));
	echo '授权完成,<a href="/">进入你的微博列表页面</a>';
}else{
	echo '授权失败。';
}