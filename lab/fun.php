<?php
date_default_timezone_set('Asia/Shanghai');
// error_reporting(0);
// header('Content-Type: text/html; charset=UTF-8');
// 微博已上线站点api数据
$wb_id = '1093396876';
$wb_key = 'd126f1302a7f1b7e36536f4ad84622a0';
$wb_url = 'https://yuki-yukimax.rhcloud.com/lab/callback.php';
/**
*
*/
class KV{
	private $path = getenv('OPENSHIFT_DATA_DIR').'data/';
	public function __construct($path){
		$path = $path != '' ? $path : $this->path;
		if(!file_exists($path)){
			is_dir($path) || mkdir($path);
			chmod($path, 0777);
		}
		$this->path = $path;
	}
	public function set($k, $v){
		$f = $this->path.$k.'.json';
		return file_put_contents($f, json_encode($v));
	}
	function get($k){
		$f = $this->path.$k.'.json';
		return file_exists($f)?json_decode(file_get_contents($f)):null;
	}
}
$kv = new KV();

function in_arr($val, $arr, $num=0){
	foreach($arr as $a)
		if($a[$num]==$val)return true;
	return false;
}

function arr_indexOf($val, $arr, $num=0){
	for($i=0, $l=count($arr); $i<$l;$i++)
		if($arr[$i][$num]===$val)
			return $i;
	return -1;
}
