
<?php
session_start();
require 'r/fun.php';
require 'r/saetv2.ex.class.php';


$o = new SaeTOAuthV2($wb_id, $wb_key);
$code_url = $o->getAuthorizeURL($wb_url);

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
	<p><input type="text" value="<?php echo $_SESSION['token']['access_token']; ?>"></p>
	<form action="" method="post">
		<p><input type="text" name="content"></p>
		<input type="hidden" name="token" value="2.00yc_LqBUTmzLBd034ebe359FIb5mB">
		<p><input type="submit" name="submit" value="提交"></p>
	</form>
	<?php if(!$_SESSION['token']): ?>
	<a href="<?php echo $code_url; ?>">登录</a>
	<?php endif; ?>
<?php
foreach(glob('{,.}*', GLOB_BRACE) as $filename){
	// echo '<p>'.$filename.'</p>';
}
foreach(glob('files/images/*') as $filename){
	echo '<p><a href="/'.$filename.'">'.$filename.'</a> -- <a href="?delete='.$filename.'"></a></p>';
}
foreach(glob('files/images/Kancolle/*') as $filename){
	echo '<p><a href="/'.$filename.'">'.$filename.'</a></p>';
}
if(!empty($_GET['delete'])){
	if(unlink($_GET['delete'])){
		echo 'Del Done';
	}else{
		echo 'Del Error';
	}
}
?>
</body>
</html>