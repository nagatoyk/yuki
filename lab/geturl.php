<?php
// $url = "http://www.baidu.com/link?url=NG5rRHoP_U6OF55nvq5Ok_6P7FQFTeKtJ1S0kVK8l68gFfeeOauHg-xNhIjWHYNs";
function curl_302($url){
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	// curl_setopt($ch, CURLOPT_HEADER, 1);
	// curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$data = curl_exec($ch);
	$Headers = curl_getinfo($ch);
	curl_close($ch);
	if($data != $Headers){
		return $Headers['url'];
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
	print_r(curl_302($url));
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