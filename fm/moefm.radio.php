<?php
$url = 'http://moe.fm/listen/playlist?api=json&api_key=18f95c02504fb5a0fdd83b205e7e1aee05421a58b&_='.time();
$out = array();
if($_GET['a'] == 'radio'){
	$url .= '&perpage=3';
	$json = json_decode(file_get_contents($url));
	$data = $json['response']['playlist'];
	foreach($data as $key => $val){
		$out[] = array(
			'xid' => $val['sub_id'],
			'title'=>htmlspecialchars_decode($val['sub_title'], ENT_QUOTES),
			'img'=>$val['cover']['large'],//str_replace('http://img.xiami.net/images/album/', '', $val['cover']['large']),
			'mp3'=>$val['url'],
			'album_name'=>htmlspecialchars_decode($val['wiki_title'], ENT_QUOTES),
			'artist'=>htmlspecialchars_decode($val['artist'], ENT_QUOTES),
			'album_id'=>$val['wiki_id'],
			'length'=>$val['stream_time'],
			'play'=>1
		);
	}
}elseif($_GET['a'] == 'song'){
	$url .= '&song='.(int)$_GET['id'];
	$json = json_decode(file_get_contents($url));
	$out[] = array(
		'xid' => $data['sub_id'],
		'title'=>htmlspecialchars_decode($data['sub_title'], ENT_QUOTES),
		'img'=>$data['cover']['large'],//str_replace('http://img.xiami.net/images/album/', '', $data['cover']['large']),
		'mp3'=>$data['url'],
		'album_name'=>htmlspecialchars_decode($data['wiki_title'], ENT_QUOTES),
		'artist'=>htmlspecialchars_decode($data['artist'], ENT_QUOTES),
		'album_id'=>$data['wiki_id'],
		'length'=>$data['stream_time'],
		'play'=>1
	);
}
header('Content-type: application/json;charset=utf-8');
echo json_encode($out);
