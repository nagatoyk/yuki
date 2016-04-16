<?php
$kv = new SaeKV();
$kv->init();
$sql = new SaeMysql();
$kv->delete('music_xiami_list');
$r = json_decode($kv->get('music_xiami_list'), true);
if(!$r){
	$arr = array();
	$data = $sql->getData('SELECT `albumid`,`title`,`artist`,`cover`,`list` FROM `wb_music` ORDER BY `albumid` DESC');
	if($data){
		foreach($data as $k => $v){
			$list = json_decode($v['list'], true);
			if(count($list) > 0){
				$arr[$v['albumid']] = array(
					$v['title'],
					$v['artist'],
					$v['cover'],
					$list
				);
			}
		}
	}
	$kv->set('music_xiami_list', json_encode($arr));
	$r = $arr;
}
header('Content-type: application/json');
echo str_replace('&amp;', '&', str_replace('&#039;', '\'', json_encode($r)));
