<?php

	$host = getenv('OPENSHIFT_MYSQL_DB_HOST');
	$port = getenv('OPENSHIFT_MYSQL_DB_PORT');
	$dbname = getenv('OPENSHIFT_APP_NAME');
	$user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	$pwd = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	echo $host.'/'.$port.'/'.$dbname.'/'.$user.'/'.$pwd;