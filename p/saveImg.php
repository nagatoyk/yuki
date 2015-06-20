<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
if(isset($_POST['imgOpt'])){
	$url = $_POST['imgOpt']['url'];
	$pid = sprintf('%d', $_GET['pid']);
	$type = pathinfo($url, PATHINFO_EXTENSION);
	if(in_array($type, array('jpg', 'png', 'gif'))){
		$r = array();
		$my_token = $kv->get('my_token');
		$token = $my_token[1687199364];
		$p = $sql->getLine('SELECT `pid` FROM `wb_pic` WHERE `pid`=\''.$pid.'\'');
		if(!isset($p['pid'])){
			$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
			$msg = $c->upload('我刚刚上传了一张照片---'.$pid.'------'.time(), $url);
			if($msg['original_pic']){
				$sql->runSql('INSERT INTO `wb_pic` (`uid`,`url`,`unix`,`pid`,`source`) VALUES (\''.$token['uid'].'\',\''.$msg['original_pic'].'\',UNIX_TIMESTAMP(),\''.$pid.'\',\''.$_POST['imgOpt']['sourec'].'\')');
				$r = $msg;
				$c->delete($msg['id']);
			}else{
				$r = array('error' => 'API没有返回数据');
			}
		}else{
			$r = $p;
		}
		header('Content-Type: application/json;charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		echo isset($_GET['callback']) ? $_GET['callback'].'('.json_encode($r).')' : json_encode($r);
	}
}
