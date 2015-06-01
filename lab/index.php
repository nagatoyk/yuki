<?php
session_start();
require './fun.php';
require './saetv2.ex.class.php';
$o=new SaeTOAuthV2($wb_id, $wb_key);
$url=$o->getAuthorizeURL($wb_url, 'code', urlencode('http://'.($_SERVER['HTTP_HOST']=='127.0.0.1'?'127.0.0.1':$_SERVER['HTTP_HOST']).'/yuki/lab/index.php'));
if(!isset($_SESSION['user']) && !isset($_GET['code'])){
	echo $url;
}elseif(!empty($_GET['code'])){
	$user = array();
	try{
		$user['token']=$o->getAccessToken('code', array('code'=>$_GET['code'], 'redirect_uri'=>'https://yuki-yukimax.rhcloud.com/lab/callback.php'));
	}catch(OAuthException $e){
		header('Location: index.php');
		exit();
	}
	if(!$user['token'])exit('error.');
	$c=new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
	$u_msg=$c->show_user_by_id($user['token']['uid']);
	$user['id']=$u_msg['id'];
	$user['name']=$u_msg['name'];
	$user['pic']=$u_msg['profile_image_url'];
	$user_ar=$kv->get('user');
	if(!$user_ar[0])
		$user_ar=array();
	if(!in_arr($user['id'], $user_ar)){
		$user_ar[]=array($user['id'], $u_msg['name'], $u_msg['profile_image_url'], time());
		$kv->set('user', $user_ar);
		$_SESSION['user']=$user;
	}else{
		$_SESSION['user']=$user;
	}
	header('Location: index.php');
	exit();
}else{
	$user_ar=$kv->get('user');
	echo '<pre>';
	print_r($user_ar);
}