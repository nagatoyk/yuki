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
		$info = $sql->getLine("SELECT * FROM `wb_pic` WHERE `pid`='{$pid}'");
		$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
		if(!isset($info['pid'])){
			$c->set_debug(true);
			$c->upload('我刚刚上传了一张照片---'.$pid.'------'.time(), $url);
			$u = $c->user_timeline_by_id($token['uid'], 1, 1);
			if($u['statuses']){
				if(strpos($u['statuses'][0]['text'], $pid) !== false){
					$sql->runSql("INSERT INTO `wb_pic` (`uid`,`url`,`unix`,`pid`,`source`) VALUES ('{$token['uid']}','{$u['statuses'][0]['original_pic']}',UNIX_TIMESTAMP(),'{$pid}','{$_POST['imgOpt']['source']}')");
					$m = $sql->getLine("SELECT * FROM `wb_pic` WHERE `url`='{$u['statuses'][0]['original_pic']}'");
					if(isset($m['pid'])){
						$r = $m;
					}else{
						$r = array('error' => '插入失败');
					}
					// $c->delete($u['statuses'][0]['id']);
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
