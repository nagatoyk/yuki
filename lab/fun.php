<?php
class SaeKV{
	function set($k, $v){
		$f = 'data/'.$k.'.json';
		return file_put_contents($f, json_encode($v));
	}
	function get($k){
		$f = 'data/'.$k.'.json';
		return file_exists($f)?json_decode(file_get_contents($f)):false;
	}
}
$kv = new SaeKV();

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
