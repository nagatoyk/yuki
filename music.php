<?php
require 'r/fun.php';
require 'x/mysql.class.php';
$kv->delete('music_xiami_list');
$r = $kv->get('music_xiami_list');
if(!$r){
	$arr = array();
	$data = $sql->getData('SELECT `albumId`,`title`,`artist`,`cover`,`list` FROM `music_list` ORDER BY `albumId` DESC');
	if($data){
		foreach($data as $k => $v){
			if(count($list) > 0){
				$arr[$v['albumId']] = array(
					$v['title'],
					$v['artist'],
					$v['cover'],
					unserialize($v['list'])
				);
			}
		}
	}
	$kv->set('music_xiami_list', $arr);
	$r = $arr;
}
header('Content-type: application/json');
echo json_encode($r);