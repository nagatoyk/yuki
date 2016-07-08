<?php
if(!isset($_GET['a']) && !preg_match('/^gm$/', $_GET['a']))
	err('参数错误');
$a = $sql->getData('SELECT `albumId`,`title`,`artist`,`cover` FROM `music_list` ORDER BY `albumId` DESC');
if($a){
	$r = $a;
}
