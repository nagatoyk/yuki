<?php
if(isset($_GET)){
	if(isset($_GET['only'])) require false;
	switch($_GET['only']){
		case 'styles':
			header('Content-Type:text/css;charset=utf-8');
			break;
		case 'scripts':
			header('Content-Type:application/javascript;charset=utf-8');
			break;
		default:
			# code...
			break;
	}
	$url = 'http://bits.moegirl.org/zh/load.php?'.$_SERVER['QUERY_STRING'];
	$file = file_get_contents($url);
	echo $file;
}
