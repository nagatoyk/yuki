<?php
// $url = "http://www.baidu.com/link?url=NG5rRHoP_U6OF55nvq5Ok_6P7FQFTeKtJ1S0kVK8l68gFfeeOauHg-xNhIjWHYNs";
function curl_post_302($url, $vars = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	if(!is_null($vars))
		curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
	if(!is_null($vars))
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	$data = curl_exec($ch);
	$Headers =  curl_getinfo($ch);
	curl_close($ch);
	if($data != $Headers){
		return  $Headers['url'];
	}else{
		return false;
	}
}
$url = isset($_POST['u']) ? $_POST['u'] : isset($_GET['u']) ? $_GET['u'] : null;
echo $url;
if($url){
	$info = parse_url($url);
	echo '<pre>';
	print_r($info);
	print_r(curl_post_302($url));
	echo '</pre>';

	/*$fp = fsockopen($info['host'], 80, $errno, $errstr, 30);
	fputs($fp, 'GET '.$info['path'].'?'.$info['query'].' HTTP/1.1'."\r\n");
	fputs($fp, 'Host: '.$info['host']."\r\n");
	fputs($fp, 'Connection: close'."\r\n\r\n");
	$rewrite = '';
	while(!feof($fp)){
		$line = fgets($fp);
		if($line != "\r\n"){
			if(strpos($line, 'Location:') !== false){
				$rewrite = str_replace(array("\r", "\n", 'Location: '),'', $line);
			}
		}else{
			break;
		}
	}
	echo $rewrite;*/
}