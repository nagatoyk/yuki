<?php
if(isset($_GET['url'])){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	// curl_setopt($ch, CURLOPT_PROXY, 'proxy.kancolle.tv'); //代理服务器地址
	curl_setopt($ch, CURLOPT_PROXY, '212.126.112.142');
	// curl_setopt($ch, CURLOPT_PROXYPORT, 9012); //代理服务器端口
	curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
	// curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	curl_setopt($ch, CURLOPT_URL, $_GET['url']);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0');
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	echo $file_contents;
}
