<?php
if(!isset($_POST['url']) && !preg_match('/http\:\/\/www\.xiami\.com\/album\/[0-9]{5,12}/', $_POST['url']))
	err('参数错误');
function getName($id){
	$url = 'http://m.xiami.com/song/playlist/id/'.$id.'/object_name/default/object_id/0/cat/json?_='.time();
	$json = json_decode(file_get_contents($url), true);
	return $json['data']['trackList'][0]['title'];
}
function getLen($id){
	$url = 'http://m.xiami.com/song/playlist/id/'.$id.'/object_name/default/object_id/0/cat/json?_='.time();
	$json = json_decode(file_get_contents($url), true);
	return $json['data']['trackList'][0]['length'];
}
function file_get($url){
	// 初始化
	$ch = curl_init();
	// 设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:114.114.114.114', 'CLIENT-IP:114.114.114.114', 'User-Agent:Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_1_2 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7D11 Safari/528.16'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	//释放curl句柄
	curl_close($ch);
	//打印获得的数据
	return $output;
}

preg_match('/http\:\/\/www\.xiami\.com\/album\/([0-9]{5,12})/s', $_POST['url'], $u);
$url = 'http://www.xiami.com/album/'.$u[1];
// $file = file_get_contents($url);
$file = file_get($url);

echo $file;
preg_match_all('/<a[^>]*href="\/song\/([0-9]{5,12})"[^>]*title/is', $file, $data);
preg_match('/<meta[^>]*property="og\:title" content="(.*?)"\/>/is', $file, $title);
preg_match('/<meta[^>]*property="og\:music\:artist" content="(.*?)"\/>/', $file, $artist);
preg_match_all('/<img[^>]*class="cdCover185"[^>]*src="http\:\/\/img\.xiami\.net\/images\/album\/(img[0-9\/_]{1,}\.(jpg|png|bmp|gif))"[^>]*alt="(.*?)"\/>/', $file, $cover);
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
print_r($data);
// echo json_encode($list);
die();
$s = $sql->getLine('SELECT * FROM `music_list` WHERE `albumId`=\''.$u[1].'\'');
if($s['albumId']){
	// $l = str_replace('\\', '\\\\', json_encode($list));
	$sql->runSql('UPDATE `music_list` SET `list`=\''.serialize($list).'\',`title`=\''.$title[1].'\',`artist`=\''.$artist[1].'\',`cover`=\''.$cover[1][0].'\' WHERE `albumId`=\''.$u[1].'\'');
}else{
	/*array(
						'albumId'=>$u[1],
						'title'=>$title[1],
						'artist'=>$artist[1],
						'cover'=>$cover[1][0],
						'list'=>serialize($list))*/
	// $l = str_replace('\\', '\\\\', json_encode($list));
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
$r = array(
	'albumId' => $u[1],//$sql->lastId()
	'list' => $list
);

