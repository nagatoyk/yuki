<?php
namespace app\org;
class MoefouOAuth{
	const VERSION = '0.1.0';
	private $consumer_key;
	private $consumer_secret;
	private $callback;
	private $oauth_token;
	private $oauth_token_secret;
	private $http_code;
	private $url;
	private $host = 'http://api.moefou.org/';
	private $timeout = 30;
	private $connecttimeout = 30;
	private $ssl_verifypeer = false;
	private $format = 'json';
	private $decode_json = true;
	private $http_info;
	private $useragent = 'MoeFM T OAuth1 v0.1';
	private $debug = false;
	private static $boundary = '';

	public function __construct($consumer_key, $consumer_secret, $callback){
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->callback = $callback;
	}

	private function accessTokenURL(){
		return 'http://api.moefou.org/oauth/access_token';
	}

	private function authorizeURL(){
		return 'http://api.moefou.org/oauth/authorize';
	}

	private function requestTokenURL(){
		return 'http://api.moefou.org/oauth/request_token';
	}

	private function getRequestToken(){
		$params = array();
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $this->consumer_key;
		$params['oauth_signature'] = rawurlencode($this->get_signature('GET&'.rawurlencode($this->requestTokenURL()).'&'.rawurlencode($this->get_normalized_string($params)), $this->consumer_secret.'&'));
		return $this->oAuthRequest($this->requestTokenURL(), 'GET', $params);
	}

	public function getAuthorizeURL($location = false){
		$params = array();
		$params['oauth_consumer_key'] = $this->consumer_key;
		parse_str($this->getRequestToken());
		$_SESSION['moefou']['request_token'] = $oauth_token;
		$_SESSION['moefou']['request_token_secret'] = $oauth_token_secret;
		$params['oauth_token'] = $oauth_token;
		$params['oauth_callback'] = $this->callback;
		$link = $this->authorizeURL().'?'.http_build_query($params);
		if($location){
			header('Location:'.$link);
			exit();
		}else{
			return $link;
		}
	}

	public function getConst(){
		return array(
			'consumer_key'=>$this->consumer_key,
			'consumer_secret'=>$this->consumer_secret,
			'oauth_token'=>$this->oauth_token,
			'oauth_token_secret'=>$this->oauth_token_secret
		);
	}

	public function get_normalized_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.$val;
		}
		return implode('&', $normalized);
	}

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

	public function get_urlencode_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.rawurlencode($val);
		}
		return implode('&', $normalized);
	}
	
	public function is_valid_openid($openid, $timestamp, $sig){
		$key = $this->appsecret;
		$str = $openid.$timestamp;
		$signature = $this->get_signature($str, $key);
		return $sig == $signature;
	}

	public function get($url, $parameters = array()){
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	public function post($url, $parameters = array(), $multi = false){
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi);
		if($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	public function delete($url, $parameters = array()){
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	private function oAuthRequest($url, $method, $parameters, $multi = false){
		if(strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0){
			$url = $this->host.$url.'.'.$this->format;
		}
		switch($method){
			case 'GET':
				$url = $url.'?'.http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if(!$multi && (is_array($parameters) || is_object($parameters))){
					$body = http_build_query($parameters);
				}else{
					$body = self::build_http_query_multi($parameters);
					$headers[] = 'Content-Type: multipart/form-data; boundary='.self::$boundary;
				}
				return $this->http($url, $method, $body, $headers);
		}
	}

	private function http($url, $method, $postfields = NULL, $headers = array()){
		$this->http_info = array();
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ci, CURLOPT_ENCODING, '');
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		if(version_compare(phpversion(), '5.4.0', '<')){
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		}else{
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, false);
		switch($method){
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, true);
				if(!empty($postfields)){
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if(!empty($postfields)){
					$url = $url.'?'.$postfields;
				}
		}
		if(isset($this->access_token) && $this->access_token){
			$headers[] = 'Authorization: OAuth1 '.$this->access_token;
		}
		if(!empty($this->remote_ip)){
			if(defined('SAE_ACCESSKEY')){
				$headers[] = 'SaeRemoteIP: '.$this->remote_ip;
			}else{
				$headers[] = 'API-RemoteIP: '.$this->remote_ip;
			}
		}else{
			if(!defined('SAE_ACCESSKEY')){
				$headers[] = 'API-RemoteIP: '.$_SERVER['REMOTE_ADDR'];
			}
		}
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLINFO_HEADER_OUT, true);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		if($this->debug){
			echo '=====post data======'."\r\n";
			var_dump($postfields);

			echo '=====headers======'."\r\n";
			print_r($headers);

			echo '=====request info====='."\r\n";
			print_r(curl_getinfo($ci));

			echo '=====response====='."\r\n";
			print_r($response);
		}
		curl_close($ci);
		return $response;
	}

	private function getHeader($ch, $header){
		$i = strpos($header, ':');
		if(!empty($i)){
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	public static function build_http_query_multi($params){
		if(!$params)return '';
		uksort($params, 'strcmp');
		$pairs = array();
		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary.'--';
		$multipartbody = '';
		foreach($params as $parameter => $value){
			if(in_array($parameter, array('pic', 'image')) && $value{0} == '@'){
				$url = ltrim($value, '@');
				$content = file_get_contents($url);
				$array = explode( '?', basename($url));
				$filename = $array[0];
				$multipartbody .= $MPboundary."\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="'.$parameter.'"; filename="'.$filename.'"'."\r\n";
				$multipartbody .= 'Content-Type: image/unknown'."\r\n\r\n";
				$multipartbody .= $content."\r\n";
			}else{
				$multipartbody .= $MPboundary."\r\n";
				$multipartbody .= 'content-disposition: form-data; name="'.$parameter."\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}
		}
		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
}