<?php
require 'fun.php';
require 'saetv2.ex.class.php';
if(!empty($_POST['imgOpt'])){
	foreach($_POST['imgOpt'] as $k => $v){
		// echo
	}
	$imgurl = $_POST['imgOpt']['url'];
	$r = $_POST['imgOpt'];
	$r['name'] = pathinfo($imgurl, PATHINFO_BASENAME);
	$r['type'] = pathinfo($imgurl, PATHINFO_EXTENSION);
	$my_token = $kv->get('my_token');
	$token = $my_token['1687199364'];
	$c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
	$msg = $c->upload_url_text('我刚刚上传了一张照片'.time(), $imgurl);
	if($msg['original_pic']){
		$sql->runSql('INSERT INTO wb_pic (`uid`,`url`,`unix`) VALUES (\''.$token['uid'].'\',\''.$msg['original_pic'].'\',\''.time().'\')');
		$r['original'] = $msg['original_pic'];
		$c->delete($msg['id']);
	}else{
		print_r($msg);
	}
	header('Content-Type: application/json;charset=utf-8');
	header('Access-Control-Allow-Origin: *');
	echo isset($_GET['cb']) ? $_GET['cb'].'('.json_encode($r).')' : json_encode($r);
}
