<?php
// $url = "http://www.baidu.com/link?url=NG5rRHoP_U6OF55nvq5Ok_6P7FQFTeKtJ1S0kVK8l68gFfeeOauHg-xNhIjWHYNs";
$url = isset($_POST['u']) ? $_POST['u'] : isset($_GET['u']) ? $_GET['u'] : null;
echo $url;
if(!empty($url)){
	$info = parse_url($url);

	$fp = fsockopen($info['host'], 80, $errno, $errstr, 30);
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
	echo $rewrite;
}