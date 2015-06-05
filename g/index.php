<?php
require '../r/fun.php';
require '../r/Mysql.class.php';
require '../r/saetv2.ex.class.php';
$data = $sql->getData('SELECT `id` AS `wPid`,`uid` AS `sUid`,`url` AS `sinaimg`,FROM_UNIXTIME(unix, \'%Y-%m-%d %H:%i:%s\') AS `addtime`,`pid` AS `kPid` FROM `wb_pic` ORDER BY `unix` DESC');
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
	<tr>
		<td><?php echo $v['wPid']; ?></td>
		<td><?php echo $v['sUid']; ?></td>
		<td><img src="<?php echo str_replace('large', 'thumb150', $v['sinaimg']); ?>"></td>
		<td><?php echo $v['time']; ?></td>
		<td><?php echo $v['kPid'] ?></td>
	</tr>
	<?php endforeach; ?>
</table>
</body>
</html>