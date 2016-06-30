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