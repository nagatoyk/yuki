<?php
if(isset($_POST['albumId']) && is_numeric($_POST['albumId']) && $_POST['albumId'] > 0){
	$sql->runSql('DELETE FROM music_list WHERE albumId='.$_POST['albumId']);
	$r = array('msg'=>'删除成功');
}
