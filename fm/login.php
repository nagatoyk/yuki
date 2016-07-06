<?php
session_start();
require '../r/fun.php';
require '../lab/moefou.class.php';
$key = '18f95c02504fb5a0fdd83b205e7e1aee05421a58b';
$secret = 'a3af2e9f06faaefb9408897388f0f916';
$callback = 'http://kloli.tk/fm/login.php';
$MoeFM = new MoeFM($key, $secret, $callback);
if(isset($_GET['redirect'])){
	$MoeFM->redirect_to_login();
}elseif(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
	$oauth_token = $_GET['oauth_token'];
	$oauth_verifier = $_GET['oauth_verifier'];
	$data = $MoeFM->get_access_token($oauth_token, $_SESSION['moefou']['token_secret'], $oauth_verifier);
	parse_str($data);
	$_SESSION['moefou']['oauth_token'] = $oauth_token;
	$_SESSION['moefou']['oauth_token_secret'] = $oauth_token_secret;
	// setcookie('moufou', json_encode($_SESSION['moefou']));
	header('Location:/fm/login.php?a=in');
	exit();
}elseif($_GET['a'] == 'in'){
	$info = $MoeFM->get_user_info($_SESSION['moefou']['oauth_token'], $_SESSION['moefou']['oauth_token_secret']);
	$user = $kv->get('moefou');
	if(!$user['user'] || !$user['moefou']){
		$user['user'] = $info['response']['user'];
		$user['moefou'] = array(
			'oauth_token'=>$_SESSION['moefou']['oauth_token'],
			'oauth_token_secret'=>$_SESSION['moefou']['oauth_token_secret']
		);
		$kv->set('moefou', $user);
	}
	echo '<pre>';
	print_r($user);
}elseif($_GET['a'] == 'sss' && !empty($_GET['cb'])){
	if(isset($_SESSION['moefou']['oauth_token'])){
		$user = $kv->get('moefou');
		$arr = array(
			'sss'=>json_encode(array(
				't'=>passport_encrypt($user['moefou']['oauth_token'], $key),
				's'=>passport_encrypt($user['moefou']['oauth_token_secret'], $key)
			))
		);
	}else{
		$arr = array(
			'error'=>'未登录'
		);
	}
	header('Content-type: application/json;charset=utf-8');
	echo $_GET['cb'].'('.json_encode($arr).')';
}elseif(!empty($_POST['sss'])){
	$sss = json_decode($_POST['sss'], true);
	print_r($sss);
	$oauth_token = passport_decrypt($sss['t'], $key);
	$oauth_token_secret = passport_decrypt($sss['s'], $key);
	$info = $MoeFM->get_user_info($oauth_token, $oauth_token_secret);
	$user = $info['response']['user'];
	header('Content-type: application/json;charset=utf-8');
	$arr = array(
		'name'=>$user['user_nickname'],
		'avatar'=>$user['user_avatar'],
		'home'=>$user['user_fm_url']
	);
	echo json_encode($arr);
}