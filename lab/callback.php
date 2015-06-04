<?php
require './fun.php';
require './saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(isset($_GET['code'])){
	if(isset($_GET['state'])){
		header('Location:'.urldecode($_GET['state']).'?code='.$_GET['code']);
		exit();
	}else{
		$user = array();
		try{
			$user['token'] = $o->getAccessToken('code', array('code' => $_GET['code'], 'redirect_uri'=>'https://yuki-yukimax.rhcloud.com/lab/callback.php'));
		}catch(OAuthException $e){
			header('Location: index.php');
			exit();
		}
		if(!$user['token']){
			exit('error.');
		}
		$my_token = $kv->get('my_token');
		if(!$my_token[$user['token']['uid']]){
			$my_token = array();
			foreach($user['token'] as $k => $v){
				$my_token[$user['token']['uid']][$k] = $v;
			}
			$token = array(array($user['token']['uid'] => $my_token));
			$kv->set('my_token', $token);
		}
		$c = new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
		$u_msg = $c->show_user_by_id($user['token']['uid']);
		$user['id'] = $u_msg['id'];
		$user['name'] = $u_msg['name'];
		$user['pic'] = $u_msg['profile_image_url'];
		$user_ar = $kv->get('user');
		if(!$user_ar[0]){
			$user_ar = array();
		}
		if(!in_arr($user['id'], $user_ar)){
			$user_ar[] = array($user['id'], $u_msg['name'], $u_msg['profile_image_url'], time());
			$kv->set('user', $user_ar);
			session_start();
			$_SESSION['user'] = $user;
		}else{
			session_start();
			$_SESSION['user'] = $user;
		}
		header('Location: index.php');
		exit();
	}
}else{
	echo $o->getAuthorizeURL($wb_url, 'code', urlencode($_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.($_SERVER['HTTP_HOST'] == '127.0.0.1'?'127.0.0.1/yuki':$_SERVER['HTTP_HOST']).'/lab/index.php'));
}
