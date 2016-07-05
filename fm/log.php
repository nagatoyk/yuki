<?php
require '../r/fun.php';
require '../x/mysql.class.php';
$pid = $_POST['pid'];
$rid = $_POST['rid'];
if(preg_match('/^[0-9]{5,12}$/', $pid) && preg_match('/^[0-9]{1,3}$/', $rid)){
	$data = $sql->getLine('SELECT * FROM imouto_playcount WHERE pid=\''.$pid.'\' AND rid=\''.$rid.'\'');
	header('Content-type: application/json;charset=utf-8');
	if($data['pcount']){
		// 
		echo json_encode($data);
	}else{
		echo json_encode(array('error'=>'无记录'));
	}
}
