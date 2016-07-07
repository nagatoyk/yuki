<?php
namespace app\org;
class MoeFM extends MoefouOAuth{
	private $host = 'http://moe.fm/';
	public function listen($url_data){
		$const = parent::getConst();
		$params = array();
		$params['oauth_version'] = '1.0';
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_consumer_key'] = $const['consumer_key'];
		$params['oauth_token'] = $const['oauth_token'];
		$params['oauth_signature'] = rawurlencode(parent::get_signature('GET&'.rawurlencode(parent::requestTokenURL()).'&'.rawurlencode(parent::get_normalized_string($params)), $const['consumer_secret'].'&'));
		$url = $this->host.'listen/playlist';
		return parent::oAuthRequest($url, 'GET', $params);
	}
}