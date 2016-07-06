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
		rand_ip(),
		'Cookie:gid=146642559728168; _unsign_token=98c066501ffe6817845a6e0c1c7869b3; cna=VtugD0Sg10cCATs9NEDzOAwl; CNZZDATA3438709=cnzz_eid%3D705339371-1466424059-http%253A%252F%252Fwww.xiami.com%252F%26ntime%3D1466429477; bdshare_firstime=1466429924320; list_mode=list; member_auth=1GnLE9tCv25i1%2FeTSIA4InVL6LLQEmOCwI5Y3uN8sFN3J4laMourlquXSw1J3yKRkYG6uGUmXO3Omzjxk%2BCh; user=6961722%22%E9%95%9C%E8%8A%B1%E6%B0%B4%E6%9C%88%22images%2Favatar_new%2F139%2F23%2F6961722%2F6961722_1322238218_1.jpg%220%227355%22%3Ca+href%3D%27%2Fwebsitehelp%23help9_3%27+%3Esi%3C%2Fa%3E%2220%22357%2223783%2246ba2b68d2%221467683865; _xiamitoken=f706db26ca947c713503f3766ebf39c6; t_sign_auth=3; radio_guest_listen=792553; __utmt=1; __utma=251084815.1886506452.1466426043.1467683645.1467787854.4; __utmb=251084815.1.10.1467787854; __utmc=251084815; __utmz=251084815.1466426043.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); CNZZDATA921634=cnzz_eid%3D1698568445-1466424631-null%26ntime%3D1467787454; CNZZDATA2629111=cnzz_eid%3D756384560-1466420615-null%26ntime%3D1467787455; __guestplay=MTc3MjA3NzgyNCwxOzE3NzM3NDc2NDUsMQ%3D%3D; l=AtzcfZXGwt3MEw1vx9FPy/a6LPCPo4B/; isg=AkVFsBLxvrgfKppsKbUvl2nvVIH1MPmUimp89Eer93yL3mRQDlAmZEr2lrXS',
		'Host:www.xiami.com',
		'Referer:http://www.xiami.com/radio/play/type/4/oid/6961722',
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
