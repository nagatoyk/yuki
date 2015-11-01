<!DOCTYPE html>
<html>
<head>
	<title>准备中....</title>
</head>
<body>
	<h1>准备中</h1>
	<?php echo time(); ?>
	<form action="?upload" method="post">
		<p><input type="text" name="filename" value="<?php echo time(); ?>.jpg"></p>
		<p><textarea name="image"></textarea></p>
		<input type="hidden" name="token" value="2.00yc_LqBLaBBXB73a1204f1e08OWQX">
		<p><input type="submit" name="submit" value="提交"></p>
	</form>
</body>
</html>
<?php
require 'r/saetv2.ex.class.php';
// $wb_id = '1093396876';
define('AK', '1093396876');
define('SK', 'd126f1302a7f1b7e36536f4ad84622a0');
// $wb_key = 'd126f1302a7f1b7e36536f4ad84622a0';
if(isset($_GET['upload']) && isset($_POST['submit'])){
	session_start();
	echo '<pre>';
	print_r($_POST);
	$c = new SaeTClientV2(AK, SK, $_POST['token']);
	$res = $c->upload('我刚刚上传了一张照片'.time(), $_POST['image']);
	print_r($res);
}
