<?php
require 'fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
if(!empty($_POST['imgOpt'])){
	$url = urldecode($_POST['imgOpt']['url']);
	$host = parse_url($url);
	$dir = pathinfo($url, PATHINFO_DIRNAME);
	$filename = pathinfo($url, PATHINFO_BASENAME);
	$imgurl = $host['scheme'].'://'.$host['host'].'/'.$dir.'/'.$filename;
	$pid = $_GET['pid'];
	$type = pathinfo($imgurl, PATHINFO_EXTENSION);
	if(in_array($type, array('jpg', 'png', 'gif'))){
		$my_token = $kv->get('my_token');
		$token = $my_token['1687199364'];
		$info = $sql->getLine('SELECT * FROM `wb_pic` WHERE `pid`=\''.$pid.'\'');
		if(!isset($info['pid'])){
			$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
			$msg = $c->upload('我刚刚上传了一张照片'.time(), $imgurl);
			if($msg['original_pic']){
				$img = $msg['original_pic'];
				$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`) VALUES (\''.$token['uid'].'\',\''.$img.'\',\''.time().'\',\''.$pid.'\')');
				$c->delete($msg['id']);
				$r = $msg;
			}else{
				$r = array('url' => $imgurl);
			}
		}else{
			$r = $info;
		}
		header('Content-Type: application/json;charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		echo isset($_GET['cb']) ? $_GET['cb'].'('.json_encode($r).')' : json_encode($r);
	}
}
