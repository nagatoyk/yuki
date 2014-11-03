<?php
session_start();
require './config.php';
require './saetv2.ex.class.php';
$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
if(isset($_REQUEST['code'])){
	switch($_REQUEST['state']){
		case 'saeyuki':
			header('Location:http://yukimax.sinaapp.com/site/callback?code='.$_REQUEST['code']);
			exit();
			break;

		default:
			header('Location:'.urldecode($_REQUEST['state']).'?code='.$_REQUEST['code']);
			exit();
			break;
	}
}
