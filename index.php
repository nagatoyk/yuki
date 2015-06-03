<?php
defined('__ROOT__') || define('__ROOT__', dirname(__FILE__));
require 'r/fun.php';
require 'r/saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_GET['login'])){
	$url = $o->getAuthorizeURL($wb_url);
	header('Location: '.$url);
	exit();
}
if(isset($_GET['user'])){
	if(preg_match('/^[0-9]{8,12}$/', $_GET['user'])){
		require 'r/Mysql.class.php';
		$user_ar = $sql->getData('SELECT information FROM wb_user WHERE uid=\''.$_GET['user'].'\'');
		$r = $user_ar[0]['information'];
	}else if($_GET['user'] == 'all'){
		require 'r/Mysql.class.php';
		$user_ar = $sql->getData('SELECT information FROM wb_user');
		$r = array();
		foreach($user_ar as $user){
			$r[] = json_decode($user['information']);
		}
		$r = json_encode($r);
	}else{
		session_start();
		$user = $_SESSION['user'];
		if(!$user){
			exit();
		}
		$r = json_encode($user['information']);
	}
	if(isset($_GET['callback'])){
		header('Content-type: text/javascript');
		exit($_GET['callback'].'('.$r.')');
	}else{
		header('Content-type: application/json');
		exit($r);
	}
}