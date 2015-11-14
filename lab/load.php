<?php
$get = isset($_GET['l']) ? $_GET['l'] : null;
if($get != null){
	$url = 'http://bits.moegirl.org/zh/load.php?'.$;
	$file = file_get_contents($url);
	echo $file;
}
