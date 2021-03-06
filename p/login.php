<?php
require '../r/fun.php';
require '../r/saetv2.ex.class.php';
require '../r/Mysql.class.php';
if(isset($_GET['code'])){
	$o = new SaeTOAuthV2($wb_id, $wb_key);
	$user = array();
	try {
		$user['token'] = $o->getAccessToken('code', array('code' => $_GET['code'],'redirect_uri' => $wb_url));
	} catch (OAuthException $e) {
		header('Location: /p/');
	}
	if(!$user['token'])exit('error.');
	$c = new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
	$u_msg = $c->show_user_by_id($user['token']['uid']);
	$user['id'] = $u_msg['id'];
	$user['information'] = array(
		$u_msg['id'],//'id'=>
		$u_msg['name'],//'name'=>
		$u_msg['description'],//'des'=>
		$u_msg['profile_image_url'],//'avatar'=>
		$u_msg['domain'],//'weibo'=>
		$u_msg['url'],//'website'=>
		$user['token']['access_token']//'access_token'
	);
	$user_ar = $sql->getData('SELECT `uid`,`information` FROM `wb_user` WHERE `uid`=\''.$user['id'].'\'');
	if(!$user_ar[0]){
		$sql->runSql('INSERT INTO wb_user (`unix`,`uid`,`information`) VALUES (\''.time().'\',\''.$user['id'].'\',\''.addslashes(json_encode($user['information'])).'\')');
	}
	session_start();
	$_SESSION['user'] = $user;
	$o_url = $_COOKIE['sty_url'];
	if($o_url){
		unset($_COOKIE['sty_url']);
		header('Location: '.$o_url);
	}else{
		header('Location: /p/');
	}
}
