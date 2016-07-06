<?php
/**
 * 萌否电台
 */
class MoeFM{
	public $appkey;
	public $appsecret;
	public $callback;
	public $base_url = 'http://api.moefou.org';
	public $authorize_url = 'http://api.moefou.org/oauth/authorize';
	public $access_token_url = 'http://api.moefou.org/oauth/access_token';
	public $request_token_url = 'http://api.moefou.org/oauth/request_token';
	/**
	 * 构造函数
	 * @access  public
	 * @param   string
	 * @return  void
	 */
	public function __construct($appkey, $appsecret, $callback){
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
		$this->callback = $callback;
	}
	/**
	 * @brief 跳转到萌否授权页面.请求需经过URL编码，编码时请遵循 RFC 1738
	 *
	 * @param $appkey
	 * @param $appsecret
	 * @param $callback
	 *
	 * @return 返回字符串格式为：oauth_token=xxx&oauth_verifier=xxx
	 */
	public function redirect_to_login(){
		// 跳转到萌否授权页面地址, 不要更改!!
		$redirect = $this->authorize_url.'?oauth_consumer_key='.$this->appkey.'&';
		// 调用get_request_token接口获取未授权的临时token
		$result = array();
		$request_token = $this->get_request_token();
		parse_str($request_token, $result);
		// request token, request token secret 需要保存起来
		$_SESSION['moefou'] = array(
			'token' => $result['oauth_token'],
			'token_secret' => $result['oauth_token_secret']
			);
		if($result['oauth_token'] == ''){
			// 示例代码中没有对错误情况进行处理。真实情况下网站需要自己处理错误情况
			exit();
		}
		// 构造请求URL
		$redirect .= 'oauth_token='.$result['oauth_token'].'&oauth_callback='.rawurlencode($this->callback);
		header('Location:'.$redirect);
	}
	/**
	 * @brief 请求临时token.请求需经过URL编码，编码时请遵循 RFC 1738
	 * @param $appkey
	 * @param $appsecret
	 * @return 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx
	 */
	public function get_request_token(){
		//请求临时token的接口地址, 不要更改!!
		$url = $this->request_token_url.'?';
		$sigstr = 'GET&'.rawurlencode($this->request_token_url).'&';
		//必要参数
		$params = array();
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $this->appkey;
		//对参数按照字母升序做序列化
		$normalized_str = $this->get_normalized_string($params);
		$sigstr .= rawurlencode($normalized_str);
		//（2）构造密钥
		$key = $this->appsecret.'&';
		//（3）生成oauth_signature签名值。这里需要确保PHP版本支持hash_hmac函数
		$signature = $this->get_signature($sigstr, $key);
		//构造请求url
		$url  .= $normalized_str.'&oauth_signature='.rawurlencode($signature);
		return $this->curl($url);
	}
	/**
	 * @brief 获取用户信息.请求需经过URL编码，编码时请遵循 RFC 1738
	 * @param $appkey
	 * @param $appsecret
	 * @param $access_token
	 * @param $access_token_secret
	 */
	public function get_user_info($access_token, $access_token_secret){
		//获取用户信息的接口地址, 不要更改!!
		$url = 'http://api.moefou.org/user/detail.json';
		$info = $this->do_get($url, $access_token, $access_token_secret);
		return json_decode($info, true);
	}
	// 添加收藏或删除收藏
	public function add_like_fav($access_token, $access_token_secret, $fav_obj_id){
		$_GET['fav_type'] = 1;
		$_GET['fav_obj_type'] = 'song';
		$_GET['fav_obj_id'] = $fav_obj_id;
		$url = 'http://api.moefou.org/fav/add.json';
		$result = $this->do_get($url, $access_token, $access_token_secret);
		return json_decode($result, true);
	}
	// 收听接口
	public function get_listen($access_token, $access_token_secret){
		$_GET['api'] = 'json';
		if(isset($_GET['id'])){
			$_GET['song'] = $_GET['id'];
			unset($_GET['id']);
		}else{
			$_GET['fav'] = 'song';
			$_GET['perpage'] = 3;
		}
		$url = 'http://moe.fm/listen/playlist';
		unset($_GET['a']);
		unset($_GET['rid']);
		unset($_GET['_r']);
		if(!$access_token && !$access_token_secret){
			$_GET['api_key'] = $this->appkey;
			$url .= '?'.$this->get_urlencode_string($_GET);
			$playlist = $this->curl($url);
		}else{
			$playlist = $this->do_get($url, $access_token, $access_token_secret);
		}
		return json_decode($playlist, true);
	}
	// 听歌记录
	public function set_log($access_token, $access_token_secret, $obj_id){
		$url = 'http://moe.fm/ajax/log';
		$_GET['log_obj_type'] = 'sub';
		$_GET['log_type'] = 'listen';
		$_GET['obj_type'] = 'song';
		$_GET['api'] = 'json';
		$_GET['obj_id'] = $obj_id;
		$log = $this->do_get($url, $access_token, $access_token_secret);
		return json_decode($log, true);
	}
	/**
	 * @brief 获取access_token。请求需经过URL编码，编码时请遵循 RFC 1738
	 *
	 * @param $appkey
	 * @param $appsecret
	 * @param $request_token
	 * @param $request_token_secret
	 * @param $vericode
	 *
	 * @return 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx&openid=xxx&oauth_signature=xxx&oauth_vericode=xxx&timestamp=xxx
	 */
	public function get_access_token($request_token, $request_token_secret, $vericode){
		// 获取萌否token
		$url = $this->access_token_url.'?';
		//生成oauth_signature签名值。
		//（1） 构造生成签名值的源串（HTTP请求方式 & urlencode(uri) & urlencode(a=x&b=y&…)）
		$sigstr = 'GET&'.rawurlencode($this->access_token_url).'&';
		// 必要参数，不要随便更改!!
		$params = array();
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $this->appkey;
		$params['oauth_token'] = $request_token;
		// 对参数按照字母升序做序列化
		$normalized_str = $this->get_normalized_string($params);
		$sigstr .= rawurlencode($normalized_str);
		//（2）构造密钥
		$key = $this->appsecret.'&'.$request_token_secret;
		//（3）生成oauth_signature签名值。这里需要确保PHP版本支持hash_hmac函数
		$signature = $this->get_signature($sigstr, $key);
		// 构造请求url
		$url .= $normalized_str.'&oauth_signature='.rawurlencode($signature);
		return $this->curl($url);
	}
	/**
	 * @brief 对参数进行字典升序排序
	 * @param $params 参数列表
	 * @return 排序后用&链接的key-value对（key1=value1&key2=value2…)
	 */
	public function get_normalized_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.$val;
		}
		return implode('&', $normalized);
	}
	/**
	 * @brief 使用HMAC-SHA1算法生成oauth_signature签名值
	 * @param $key  密钥
	 * @param $str  源串
	 * @return 签名值
	 */
	public function get_signature($str, $key){
		$signature = '';
		if(function_exists('hash_hmac')){
			$signature = base64_encode(hash_hmac('sha1', $str, $key, true));
		}else{
			$blocksize = 64;
			$hashfunc = 'sha1';
			if(strlen($key) > $blocksize){
				$key = pack('H*', $hashfunc($key));
			}
			$key = str_pad($key, $blocksize, chr(0x00));
			$ipad = str_repeat(chr(0x36), $blocksize);
			$opad = str_repeat(chr(0x5c), $blocksize);
			$hmac = pack('H*', $hashfunc(($key^$opad).pack('H*', $hashfunc(($key^$ipad).$str))));
			$signature = base64_encode($hmac);
		}
		return $signature;
	}
	/**
	 * @brief 对字符串进行URL编码，遵循rfc1738 urlencode
	 * @param $params
	 * @return URL编码后的字符串
	 */
	public function get_urlencode_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.rawurlencode($val);
		}
		return implode('&', $normalized);
	}
	/**
	 * @brief 检查openid是否合法
	 * @param $openid  与用户QQ号码一一对应
	 * @param $timestamp　时间戳
	 * @param $sig　　签名值
	 * @return true or false
	 */
	public function is_valid_openid($openid, $timestamp, $sig){
		$key = $this->appsecret;
		$str = $openid.$timestamp;
		$signature = $this->get_signature($str, $key);
		return $sig == $signature;
	}
	/**
	 * @brief 所有Get请求都可以使用这个方法
	 * @param $url
	 * @param $appkey
	 * @param $appsecret
	 * @param $access_token
	 * @param $access_token_secret
	 * @return true or false
	 */
	public function do_get($url, $access_token, $access_token_secret){
		$sigstr = 'GET&'.rawurlencode($url).'&';
		// 必要参数, 不要随便更改!!
		$params = $_GET;
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $this->appkey;
		$params['oauth_token'] = $access_token;
		unset($params['oauth_signature']);
		// 参数按照字母升序做序列化
		$normalized_str = $this->get_normalized_string($params);
		$sigstr .= rawurlencode($normalized_str);
		// 签名,确保php版本支持hash_hmac函数
		$key = $this->appsecret.'&'.$access_token_secret;
		$signature = $this->get_signature($sigstr, $key);
		$url .= '?'.$normalized_str.'&oauth_signature='.rawurlencode($signature);
		echo $url;
		return $this->curl($url);
	}
	/**
	 * @brief 所有multi-part post 请求都可以使用这个方法
	 * @param $url
	 * @param $appkey
	 * @param $appsecret
	 * @param $access_token
	 * @param $access_token_secret
	 *
	 */
	public function do_multi_post($url, $access_token, $access_token_secret){
		// 构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
		$sigstr = 'POST&'.$url.'&';
		// 必要参数,不要随便更改!!
		$params = array();
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $appkey;
		$params['oauth_token'] = $access_token;
		unset($params['oauth_signature']);
		// 获取上传图片信息
		foreach($_FILES as $filename => $filevalue){
			if($filevalue['error'] != UPLOAD_ERR_OK){
				//echo 'upload file error $filevalue['error']\n';
				//exit;
			}
			$params[$filename] = $this->curl($filevalue['tmp_name']);
		}
		// 对参数按照字母升序做序列化
		$sigstr .= $this->get_normalized_string($params);
		// 签名,需要确保php版本支持hash_hmac函数
		$key = $appsecret.'&'.$access_token_secret;
		$signature = $this->get_signature($sigstr, $key);
		$params['oauth_signature'] = $signature;
		// 处理上传图片
		foreach($_FILES as $filename => $filevalue){
			$tmpfile = dirname($filevalue['tmp_name']).'/'.$filevalue['name'];
			move_uploaded_file($filevalue['tmp_name'], $tmpfile);
			$params[$filename] = '@'.$tmpfile;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);
		// $httpinfo = curl_getinfo($ch);
		// print_r($httpinfo);
		curl_close($ch);
		//删除上传临时文件
		unlink($tmpfile);
		return $ret;
	}
	/**
	 * @brief 所有post 请求都可以使用这个方法
	 * @param $url
	 * @param $appkey
	 * @param $appsecret
	 * @param $access_token
	 * @param $access_token_secret
	 */
	public function do_post($url, $access_token, $access_token_secret){
		// 构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
		$sigstr = 'POST&'.rawurlencode($url).'&';
		// 必要参数,不要随便更改!!
		$params = $_POST;
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $this->appkey;
		$params['oauth_token'] = $access_token;
		unset($params['oauth_signature']);
		// 对参数按照字母升序做序列化
		$sigstr .= rawurlencode($this->get_normalized_string($params));
		// 签名,需要确保php版本支持hash_hmac函数
		$key = $this->appsecret.'&'.$access_token_secret;
		$signature = $this->get_signature($sigstr, $key);
		$params['oauth_signature'] = $signature;
		$postdata = $this->get_urlencode_string($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	/**
	 * 打乱加码
	 */
	public function token_hash($str){
		// $str = urlencode($str);
		$r = rand(4, 9);
		$l = strlen($str);
		$c = ceil($l / $r);
		$n = '';
		for($i = 0; $i < $c; $i++){
			$n .= substr($str, $i * $r, $r).END;
		}
		$nstr = substr($n, 0, -strlen(END));
		$o = explode(END, $nstr);
		$t = $u = 0;
		$s = '';
		while ($u < strlen($o[0])) {
			$t = 0;
			while ($t < count($o)) {
				$s .= isset($o[$t][$u]) ? $o[$t][$u] : null;
				$t++;
			}
			$u++;
		}
		return $r.$s;
	}
	/**
	 * 抓取方式
	 */
	public function curl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}