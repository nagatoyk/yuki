<?php
if(!empty($_POST['imgOpt'])){
	foreach($_POST['imgOpt'] as $k => $v){
		// echo
	}
	echo isset($_GET['cb']) ? $_GET['cb'].'('.json_encode($_POST['imgOpt']).')' : json_encode($_POST['imgOpt']);
}
