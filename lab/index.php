<?php
session_start();
header('Content-Type:text/html;charset=utf-8');
require './fun.php';
require './saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
$url = $o->getAuthorizeURL($wb_url, 'code', urlencode($_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.($_SERVER['HTTP_HOST'] == '127.0.0.1'?'127.0.0.1/yuki':$_SERVER['HTTP_HOST']).'/lab/callback.php'));
if(!isset($_SESSION['user']) && !isset($_GET['code'])){
	echo $url;
}elseif(!empty($_GET['code'])){
	$user = array();
	try{
		$user['token'] = $o->getAccessToken('code', array('code'=>$_GET['code'], 'redirect_uri'=>'https://yuki-yukimax.rhcloud.com/lab/callback.php'));
	}catch(OAuthException $e){
		header('Location: index.php');
		exit();
	}
	if(!$user['token'])exit('error.');
	$c = new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
	$u_msg = $c->show_user_by_id($user['token']['uid']);
	$user['id'] = $u_msg['id'];
	$user['name'] = $u_msg['name'];
	$user['pic'] = $u_msg['profile_image_url'];
	$user_ar = $kv->get('user');
	if(!$user_ar[0])
		$user_ar = array();
	if(!in_arr($user['id'], $user_ar)){
		$user_ar[] = array($user['id'], $u_msg['name'], $u_msg['profile_image_url'], time());
		$kv->set('user', $user_ar);
		$_SESSION['user'] = $user;
	}else{
		$_SESSION['user'] = $user;
	}
	header('Location: index.php');
	exit();
}else{
	$user = $kv->get('user');
	$my_token = $kv->get('my_token');
	echo getenv('OPENSHIFT_DATA_DIR');
	echo '<pre>';
	print_r($user);
	print_r($my_token[$user[0][0]]);
	$c = new SaeTClientV2($wb_id, $wb_key, $my_token[$user[0][0]]['access_token']);
	$rate = $c->rate_limit_status();
	print_r($rate);
	echo '</pre>';
	$api = $rate['api_rate_limits'];
	foreach($api as $k => $v){
		echo '<p><b>api</b>:&nbsp;'.$v['api'].'&nbsp;<b>limit</b>:&nbsp;'.$v['limit'].'&nbsp;<b>limit_time_unit</b>:&nbsp;'.$v['limit_time_unit'].'&nbsp;<b>remaining_hits</b>:&nbsp;'.$v['remaining_hits'].'</p>';
	}
}