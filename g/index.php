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
	<style>
		table{width:100%;border:solid #add9c0;border-width:1px 0px 0px 1px}
		td{border:solid #add9c0;border-width:0px 1px 1px 0px;padding:10px 0px}
	</style>
</head>
<body>
<table>
	<tr>
		<th>序号</th>
		<th>用户ID</th>
		<th>预览图(150px)</th>
		<th>原图链接</th>
		<th>记录时间</th>
		<th>konachan(源)</th>
	</tr>
	<?php foreach($data as $k => $v): ?>
	<tr>
		<td><?php echo $v['wPid']; ?></td>
		<td><?php echo $v['sUid']; ?></td>
		<td><img src="<?php echo str_replace('large', 'thumb150', $v['sinaimg']); ?>"></td>
		<td><a href="<?php echo $v['sinaimg']; ?>" target="_blank">原图</a></td>
		<td><?php echo $v['addtime']; ?></td>
		<td><?php echo $v['kPid'] != 0 ? '<a href="//konachan.com/post/'.$v['kPid'].'" target="_blank">konachan.com</a>' : '本地'; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
</body>
</html>