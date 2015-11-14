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
		return gethostbyname($_SERVER["SERVER_NAME"]);
	}
}
$getIP = new getIP();
if(!empty($_POST['sub'])){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_POST['url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	curl_setopt($ch, CURLOPT_PROXY, $getIP::serverIP()); //代理服务器地址
	curl_setopt($ch, CURLOPT_PROXYPORT, 80); //代理服务器端口
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	$file_contents = curl_exec($ch);
	curl_close($ch);
	echo $file_contents;
}
