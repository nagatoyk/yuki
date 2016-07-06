<?php
// require '../r/fun.php';
require '../x/mysql.class.php';
$pid = $_POST['pid'];
$rid = $_POST['rid'];
if(preg_match('/^[0-9]{5,12}$/', $pid) && preg_match('/^[0-9]{1,3}$/', $rid)){
	$data = $sql->getLine('SELECT * FROM imouto_playcount WHERE pid=\''.$pid.'\' AND rid=\''.$rid.'\'');
	header('Content-type: application/json;charset=utf-8');
	if($data){
		$sql->runSql('UPDATE imouto_playcount SET pcount=pcount+1 WHERE pid=\''.$pid.'\' AND rid=\''.$rid.'\'');
		echo json_encode(array(
			'id'=>$pid,
			'msg'=>'更新记录成功!',
			'count'=>($data['pcount'] + 1)
		));
	}else{
		$sql->runSql('INSERT INTO imouto_playcount (`pid`,`rid`,`pcount`) VALUES ('.$pid.','.$rid.',1)');
		echo json_encode(array(
			'id'=>$sql->lastId(),
			'msg'=>'添加记录成功!',
			'count'=>1
		));
	}
}
