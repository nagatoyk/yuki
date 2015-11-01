<!DOCTYPE html>
<html>
<head>
	<title>准备中....</title>
</head>
<body>
	<h1>准备中</h1>
	<?php echo time(); ?>
	<form action="?upload" method="post">
		<p><input type="text" name="file[token]"></p>
		<p><input type="text" name="file[filename]" value="<?php echo time(); ?>.jpg"></p>
		<p><textarea name="file[image]"></textarea></p>
		<p><input type="submit" value="提交"></p>
	</form>
</body>
</html>
<?php
if (isset($_GET['upload']) && isset($_POST['file'])) {
	echo '<pre>';
	print_r($_POST);
}
