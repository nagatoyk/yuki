<?php 

/**
 * User: TMs
 * Date: 2016-06-06
 * Time: 15:30
 */

require 'DB_driver.php';
// 当前可选driver:Mysql,PDO;
$db_driver = 'PDO';
if(defined('MYSQL_DATABASE')){
	$host = 'localhost';
	$port = 3306;
	$dbname = MYSQL_DATABASE;
	$user = MYSQL_USERNAME;
	$pwd = MYSQL_PASSWORD;
}elseif(getenv('OPENSHIFT_APP_NAME')){
	$host = getenv('OPENSHIFT_MYSQL_DB_HOST');
	$port = getenv('OPENSHIFT_MYSQL_DB_PORT');
	$dbname = getenv('OPENSHIFT_APP_NAME');
	$user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	$pwd = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
}else{
	$host = 'localhost';
	$port = 3306;
	$dbname = 'yuki';
	$user = 'root';
	$pwd = '';
}

$driver = 'DB_'.$db_driver;
$sql = new $driver($host, $port, $user, $pwd, $dbname);