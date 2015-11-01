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
require 'r/saetv2.ex.class.php';
$wb_id = '1093396876';
$wb_key = 'd126f1302a7f1b7e36536f4ad84622a0';
if(isset($_GET['upload']) && isset($_POST['file'])){
	session_start();
	echo '<pre>';
	$c = new SaeTClientV2($wb_id, $wb_key, $_POST['file']['token']);
	$res = $c->upload('我刚刚上传了一张照片'.time(), $_POST['file']['image']);
	print_r($res);
}
