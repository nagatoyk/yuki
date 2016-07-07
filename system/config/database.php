<?php
return [
	//读库列表
	'read'     => [ ],
	//写库列表
	'write'    => [ ],
	//开启读写分离
	'proxy'    => FALSE,
	//主机
	'host'     => getenv('OPENSHIFT_MYSQL_DB_HOST') ? getenv('OPENSHIFT_MYSQL_DB_HOST') : '127.0.0.1',
	//类型
	'driver'   => 'mysql',
	//帐号
	'user'     => getenv('OPENSHIFT_MYSQL_DB_USERNAME') ? getenv('OPENSHIFT_MYSQL_DB_USERNAME') : 'root',
	//密码
	'password' => getenv('OPENSHIFT_MYSQL_DB_PASSWORD')?getenv('OPENSHIFT_MYSQL_DB_PASSWORD') : '',
	//数据库
	'database' => getenv('OPENSHIFT_APP_NAME') ? getenv('OPENSHIFT_APP_NAME') : 'yuki',
	//表前缀
	'prefix'   => 'imouto_'
];