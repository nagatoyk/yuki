<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
if(isset($_POST['imgOpt'])){
	$url = $_POST['imgOpt']['url'];
	$type = pathinfo($url, PATHINFO_EXTENSION);
	if(in_array($type, array('jpg', 'png', 'gif'))){
		$my_token = $kv->get('my_token');
		$token = $my_token[1687199364];
		$pid = $sql->getLine('SELECT `pid` FROM `wb_pic` WHERE `pid`=\''.(int)$_GET['pid'].'\'');
		if(!isset($pid['pid'])){
			$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
			$msg = $c->upload('我刚刚上传了一张照片---'.(int)$_GET['pid'].'------'.time(), $imgurl);
			if($msg['original_pic']){
				$img = $msg['original_pic'];
				$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`,`source`) VALUES (\''.$my_token['uid'].'\',\''.$img.'\',\''.time().'\',\''.(int)$_GET['pid'].'\',\''.$_POST['imgOpt']['source'].'\')');
				$c->delete($msg['id']);
				$r = $msg;
			}else{
				$u = $c->user_timeline_by_id($my_token['uid'], 1, 1);
				if($u['statuses']){
					$info = $sql->getLine('SELECT * FROM `wb_pic` WHERE `url`=\''.$u['statuses'][0]['original_pic'].'\'');
					if(!isset($info['url'])){
						$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`,`pid`,`source`) VALUES (\''.$my_token['uid'].'\',\''.$u['statuses'][0]['original_pic'].'\',\''.time().'\',\''.(int)$_GET['pid'].'\',\''.$_POST['imgOpt']['source'].'\')');
						$r = $sql->getLine('SELECT * FROM `wb_pic` WHERE `url`=\''.$u['statuses'][0]['original_pic'].'\'');
						$c->delete($u['statuses'][0]['id']);
					}else{
						$r = $info;
					}
				}
			}
		}else{
			$r = $info;
		}
		header('Content-Type: application/json;charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		echo isset($_GET['callback']) ? $_GET['callback'].'('.json_encode($r).')' : json_encode($r);
	}
}
