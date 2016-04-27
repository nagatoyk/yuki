<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<form method="post">
	<input type="text" name="u">
	<input type="submit" value="提交">
</form>
<?php
// $url = "http://www.baidu.com/link?url=NG5rRHoP_U6OF55nvq5Ok_6P7FQFTeKtJ1S0kVK8l68gFfeeOauHg-xNhIjWHYNs";
require 'fun.php';
require '../r/Mysql.class.php';
// require '../r/saetv2.ex.class.php';
$my_token = $kv->get('my_token');
$token = $my_token['1687199364'];
// $c = new SaeTClientV2($wb_id, $wb_key, $token['access_token']);
$url = $_POST['u'];
if(!empty($url)){
	// echo file_get_contents('https://api.weibo.com/2/short_url/shorten.json?access_token='.$wb_id.'&url_short='.$url);
	$ch = curl_init('https://api.weibo.com/2/short_url/shorten.json?source='.$wb_id.'&url_short='.$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	echo $data;
	// $json = json_decode(file_get_contents('https://api.weibo.com/2/short_url/shorten.json?access_token='.$wb_id.'&url_short='.$url), true);
	echo '<pre>';
	// print_r($json);
}