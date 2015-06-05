<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
$data = $sql->getData('SELECT `url` AS `imgurl`,FROM_UNIXTIME(unix, \'%Y-%m-%d %H:%i:%s\') AS time FROM `wb_pic` ORDER BY `unix` DESC');
echo '<pre>';
print_r($data);
echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>图片管理</title>
</head>
<body>
<ul>
	<li><img src="<?php echo $imgurl; ?>"></li>
</ul>
</body>
</html>