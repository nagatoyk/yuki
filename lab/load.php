<?php
if(isset($_GET)){
	$url = 'http://bits.moegirl.org/zh/load.php?'.$_SERVER['QUERY_STRING'];
	$file = file_get_contents($url);
	echo $file;
}
