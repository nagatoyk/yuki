<?php
if(!isset($_GET['id'])||!preg_match('/^[0-9]{0,10}$/',$_GET['id']))
	err('恶意请求');


$r=$sql->getLine('SELECT * FROM tp_post WHERE pid='.$_GET['id']);

$sql->runSql('UPDATE tp_post SET look=look+1 WHERE pid='.$_GET['id']);

unset($r['modified']);


//$r=array('123132');