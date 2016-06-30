<?php
if(!isset($_GET['a']) && !preg_match('/^dm$/', $_GET['a']))
	err('参数错误');
if(!preg_match('/^[0-9]+$/', $_POST['albumId']))
	err('您输入的 [albumId] 格式不正确！');
$sql->runSql('DELETE FROM `music_list` WHERE `albumId`=\''.$_POST['albumId'].'\'');
$r = array('msg'=>'删除成功');

