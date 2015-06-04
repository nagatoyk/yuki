<?php
require 'fun.php';
require '../r/Mysql.class.php';
require 'saetv2.ex.class.php';
if(!empty($_POST['imgOpt'])){
	$imgurl = $_POST['imgOpt']['url'];
	$pid = $_POST['imgOpt']['pid'];
	$type = pathinfo($imgurl, PATHINFO_EXTENSION);
	if(in_array($type, array('jpg', 'png', 'gif'))){
		$my_token = $kv->get('my_token');
		$token = $my_token['1687199364'];
		$url = $sql->runSql('SELECT `url` FROM `wb_pic` WHERE `pid`=\''.$pid.'\'');
		if($url !== NULL){
			$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
			$msg = $c->upload('我刚刚上传了一张照片'.time(), $imgurl);
			if(!isset($msg['error_code'])){
				$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`) VALUES (\''.$token['uid'].'\',\''.$msg['original_pic'].'\',\''.time().'\',\''.$pid.'\')');
				$r['imgurl'] = $msg['original_pic'];
				$c->delete($msg['id']);
			}else{
				$r['error'] = $msg;
			}
		}else{
			$r['imgurl'] = $url;
		}
		header('Content-Type: application/json;charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		echo isset($_GET['cb']) ? $_GET['cb'].'('.json_encode($r).')' : json_encode($r);
	}
}
