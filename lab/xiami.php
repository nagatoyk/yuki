<?php
require '../r/fun.php';
require '../r/saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_GET['code'])){
	$code = $_GET['code'];
	$user = array();
	try{
		$user['token'] = $o->getAccessToken('code', array('code' => $code, 'redirect_uri'=>'https://yuki-yukimax.rhcloud.com/lab/xiami.php'));
		session_start();
		$_SESSION['user'] = $user;
		header('Location: /lab/xiami.php?vcode='.$user['token']['access_token']);
		exit();
	}catch(OAuthException $e){
		header('Location: /lab/err.php?e='.json_encode($e));
		exit();
	}
}elseif(isset($_GET['login'])){
	header('Location: '.$o->getAuthorizeURL('https://yuki-yukimax.rhcloud.com/lab/xiami.php'));
	exit();
}
?>
<!doctype html>
<meta charset="utf-8">
<script>
if(window.location.href.indexOf('vcode') >= 0){
	var vcode = window.location.href.split('vcode=')[1];
	if(window.opener){
		window.opener.setToken(vcode);
		window.close()
	}else{
		prompt('请复制下面的验证码并贴入播放页指定文本框以完成验证', vcode);
		window.close()
	}
}
</script>