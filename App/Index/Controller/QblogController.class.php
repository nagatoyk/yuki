<?php
/**
 *
 */
class QblogController extends Controller{
	/**
	 * 初始化
	 */
	public function __construct(){}
	/**
	 * 首页
	 */
	public function index(){
		$this->display();
	}
	private function get(){
		// header('Content-Type:application/json;charset=utf-8');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://moe.fm/listen/playlist?perpage=5");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		// yOU cOULD aLSO uSE tHE mOE123 aPI, bUT mY sERVER iS nOT vERY gOOD...
		// 你也可以用萌导航的API，不过服务质量不咋地，拖垮了要你赔哟！
		// www.moe123.com/api/moefm_music_list/
		// www.moe123.com/api/moefm_music_rand_list/
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'api_key'     => '\u0065\u0064\u0039\u0038\u0034\u0066\u0038\u0062\u0036\u0063\u0063\u0065\u0035\u0066\u0030\u0064\u0032\u0038\u0066\u0038\u0064\u0062\u0037\u0063\u0066\u0030\u0034\u0066\u0038\u0034\u0035\u0065\u0030\u0035\u0034\u0033\u0032\u0064\u0064\u0033\u0061',
			'api'        => 'json'
			));
		$ce = curl_exec($ch);
		curl_close($ch);
		return $ce;
	}
	public function get_playlist(){
		header('Content-Type:application/json;charset=utf-8');
		$data = $this->get();
		$json = json_decode($data, true);
		$this->ajax($json);
	}
}