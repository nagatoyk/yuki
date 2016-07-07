<?php
session_start();
date_default_timezone_set('PRC');
require 'r/fun.php';
require 'r/saetv2.ex.class.php';


if(isset($_POST['image'])&&!empty($_POST['image'])){
	header('Content-type: application/json;charset=utf8');
	$dirname = 'files/images/'.$_POST['from'].'/';
	file_exists($dirname) || mkdir($dirname);
	$data = file_get_contents($_POST['image']);

	$filename = /*$_SERVER['OPENSHIFT_DATA_DIR'].*/'files/images/'.$_POST['from'].'/'.date('YmdHis').'.'.$_POST['format'];

	file_put_contents($filename, $data);
	$c = new SaeTClientV2($wb_id, $wb_key, $_POST['token']);

	$res = $c->upload('#艦隊これくしょん# 玩家截图 发布于 '.date('Y-m-d H:i:s').' 经由<KC3改>插件截图发布,插件作者: @凉宫长门', 'https://yuki-yukimax.rhcloud.com/'.$filename);

	if($res['original_pic'] && $_POST['from'] == 'Kancolle'){
		$res['url'] = $res['original_pic'];
		echo json_encode($res);
	}
}


