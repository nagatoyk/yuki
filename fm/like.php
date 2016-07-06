<?php
session_start();
require '../r/fun.php';
require '../lab/moefou.class.php';
$key = '18f95c02504fb5a0fdd83b205e7e1aee05421a58b';
$secret = 'a3af2e9f06faaefb9408897388f0f916';
$callback = 'http://kloli.tk/fm/login.php';
$MoeFM = new MoeFM($key, $secret, $callback);
if($_GET['a'] == 'like'){
	$pid = $_POST['pid'];
	$rid = $_POST['rid'];
	$result = $MoeFM->add_like_fav($_SESSION['moefou']['oauth_token'], $_SESSION['moefou']['oauth_token_secret'], $pid);
	echo $result;
	if($result['respone']['fav']['fav_obj_id'] == $pid){
		$r = array(
			'msg'=>'加入收藏成功'
		);
	}
	header('Content-type: application/json;charset=utf-8');
	echo json_encode($r);
}
