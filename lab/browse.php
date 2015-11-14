<form action="" method="post">
	<input type="text" name="url">
	<input type="submit" name="sub" value="提交">
</form>
<?php
class getIP{
	public static function clientIP(){
		$cIP = getenv('REMOTE_ADDR');
		$cIP1 = getenv('HTTP_X_FORWARDED_FOR');
		$cIP2 = getenv('HTTP_CLIENT_IP');
		$cIP1 ? $cIP = $cIP1 : null;
		$cIP2 ? $cIP = $cIP2 : null;
		return $cIP;
	}
	public static function serverIP(){
		return gethostbyname($_SERVER['SERVER_NAME']);
	}
}
$getIP = new getIP();
echo $getIP::serverIP();
if(!empty($_POST['sub'])){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	// curl_setopt($ch, CURLOPT_PROXY, 'proxy.kancolle.tv'); //代理服务器地址
	curl_setopt($ch, CURLOPT_PROXY, '212.126.112.142');
	// curl_setopt($ch, CURLOPT_PROXYPORT, 9012); //代理服务器端口
	curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
	// curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	curl_setopt($ch, CURLOPT_URL, $_POST['url']);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	$file_contents = preg_replace('/<title>(.*?)<\/title>/', "<title>$1</title>\n\r<base href=\"http://zh.moegirl.org/\">", $file_contents);
	$file_contents = preg_replace('/^\/\/(bits\.moegirl\.org\/zh\/load\.php.*?)\"$/', "/lab/load.php?l=$1", $file_contents);

	/*preg_match_all('/^(href="\/)$/', $file_contents, $mat);
	for($i = 0; $i < count($mat[1]); $i++){
		$file_contents .= str_replace($mat[1][$i], 'href="//zh.moegirl.org/', $file_contents);
	}*/
	echo $file_contents;
}
