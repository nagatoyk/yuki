<?php
if(!isset($_POST['url']) && !preg_match('/http\:\/\/www\.xiami\.com\/album\/[0-9]{5,12}/', $_POST['url']))
	err('参数错误');
function getName($id){
	$url = 'http://m.xiami.com/song/playlist/id/'.$id.'/object_name/default/object_id/0/cat/json?_='.time();
	$f = new SaeFetchurl();
	$json = json_decode($f->fetch($url), true);
	return $json['data']['trackList'][0]['title'];
}
function getLen($id){
	$url = 'http://m.xiami.com/song/playlist/id/'.$id.'/object_name/default/object_id/0/cat/json?_='.time();
	$f = new SaeFetchurl();
	$json = json_decode($f->fetch($url), true);
	return $json['data']['trackList'][0]['length'];
}

preg_match('/http\:\/\/www\.xiami\.com\/album\/([0-9]{5,12})/s', $_POST['url'], $u);
$f = new SaeFetchurl();
$file = $f->fetch($u[0]);
preg_match_all('/<(table)[^>]*class="track_list">(.*?)<\/\\1>/is', $file, $tbody);

preg_match_all('/<td[^>]*class="song_name">[^>]*<a[^>]*href="\/song\/([0-9]{5,12})"/is', $tbody[2][0], $data);

preg_match_all('/<img[^>]*class="cdCover185"[^>]*src="http\:\/\/img\.xiami\.net\/images\/album\/(img[0-9\/_]{1,}\.(jpg|png|bmp|gif))"[^>]*alt="(.*?)"\/>/', $file, $cover);

preg_match_all('/<td[^>]*valign="top">[^>]*<(a)[^>]*href="\/artist\/[0-9]{1,}"[^>]*title="[^>]*">(.*?)<\/\\1>/is', $file, $artist);

$list = array();
for($i = 0; $i < count($data[1]); $i++){
	$song_name = getName($data[1][$i]);
	if(!is_null($song_name)){
		$list[$data[1][$i]] = array(
			$song_name,
			getLen($data[1][$i])
		);
	}
}

$s = $sql->getLine('SELECT * FROM `wb_music` WHERE `albumid`=\''.$u[1].'\'');
if($s['albumid']){
	$l = str_replace('\\', '\\\\', json_encode($list));
	$sql->runSql('UPDATE `wb_music` SET `list`=\''.json_encode($l).'\' WHERE `albumid`=\''.$u[1].'\'');
}else{
	$l = str_replace('\\', '\\\\', json_encode($list));
	$sql->runSql('INSERT INTO `wb_music` (`albumid`,`title`,`artist`,`cover`,`list`) VALUES (\''.$u[1].'\',\''.$cover[3][0].'\',\''.$artist[2][0].'\',\''.$cover[1][0].'\',\''.$l.'\')');
}
$arr = array();
$data = $sql->getData('SELECT `albumid`,`title`,`artist`,`cover`,`list` FROM `wb_music` ORDER BY `albumid` DESC');
if($data){
	foreach($data as $k => $v){
		$arr[$v['albumid']] = array(
			$v['title'],
			$v['artist'],
			$v['cover'],
			json_decode($v['list'], true)
		);
	}
}
$kv->delete('music_xiami_list');
$kv->set('music_xiami_list', json_encode($arr));
$res = $sql->getLine('SELECT * FROM `wb_music` WHERE `albumid`=\''.$u[1].'\'');
$r = array(
	'albumid' => $res['albumid'],//$sql->lastId()
	'list' => json_decode($res['list'], true)
);

