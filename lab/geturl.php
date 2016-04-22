<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<form method="post">
	<input type="text" name="u">
	<input type="submit" value="提交">
</form>
<?php
// $url = "http://www.baidu.com/link?url=NG5rRHoP_U6OF55nvq5Ok_6P7FQFTeKtJ1S0kVK8l68gFfeeOauHg-xNhIjWHYNs";
$url = $_POST['u'];
if(!empty($url)){
	$info = parse_url($url);
	echo '<pre>';
	print_r($info);
	// print_r(curl_302($url));
	echo '</pre>';

	$fp = fsockopen($info['host'], 80, $errno, $errstr, 30);
	fputs($fp, 'GET '.$info['path'].'?'.$info['query'].' HTTP/1.1'."\r\n");
	fputs($fp, 'Host: '.$info['host']."\r\n");
	fputs($fp, 'Connection: close'."\r\n\r\n");
	$rewrite = '';
	echo '<p>';
	while(!feof($fp)){
		$line = fgets($fp);
		echo str_replace("\r\n", '</p><p>', $line);
		if($line != "\r\n"){
			if(strpos($line, 'Location:') !== false){
				$rewrite = str_replace(array("\r", "\n", 'Location: '),'', $line);
			}
		}else{
			break;
		}
	}
	echo '</p>';
	echo $rewrite;
}