<?php
if(!empty($_POST['imgOpt'])){
	foreach($_POST['imgOpt'] as $k => $v){
		// echo
	}
	$imgurl = $_POST['imgOpt']['url'];
	$r = $_POST['imgOpt'];
	// $finfo = finfo_open(FILEINFO_MIME_TYPE);
	// $type = finfo_file($finfo, $img);
	// finfo_close($finfo);
	$r['type'] = pathinfo($imgurl, PATHINFO_EXTENSION);
	header('Content-Type: application/json;charset=utf-8');
	header('Access-Control-Allow-Origin: *');
	echo isset($_GET['cb']) ? $_GET['cb'].'('.json_encode($r).')' : json_encode($r);
}
