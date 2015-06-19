<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
if(isset($_POST['imgOpt'])){
	$url = $_POST['imgOpt']['url'];
	$pid = sprintf('%d', $_GET['pid']);
	$type = pathinfo($url, PATHINFO_EXTENSION);
	if(in_array($type, array('jpg', 'png', 'gif'))){
		$my_token = $kv->get('my_token');
		$token = $my_token[1687199364];
		$info = $sql->getLine('SELECT * FROM `wb_pic` WHERE `pid`=\''.$pid.'\'');
		if(!isset($info['pid'])){
			$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
			$msg = $c->upload('我刚刚上传了一张照片---'.$pid.'------'.time(), $url);
			if(!$msg['original_pic']){
				exec('sleep 3');
				$u = $c->user_timeline_by_id(1687199364, 1, 1);
				if($u['statuses']){
					$info = $sql->getLine('SELECT * FROM `wb_pic` WHERE `url`=\''.$u['statuses'][0]['original_pic'].'\'');
					if(!isset($info['url'])){
						$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`,`source`) VALUES (\'1687199364\',\''.$u['statuses'][0]['original_pic'].'\',UNIX_TIMESTAMP(),\''.$pid.'\',\''.$_POST['imgOpt']['source'].'\')');
						$r = $sql->getLine('SELECT * FROM `wb_pic` WHERE `url`=\''.$u['statuses'][0]['original_pic'].'\'');
						$c->delete($u['statuses'][0]['id']);
					}else{
						$r = $info;
					}
				}
			}else{
				$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`,`source`) VALUES (\'1687199364\',\''.$msg['original_pic'].'\',UNIX_TIMESTAMP(),\''.$pid.'\',\''.$_POST['imgOpt']['source'].'\')');
				$r = $msg;
			}
			$c->delete($msg['id']);
		}else{
			$r = $info;
		}
		header('Content-Type: application/json;charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		echo isset($_GET['callback']) ? $_GET['callback'].'('.json_encode($r).')' : json_encode($r);
	}
}
