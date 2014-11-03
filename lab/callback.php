<?php
session_start();
require './config.php';
require './saetv2.ex.class.php';
$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
if(isset($_REQUEST['code'])){
	if(isset($_REQUEST['state'])){
		header('Location:'.urldecode($_REQUEST['state']).'&code='.$_REQUEST['code']);
		exit();
	}
}
