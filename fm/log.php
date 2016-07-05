<?php
require '../r/fun.php';
require '../x/mysql.class.php';
$pid = $_POST['pid'];
if(preg_match('/^[0-9]{5,12}$/', $pid)){
	$data = $sql->getLine('SELECT * FROM imouto_playcount WHERE pid=\''.$pid.'\'');
	header('Content-type: application/json;charset=utf-8');
	echo json_encode($data);
}
