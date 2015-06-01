<?php
session_start();
require './config.php';
require './saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_GET['code'])){
	if(isset($_GET['state'])){
		header('Location:'.urldecode($_GET['state']).'?code='.$_GET['code']);
		exit();
	}else{
		$user = array();
		try {
			$user['token'] = $o->getAccessToken('code', array('code'=>$_GET['code'], 'redirect_uri'=>'https://yuki-yukimax.rhcloud.com/lab/callback.php'));
		} catch (OAuthException $e) {
			header('Location: /index.php');
		}
		if(!$user['token'])exit('error.');
		$c = new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
		$u_msg = $c->show_user_by_id($user['token']['uid']);
		print_r($u_msg);

	}
}else{
	echo 456;
}
