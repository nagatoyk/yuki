<?php
if(!isset($_GET['a']) && !preg_match('/^gm$/', $_GET['a']))
	err('参数错误');
$a = $sql->getData('SELECT `albumid`,`title`,`artist`,`cover` FROM `wb_music` ORDER BY `albumid` DESC');
if($a){
	$r = $a;
}
