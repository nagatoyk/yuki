<?php
require '../lab/moefou.class.php';
$key = '18f95c02504fb5a0fdd83b205e7e1aee05421a58b';
$secret = 'a3af2e9f06faaefb9408897388f0f916';
$callback = 'http://kloli.tk/fm/login.php';
$MoeFM = new MoeFM($key, $secret, $callback);
if(isset($_GET['redirect'])){
	$MoeFM->redirect_to_login();
}