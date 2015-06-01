<?php
session_start();
require './fun.php';
require './saetv2.ex.class.php';
$o=new SaeTOAuthV2($wb_id, $wb_key);
$url=$o->getAuthorizeURL($wb_url);
echo $url;
$c=new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
$user_ar=$kv->get('user');
if(is_null($user_ar)){
	$user_ar=array();
}
print_r($user_ar);
