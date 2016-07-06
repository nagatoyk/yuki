<?php
$wb_id = '1093396876';
$wb_key = 'd126f1302a7f1b7e36536f4ad84622a0';
$wb_url = 'http://kloli.tk/login.php';
class KV{
	public function set($k, $v){
		$f = '../data/'.$k.'.json';
		return file_put_contents($f, json_encode($v));
	}
	public function get($k){
		$f = '../data/'.$k.'.json';
		return file_exists($f) ? json_decode(file_get_contents($f), true) : null;
	}
	public function delete($filename){
		$filename = '../data/'.$filename.'.json';
		return file_exists($filename) ? unlink($filename) : false;
	}
}
/*
*功能：对字符串进行加密处理
*参数一：需要加密的内容
*参数二：密钥
*/
function passport_encrypt($str, $key){ //加密函数
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($str); $i++){
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($str[$i] ^ $encrypt_key[$ctr++]);
	}
	return base64_encode(passport_key($tmp, $key));
}
/*
*功能：对字符串进行解密处理
*参数一：需要解密的密文
*参数二：密钥
*/
function passport_decrypt($str, $key){ //解密函数
	$str = passport_key(base64_decode($str), $key);
	$tmp = '';
	for($i = 0; $i < strlen($str); $i++){
		$md5 = $str[$i];
		$tmp .= $str[++$i] ^ $md5;
	}
	return $tmp;
}
/*
*辅助函数
*/
function passport_key($str, $encrypt_key){
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($str); $i++){
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $str[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}



if(defined('SAE_MYSQL_DB')){
	$kv=new SaeKV();
	$kv->init();
}else{
	$kv = new KV();
}
$config = array(
	'title' => '小熊\'博客',
	'url' => 'http://kloli.tk',
	'description' => '小熊的博客'
);