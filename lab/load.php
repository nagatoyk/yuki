<?php
if(isset($_GET)){
	if(isset($_GET['only']) && $_GET['only'] == 'styles'){
		header('Content-Type:text/css;charset=utf-8');
	}
	$url = 'http://bits.moegirl.org/zh/load.php?'.$_SERVER['QUERY_STRING'];
	$file = file_get_contents($url);
	echo $file;
}
