<?php
if(isset($_GET)){
	$url = 'http://zh.moegirl.org/index.php?'.$_SERVER['QUERY_STRING'];
	$file = file_get_contents($url);
	echo $file;
}

