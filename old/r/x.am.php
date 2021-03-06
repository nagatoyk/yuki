<?php
if(!isset($_POST['url']) && !preg_match('/http\:\/\/www\.xiami\.com\/album\/[0-9]{5,12}/', $_POST['url']))
	err('参数错误');
function getSongInfo($id){
	$url = 'http://m.xiami.com/song/playlist/id/'.$id.'/object_name/default/object_id/0/cat/json?_='.time();
	$json = json_decode(file_get_contents($url), true);
	return array(
		$json['data']['trackList'][0]['title'],
		$json['data']['trackList'][0]['length']
	);
}

preg_match('/http\:\/\/www\.xiami\.com\/album\/([0-9]{5,12})/s', $_POST['url'], $u);
$url = 'http://www.xiami.com/album/'.$u[1].'?_='.time();
$file = file_get_contents($url);
preg_match_all('/<a[^>]*href="\/song\/([0-9]{5,12})"[^>]*title/is', $file, $data);
if(count($data[1]) == 0)
	err('无数据!--->'.count($data[1]));
preg_match('/<meta[^>]*property="og\:title" content="(.*?)"\/>/is', $file, $title);
preg_match('/<meta[^>]*property="og\:music\:artist" content="(.*?)"\/>/', $file, $artist);
preg_match_all('/<img[^>]*class="cdCover185"[^>]*src="http\:\/\/img\.xiami\.net\/images\/album\/(img[0-9\/_]{1,}\.(jpg|png|bmp|gif))"[^>]*alt="(.*?)"\/>/', $file, $cover);
$list = array();
for($i = 0; $i < count($data[1]); $i++){
	if(!is_null($song_name)){
		$list[$data[1][$i]] = getSongInfo($data[1][$i]);
	}
}
$s = $sql->getLine('SELECT * FROM `music_list` WHERE `albumId`=\''.$u[1].'\'');
if($s['albumId']){
	$sql->runSql('UPDATE `music_list` SET `list`=\''.serialize($list).'\',`title`=\''.$title[1].'\',`artist`=\''.$artist[1].'\',`cover`=\''.$cover[1][0].'\' WHERE `albumId`=\''.$u[1].'\'');
}else{
	$sql->runSql('INSERT INTO `music_list` (`albumId`,`title`,`artist`,`cover`,`list`) VALUES (\''.$u[1].'\',\''.$title[1].'\',\''.$artist[1].'\',\''.$cover[1][0].'\',\''.serialize($list).'\')');
}
$arr = array();
$data = $sql->getData('SELECT `albumId`,`title`,`artist`,`cover`,`list` FROM `music_list` ORDER BY `albumId` DESC');
if($data){
	foreach($data as $k => $v){
		$arr[$v['albumId']] = array(
			$v['title'],
			$v['artist'],
			$v['cover'],
			unserialize($v['list'])
		);
	}
}
$kv->delete('music_xiami_list');
$kv->set('music_xiami_list', $arr);
$res = $sql->getLine('SELECT `list` FROM `music_list` WHERE `albumId`=\''.$u[1].'\'');
$r = array(
	'albumId' => $u[1],//$sql->lastId()
	'list' => unserialize($res['list'])
);

