<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
if(isset($_GET['addUrl']) && isset($_POST['url'])){
	echo $sql->runSql('INSERT INTO `wb_pic` (`id`, `uid`, `url`, `unix`, `source`, `pid`) VALUES (NULL, \'1687199364\', \''.$_POST['url'].'\', UNIX_TIMESTAMP(), NULL, \'0\');');
}
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