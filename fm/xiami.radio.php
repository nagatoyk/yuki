<?php
$out = array();
function getLocation($location){
	$loc_2 = (int)substr($location, 0, 1);
	$loc_3 = substr($location,1);
	$loc_4 = floor(strlen($loc_3) / $loc_2);
	$loc_5 = strlen($loc_3) % $loc_2;
	$loc_6 = array();
	$loc_7 = 0;
	$loc_8 = '';
	$loc_9 = '';
	$loc_10 = '';
	while($loc_7 < $loc_5){
		$loc_6[$loc_7] = substr($loc_3, ($loc_4 + 1) * $loc_7, $loc_4 + 1);
		$loc_7++;
	}
	$loc_7 = $loc_5;
	while($loc_7 < $loc_2){
		$loc_6[$loc_7] = substr($loc_3, $loc_4 * ($loc_7 - $loc_5) + ($loc_4 + 1) * $loc_5, $loc_4);
		$loc_7++;
	}
	$loc_7 = 0;
	while($loc_7 < strlen($loc_6[0])){
		$loc_10 = 0;
		while($loc_10 < count($loc_6)){
			$loc_8 .= $loc_6[$loc_10][$loc_7];
			$loc_10++;
		}
		$loc_7++;
	}
	$loc_9 = str_replace('^', 0, urldecode($loc_8));
	return $loc_9;
}
function rand_ip(){
	$cip = '123.125.68.'.mt_rand(0, 254);
	$xip = '125.90.88.'.mt_rand(0, 254);
	return array( 
		'CLIENT-IP:'.$cip, 
		'X-FORWARDED-FOR:'.$xip, 
	);
}
function writelog($str){
	$open = fopen('../data/fm_getxml_log.txt', 'a');
	fwrite($open, $str);
	fclose($open);
} 
function get_xml($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, rand_ip());
	// curl_setopt($ch, CURLOPT_INTERFACE, '218.66.112.178');

	if(!curl_exec($ch)){
		// Log::write(curl_errno($ch));
		$error = curl_errno($ch);
		$data = $error;
	}else{
		$data = curl_multi_getcontent($ch);
	}
	curl_close($ch);
	return $data;
}
function simplest_xml_to_array($xmlstring){
	return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
}
if($_GET['a'] == 'radio' && $_GET['rid'] == 11){
	$url = 'http://www.xiami.com/radio/xml/type/8/id/6961722';
	$file = get_xml($url);
	$json = simplest_xml_to_array($file);
	echo '<pre>';
	print_r($json);
	die();
	$doc = new DOMDocument();
	$doc->load($url);
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
			'length'=>$length,
			'play'=>1
		);
	}
}

// header('Content-type: application/json;charset=utf-8');
echo json_encode($out);
