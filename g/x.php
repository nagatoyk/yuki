<?php
require '../r/fun.php';
if(isset($_GET['del']) && preg_match('/^[0-9]{1,}$/u', $_GET['id'])){
	echo $sql->runSql('DELETE FROM `wb_pic` WHERE `id`=\''.$_GET['id'].'\'');
}
if(isset($_GET['update']) && preg_match('/^[0-9]{1,}$/', $_GET['id'])){
	if(isset($_POST['url'])){
		echo $sql->runSql('UPDATE `wb_pic` SET `url`=\''.$_POST['url'].'\' WHERE `id`=\''.$_GET['id'].'\'');
	}elseif(isset($_POST['source']) && preg_match('/^[0-9]{1,}$/', $_GET['id'])){
		echo $sql->runSql('UPDATE `wb_pic` SET `source`=\''.$_POST['source'].'\' WHERE `id`=\''.$_GET['id'].'\'');
	}
}