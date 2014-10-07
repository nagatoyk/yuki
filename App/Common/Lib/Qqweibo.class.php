<?php
/**
 * QQ微博类
 */
class qqweibo{
	var $appkey = '';
	var $appsecret = '';
	var $openid = '';
	var $callback = '';
	var $base_url = 'https://open.t.qq.com';
	var $api_url = 'https://open.t.qq.com/api/';
	var $authorize_url = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';
	var $access_token_url = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
	var $request_token_url = 'https://open.t.qq.com/cgi-bin/oauth2/request_token';
	public function __construct($appkey, $appsecret, $callback){
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
		$this->callback = $callback;
	}
	public function get_normalized_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.$val;
		}
		return implode('&', $normalized);
	}
	public function get_urlencode_string($params){
		ksort($params);
		$normalized = array();
		foreach($params as $key => $val){
			$normalized[] = $key.'='.rawurlencode($val);
		}
		return implode('&', $normalized);
	}
	public function redirect_to_login($state){
		$redirect = $this->authorize_url.'?';
		$params = array();
		$params['client_id'] = $this->appkey;
		$params['response_type'] = 'code';
		$params['redirect_uri'] = rawurlencode($this->callback);
		$params['state'] = $_SESSION['state'] = $state;
		$normalized_string = $this->get_normalized_string($params);
		$redirect .= $normalized_string;
		header('Location:'.$redirect);
	}
	public function get_access_token($code, $state){
		$url = $this->access_token_url.'?';
		$params = array();
		$params['client_id'] = $this->appkey;
		$params['client_secret'] = $this->appsecret;
		$params['grant_type'] = 'authorization_code';
		$params['code'] = $code;
		$params['redirect_uri'] = rawurlencode($this->callback);
		$params['state'] = $state;
		$normalized_string = $this->get_normalized_string($params);
		$url .= $normalized_string;
		return $this->cURL($url);
	}
	// https://open.t.qq.com/cgi-bin/oauth2/access_token?client_id=APP_KEY&grant_type=refresh_token&refresh_token=REFRESH_TOKEN
	public function get_refresh_token(){}
	public function get_user_info($method, $access_token, $openid, $ip){
		$url = $this->api_url.$method.'?';
		$params = array();
		$params['format'] = 'json';
		$params['oauth_consumer_key'] = $this->appkey;
		$params['access_token'] = $access_token;
		$params['openid'] = $openid;
		$params['clientip'] = $ip;
		$params['oauth_version'] = '2.a';
		$params['scope'] = 'all';
		$normalized_string = $this->get_normalized_string($params);
		$url .= $normalized_string;
		return $this->cURL($url);
	}
	public function add_pic_url($method, $access_token, $openid, $ip){
		$url = $this->api_url.$method;
		$params = $_POST;
		$params['format'] = 'json';
		$params['oauth_consumer_key'] = $this->appkey;
		$params['access_token'] = $access_token;
		$params['openid'] = $openid;
		$params['clientip'] = $ip;
		$params['oauth_version'] = '2.a';
		$params['scope'] = 'all';
		$data = $this->get_normalized_string($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
    private function cURL($arr){
        if(is_array($arr)){
            $url = $arr['url'];
            $timeout = $arr['timeout'];
        }else{
            $url = $arr;
            $timeout = 10;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}