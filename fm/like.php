<?php
session_start();
require '../r/fun.php';
require '../lab/moefou.class.php';
$key = '18f95c02504fb5a0fdd83b205e7e1aee05421a58b';
$secret = 'a3af2e9f06faaefb9408897388f0f916';
$callback = 'http://kloli.tk/fm/login.php';
$MoeFM = new MoeFM($key, $secret, $callback);
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
if($_GET['a'] == 'like'){
	$pid = $_POST['pid'];
	$rid = $_POST['rid'];
	$result = $MoeFM->set_fav($_SESSION['moefou']['oauth_token'], $_SESSION['moefou']['oauth_token_secret'], 1, $pid);
	print_r($result);
}
