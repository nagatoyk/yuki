<?php
require '../r/fun.php';
$size = 10;
$s = (int)(isset($_GET['p']) ? $_GET['p'] - 1 : 0) * $size;
$data = $sql->getData('SELECT `id` AS `wPid`,`uid` AS `sUid`,`url` AS `sinaimg`,FROM_UNIXTIME(unix, \'%Y-%m-%d %H:%i:%s\') AS `addtime`,`source`,`pid` AS `kPid` FROM `wb_pic` ORDER BY `unix` DESC LIMIT '.$s.','.$size);
$dmax = $sql->getLine('SELECT COUNT(*) AS `max` FROM `wb_pic`');
$pmax = ceil($dmax['max'] / $size);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<title>图片管理</title>
<style>
	table{width:100%;border:solid #add9c0;border-width:1px 0px 0px 1px}
	td{text-align:center;border:solid #add9c0;border-width:0px 1px 1px 0px;padding:5px 0px;margin:0px}
	/*input[type="text"]{width:100%;height:100%;text-align:top}*/
	textarea{resize:none;width:95%;height:100%}
</style>
<table>
	<tr>
		<th width="50px">序号</th>
		<th width="100px">用户ID</th>
		<th width="155px">预览图(150px)</th>
		<th width="70px">原图链接</th>
		<th width="250px">替换用</th>
		<th width="170px">记录时间</th>
		<th width="120px">源</th>
		<th width="250px">替换用</th>
		<th>删除</th>
	</tr>
	<?php foreach($data as $k => $v): ?>
	<tr>
		<td>
			<?php echo $v['wPid']; ?>
		</td>
		<td>
			<?php echo $v['sUid']; ?>
		</td>
		<td>
			<img id="img<?php echo $v['wPid'] ?>" src="<?php echo str_replace('large', 'thumb150', $v['sinaimg']); ?>">
		</td>
		<td>
			<a id="url<?php echo $v['wPid']; ?>" href="<?php echo $v['sinaimg']; ?>" target="_blank">原图</a>
		</td>
		<td>
			<textarea cols="10" rows="5" id="<?php echo $v['wPid']; ?>" name="url" onchange="javascript:update(this)"><?php echo $v['sinaimg']; ?></textarea>
		</td>
		<td>
			<?php echo $v['addtime']; ?>
		</td>
		<td>
			<a id="source<?php echo $v['wPid']; ?>" href="<?php echo $v['source']; ?>" target="_blank">源址</a>
		</td>
		<td>
			<textarea cols="10" rows="5" id="<?php echo $v['wPid']; ?>" name="source" onchange="javascript:update(this)"><?php echo $v['source']; ?></textarea>
		</td>
		<td>
			<input type="button" onclick="javascript:del(<?php echo $v['wPid']; ?>)" value="删除">
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<th colspan="9">
			<?php for($i = 1; $i < $pmax + 1; $i++): ?>
			<?php if($i == 1 && ($_GET['p'] == 1 || !isset($_GET['p']))): ?>
			-- 第 1 页 --
			<?php elseif($i == $_GET['p']): ?>
			-- 第 <?php echo $i; ?> 页 --
			<?php else: ?>
			<a href="?p=<?php echo $i; ?>">-- 第 <?php echo $i; ?> 页 --</a>
			<?php endif; ?>
			<?php endfor; ?>
		</th>
	</tr>
</table>
<script src="//yukimax.sinaapp.com/f/iTorr.js"></script>
<script>
var del = function(id) {
	console.log(id);
	$.x('x.php?del&id=' + id, function(r) {
		if (r == 1) {
			location.reload(true)
		}
	})
},
update = function(ele) {
	$.x('x.php?update&id=' + ele.id, ele.name + '=' + ele.value, function(r) {
		if (r == 1) {
			console.log('修改成功(' + ele.name + '=' + ele.value + ')');
			$('#' + ele.name + ele.id).href = ele.value;
			if (ele.name == 'url') {
				$('#img' + ele.id).src = ele.value.replace(/large/g, 'thumb150')
			}
		}
	})
};
</script>