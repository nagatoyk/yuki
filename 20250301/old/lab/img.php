<?php
if(isset($_GET['url'])){
	$url = $_GET['url'];
	$pics = file($url);
	for($i = 0; $i < count($pics); $i++){
		echo $pics[$i];
	}
}

