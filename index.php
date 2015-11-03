
<?php
session_start();
require 'r/fun.php';
require 'r/saetv2.ex.class.php';
echo $wb_id.'<br/>'.$wb_key;
if(!empty($_POST['submit'])){
	echo '<pre>';
	print_r($_POST);
	$c = new SaeTClientV2($wb_id, $wb_key, $_POST['token']);
	$c->set_debug(true);
	$res = $c->update($_POST['content'].time());
	print_r($res);
	echo '</pre>';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>准备中....</title>
</head>
<body>
	<h1>准备中</h1>
	<?php echo time(); ?>
	<form action="" method="post">
		<p><input type="text" name="content"></p>
		<input type="hidden" name="token" value="2.00yc_LqBLaBBXB73a1204f1e08OWQX">
		<p><input type="submit" name="submit" value="提交"></p>
	</form>
</body>
</html>