<?php

	$host = getenv('OPENSHIFT_MYSQL_DB_HOST');
	$port = getenv('OPENSHIFT_MYSQL_DB_PORT');
	$dbname = getenv('OPENSHIFT_APP_NAME');
	$user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	$pwd = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	echo $host.'/'.$port.'/'.$dbname.'/'.$user.'/'.$pwd;
	$url = 'http://www.xiami.com/radio/xml/type/4/id/6961722?_='.time();
	echo file_get_contents($url);
	$file = fopen('../data/fm_getxml_log.txt', 'r');
	$user = array();
	$i = 0;
	//输出文本中所有的行，直到文件结束为止。
	while(!feof($file)){
		$user[$i] = fgets($file);//fgets()函数从文件指针中读取一行
		$i++;
	}
	fclose($file);
	$user = array_filter($user);
	echo '<pre>';
	print_r($user);
	echo '</pre>';