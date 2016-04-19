<?php
require 'r/fun.php';
require 'x/Mysql.class.php';
$r = $kv->get('music_xiami_list');
if(!$r){
	$arr = array();
	$data = $sql->getData('SELECT `albumId`,`title`,`artist`,`cover`,`list` FROM `music_list` ORDER BY `albumId` DESC');
	if($data){
		foreach($data as $k => $v){
			$list = unserialize($v['list']);
			// $list = json_decode($v['list'], true);
			if(count($list) > 0){
				$arr[$v['albumId']] = array(
					$v['title'],
					$v['artist'],
					$v['cover'],
					$list
				);
			}
		}
	}
	$kv->set('music_xiami_list', $arr);
	$r = $arr;
}
header('Content-type: application/json');
echo str_replace('&amp;', '&', str_replace('&#039;', '\'', json_encode($r)));
