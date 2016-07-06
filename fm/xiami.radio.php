<?php
require '../x/mysql.class.php';
function writelog($str){
	$open = fopen('../data/fm_getxml_log.txt', 'a');
	fwrite($open, $str);
	fclose($open);
} 
function rand_ip(){
	$cip = '123.125.68.'.mt_rand(0, 254);
	$xip = '125.90.88.'.mt_rand(0, 254);
	return array( 
		'CLIENT-IP:'.$cip, 
		'X-FORWARDED-FOR:'.$xip, 
	);
}
function get_xml($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	// curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(
		// rand_ip(),
		'CLIENT-IP:127.0.0.1',
		'X-FORWARDED-FOR:127.0.0.1',
		'Cookie:gid=146779186835297; _xiamitoken=36fba1f459be524f64a15b4f0fe9a4ab; _unsign_token=8fcb821a564a40d2658fd9dddaa984a6; cna=VtugD0Sg10cCATs9NEDzOAwl; member_auth=1GnLE9tCv25i1%2FeTSIA4InVL6LLQEmOCwI5Y3uN8sFN3J4laMourlquXSw1J3yKRkYG6uGUmXO3Omzjxk%2BCh; user=6961722%22%E9%95%9C%E8%8A%B1%E6%B0%B4%E6%9C%88%22images%2Favatar_new%2F139%2F23%2F6961722%2F6961722_1322238218_1.jpg%220%227376%22%3Ca+href%3D%27%2Fwebsitehelp%23help9_3%27+%3Esi%3C%2Fa%3E%2220%22357%2223798%2246ba2b68d2%221467791879; CNZZDATA921634=cnzz_eid%3D472386582-1467787454-http%253A%252F%252Fwww.xiami.com%252F%26ntime%3D1467787454; CNZZDATA2629111=cnzz_eid%3D735352521-1467787455-http%253A%252F%252Fwww.xiami.com%252F%26ntime%3D1467787455; t_sign_auth=1; l=AouL3BUbfWB3nhraTKSAhqtCmyV1IJ-i; isg=AsjIp2FOC5cEymcbrIaaCHQsmTZVzSx7xwmh44J5FMM2XWjHKoH8C15dq4LX',
		// 'Host:www.xiami.com',
		// 'Referer:http://www.xiami.com/radio/play/type/4/oid/6961722',
		'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.19 Safari/537.36'
	));
	if(!curl_exec($ch)){
		$errno = curl_errno($ch);
		writelog('抓取失败, 错误码->'.$errno);
		$data = false;
	}else{
		$data = curl_multi_getcontent($ch);
	}
	curl_close($ch);
	return $data;
}
function get_playcount($sql, $pid, $rid = 11){
	$r = $sql->getLine('SELECT `pcount` FROM imouto_playcount WHERE pid=\''.$pid.'\' AND rid=\''.$rid.'\'');
	return $r ? $r['pcount'] : 0;
}
$out = array();
if($_GET['a'] == 'radio' && $_GET['rid'] == 11){
	$url = 'http://www.xiami.com/radio/xml/type/4/id/6961722?v='.time();
	$xml = get_xml($url);
	if($xml){
		$doc = new DOMDocument();
		$doc->loadXML($xml);
		$items = $doc->getElementsByTagName('track');
		foreach($items as $node){
			$song_ids = $node->getElementsByTagName('song_id');
			$titles = $node->getElementsByTagName('title');
			$pics = $node->getElementsByTagName('pic');
			$locations = $node->getElementsByTagName('location');
			$album_names = $node->getElementsByTagName('album_name');
			$artists = $node->getElementsByTagName('artist');
			$album_ids = $node->getElementsByTagName('album_id');
			$lengths = $node->getElementsByTagName('length');
			$song_id = $song_ids->item(0)->nodeValue;
			$title = $titles->item(0)->nodeValue;
			$pic = $pics->item(0)->nodeValue;
			$location = $locations->item(0)->nodeValue;
			$artist = $artists->item(0)->nodeValue;
			$album_name = $album_names->item(0)->nodeValue;
			$album_id = $album_ids->item(0)->nodeValue;
			$length = $lengths->item(0)->nodeValue;
			$out[] = array(
				'xid'=>$song_id,
				'title'=>htmlspecialchars_decode($title, ENT_QUOTES),
				'img'=>str_replace('http://img.xiami.net/images/album/', '', $pic),
				'mp3'=>$location,
				'album_name'=>htmlspecialchars_decode($album_name, ENT_QUOTES),
				'artist'=>htmlspecialchars_decode($artist, ENT_QUOTES),
				'album_id'=>$album_id,
				'length'=>$length*1000000,
				'play'=>get_playcount($sql, $song_id)
			);
		}
	}else{
		$out = array();
	}

}
header('Content-type: application/json;charset=utf-8');
echo json_encode($out);
