
<?php
session_start();
require 'r/fun.php';
require 'r/saetv2.ex.class.php';


$o = new SaeTOAuthV2($wb_id, $wb_key);
$code_url = $o->getAuthorizeURL($wb_url);

if(!empty($_POST['submit'])){
	echo '<pre>';
	print_r($_POST);
	$_SESSION['token'] = $_POST['token'];
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
echo '<p>';
foreach(glob('{,.}*', GLOB_BRACE) as $filename){
	// echo '<p>'.$filename.'</p>';
}
echo '</p><p>';
foreach(glob('files/images/*') as $filename){
	echo '<a href="/'.$filename.'" target="_blank"><img src="'.$filename.'" width="300px" height="auto"></a> -- <a href="?delete='.$filename.'">删除</a>';
}
echo '</p><p>';
foreach(glob('files/images/Kancolle/*') as $filename){
	echo '<a href="/'.$filename.'" target="_blank"><img src="'.$filename.'" width="300px" height="auto"></a> -- <a href="?delete='.$filename.'">删除</a>';
}
echo '</p><p>';
if(!empty($_GET['delete'])){
	if(unlink($_GET['delete'])){
		echo 'Del Done';
	}else{
		echo 'Del Error';
	}
}
$c = new SaeTClientV2($wb_id, $wb_key, $_SESSION['token']);
$ms = $c->home_timeline();
if(is_array($ms['statuses'])){
	foreach($ms['statuses'] as $item){
		echo '<div style="padding:10px;margin:5px;border:1px solid #ccc">'.$item['text'].'</div>';
	}
}




?>
</body>
</html>