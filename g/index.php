<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
$data = $sql->getData('SELECT `url` AS `imgurl`,FROM_UNIXTIME(unix, \'%Y-%m-%d %H:%i:%s\') AS time FROM `wb_pic` ORDER BY `unix` DESC');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>图片管理</title>
</head>
<body>
<table>
	<?php foreach($data as $k => $v): ?>
	<tr><td><img src="<?php echo str_replace('large', 'thumb150', $v['imgurl']); ?>"></td><td><?php echo $v['time']; ?></td></tr>
	<?php endforeach; ?>
</table>
</body>
</html>