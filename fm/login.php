<?php
session_start();
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
	setcookie('moufou', json_encode($_SESSION['moefou']));
	header('Location:/fm/login.php');
	exit();
}else{
	$info = $MoeFM->get_user_info($_SESSION['moefou']['oauth_token'], $_SESSION['moefou']['oauth_token_secret']);
	print_r($info);
}