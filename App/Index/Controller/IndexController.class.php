<?php
/**
 * 萌否
 */
class IndexController extends Controller{
	private $_qqweibo = '';
	private $_moefm = '';
	public function __construct(){
		parent::__construct();
		$this->_qqweibo = new qqweibo(C('QQ_T_KEY'), C('QQ_T_SECRET'), U('callback'));
		$this->_moefm = new Moefou(C('MF_KEY'), C('MF_SECRET'), U('Index/Oauth/index'));
	}
	/**
	 * 首页
	 */
	public function index(){
		import('@.Common.Lib.saetv2');
		$o = new SaeTOAuthV2(C('SAET_AKEY'), C('SAET_SKEY'));
		$this->wburl = $o->getAuthorizeURL(U('Index/Oauth/wbcallback'));
		$this->display('indexv2');
	}
	/**
	 * 萌否授权中转
	 */
	public function request_token(){
		$this->_moefm->redirect_to_login();
	}
	/**
	 * 获取token
	 */
	public function access_token(){
		header('Content-Type:application/json;charset=utf-8');
		$oauth_verifier = isset($_GET['oauth_verifier']) ? $_GET['oauth_verifier'] : null;
		$str = $this->_moefm->get_access_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret'], $oauth_verifier);
		$this->ajax(array('str' => $str));
	}
	/**
	 * 检查登录状态
	 */
	public function check_login(){
		header('Content-Type:application/json;charset=utf-8');
		$oauth_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : null;
		$oauth_token_secret = isset($_GET['oauth_token_secret']) ? $_GET['oauth_token_secret'] : null;
		$json = $this->_moefm->get_user_info($oauth_token, $oauth_token_secret);
		unset($json['response']['information']);
		$this->ajax($json);
	}
	/**
	 * 萌否列表
	 */
	public function listen(){
		header('Content-Type:application/json;charset=utf-8');
		$oauth_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : null;
		$oauth_token_secret = isset($_GET['oauth_token_secret']) ? $_GET['oauth_token_secret'] : null;
		unset($_GET['c']);
		unset($_GET['m']);
		unset($_GET['a']);
		unset($_GET['_']);
		unset($_GET['oauth_token']);
		unset($_GET['oauth_token_secret']);
		$data = '';
		if($oauth_token && $oauth_token_secret){
			$data = $this->_moefm->do_get('http://moe.fm/listen/playlist', $oauth_token, $oauth_token_secret);
			$json = json_decode($data, true);
			unset($json['response']['information']['parameters']);
			unset($json['response']['information']['request']);
			$this->ajax($json);
		}else{
			$params = $this->_moefm->get_normalized_string($_GET);
			$data = $this->_moefm->curl('http://moe.fm/listen/playlist?api_key='.C('MF_KEY').'&'.$params);
			$json = json_decode($data, true);
			$this->ajax($json);
		}
	}
	/**
	 * 收藏
	 */
	public function fav(){
		header('Content-Type:application/json;charset=utf-8');
		$oauth_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : null;
		$oauth_token_secret = isset($_GET['oauth_token_secret']) ? $_GET['oauth_token_secret'] : null;
		$method = isset($_GET['method']) ? $_GET['method'] : null;
		unset($_GET['c']);
		unset($_GET['m']);
		unset($_GET['a']);
		unset($_GET['_']);
		unset($_GET['method']);
		unset($_GET['oauth_token']);
		unset($_GET['oauth_token_secret']);
		if ($oauth_token && $oauth_token_secret) {
			$data = $this->_moefm->do_get('http://api.moefou.org/fav/'.$method.'.json', $oauth_token, $oauth_token_secret);
		} else {
			// $params = $this->_moefm->get_normalized_string($_GET);
			// $data = $this->_moefm->curl('http://moe.fm/listen/playlist?api_key='.C('MF_KEY').'&'.$params);
		}
		$json = json_decode($data, true);
		unset($json['response']['information']['parameters']);
		unset($json['response']['information']['request']);
		$this->ajax($json);
	}
	/**
	 * 记录
	 */
	public function my_log(){
		header('Content-Type:application/json;charset=utf-8');
		$oauth_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : null;
		$oauth_token_secret = isset($_GET['oauth_token_secret']) ? $_GET['oauth_token_secret'] : null;
		unset($_GET['c']);
		unset($_GET['m']);
		unset($_GET['a']);
		unset($_GET['_']);
		unset($_GET['method']);
		unset($_GET['oauth_token']);
		unset($_GET['oauth_token_secret']);
		if ($oauth_token && $oauth_token_secret) {
			$data = $this->_moefm->do_get('http://moe.fm/ajax/log', $oauth_token, $oauth_token_secret);
		} else {
			// $params = $this->_moefm->get_normalized_string($_GET);
			// $data = $this->_moefm->curl('http://moe.fm/listen/playlist?api_key='.C('MF_KEY').'&'.$params);
		}
		$json = json_decode($data, true);
		unset($json['response']['information']);
		$this->ajax($json);
	}
	/**
	 * 微博分享
	 */
	public function share(){
		if(Rbac::isLogin()){
			$user = M('user')->find(session('uid'));
			if(!empty($user['qqweibo'])){
				$qqweibo = unserialize($user['qqweibo']);
				$data = $this->_qqweibo->add_pic_url('t/add_pic_url', $qqweibo['access_token'], $qqweibo['openid'], ip::getClientIp());exit();
				$json = json_decode($data, true);
				$this->ajax($qqweibo);
			}
		}else{
			_404(__METHOD__.'页面没找到', '404.html');
		}
	}
}
