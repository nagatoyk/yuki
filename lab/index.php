<?php
session_start();
require 'saetv2.ex.class.php';
$o = new SaeTOAuthV2('1093396876', 'd126f1302a7f1b7e36536f4ad84622a0');
$url = $o->getAuthorizeURL('https://yuki-yukimax.rhcloud.com/lab/callback.php');
echo $url;
