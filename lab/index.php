<?php
session_start();
header('Content-Type:text/html;charset=utf-8');
require './fun.php';
require '../r/saetv2.ex.class.php';
$o = new SaeTOAuthV2($wb_id, $wb_key);
if(empty($_POST['url'])){
	$url = $o->getAuthorizeURL($wb_url, 'code', urlencode($_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.($_SERVER['HTTP_HOST'] == '127.0.0.1'?'127.0.0.1/yuki':$_SERVER['HTTP_HOST']).'/lab/callback.php'));
	if(!isset($_SESSION['user']) && !isset($_GET['code'])){
		echo '<a href="'.$url.'">登录授权</a>';
	}elseif(!empty($_GET['code'])){
		$user = array();
		try{
			$user['token'] = $o->getAccessToken('code', array('code'=>$_GET['code'], 'redirect_uri'=>$_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['HTTP_HOST'].'/lab/callback.php'));
		}catch(OAuthException $e){
			header('Location: index.php');
			exit();
		}
		if(!$user['token'])exit('error.');
		$c = new SaeTClientV2($wb_id, $wb_key, $user['token']['access_token']);
		$u_msg = $c->show_user_by_id($user['token']['uid']);
		$user['id'] = $u_msg['id'];
		$user['name'] = $u_msg['name'];
		$user['pic'] = $u_msg['profile_image_url'];
		$user_ar = $kv->get('user');
		if(!$user_ar[0])
			$user_ar = array();
		if(!in_arr($user['id'], $user_ar)){
			$user_ar[] = array($user['id'], $u_msg['name'], $u_msg['profile_image_url'], time());
			$kv->set('user', $user_ar);
			$_SESSION['user'] = $user;
		}else{
			$_SESSION['user'] = $user;
		}
		header('Location: index.php');
		exit();
	}else{
		$user = $kv->get('user');
		$my_token = $kv->get('my_token');
		echo getenv('OPENSHIFT_DATA_DIR');
		$c = new SaeTClientV2($wb_id, $wb_key, $my_token[$user[0][0]]['access_token']);
		$rate = $c->rate_limit_status();	$api = $rate['api_rate_limits'];
		foreach($api as $k => $v){
			$limit_time_unit = $v['limit_time_unit'];
			switch ($limit_time_unit) {
				case 'MINUTES':
					$limit_time_unit = '分';
					break;
				case 'HOURS':
					$limit_time_unit = '时';
					break;
				case 'DAYS':
					$limit_time_unit = '日';
					break;
			}
			echo '<p><b>api</b>:&nbsp;'.$v['api'].'&nbsp;<b>limit</b>:&nbsp;'.$v['limit'].'&nbsp;<b>limit_time_unit</b>:&nbsp;'.$limit_time_unit.'&nbsp;<b>remaining_hits</b>:&nbsp;'.$v['remaining_hits'].'</p>';
		}
	}
?>
<script src="//yukimax.sinaapp.com/f/iTorr.js"></script>
<form>
	<input type="text" name="url">
	<input type="button" value="提交">
</form>
<div class="filedata"></div>
<script>
(function($) {
	$('input[type=button]').onclick = function() {
		$.x('index.php', 'url=' + $('input[name=url]').value, function(r) {
			$('.filedta').innerHTML = r
		});
	};
}) (iTorr);
</script>
<?php
}else{
	function get_imgdata($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		// 以数据流的方式返回数据,当为false是直接显示出来
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$imgdata = curl_exec($ch);
		curl_close($ch);
		return $imgdata;
	}
	echo get_imgdata($_POST['url']);
}