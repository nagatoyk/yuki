<?php
session_start();
require './fun.php';
require './saetv2.ex.class.php';
$o=new SaeTOAuthV2($wb_id, $wb_key);
$url=$o->getAuthorizeURL($wb_url);
echo $url;
$c=new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
echo '<br>';
echo dirname(__FILE__);
echo '<pre>';
print_r($_SESSION);