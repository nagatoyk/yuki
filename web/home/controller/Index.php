<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\home\controller;
class Index {
	public function index() {
		echo u('fm.index.index');
		echo '<hr>';
		$config = array(
			'host'=>'api.moefou.org',
			'consumer_key'=>'18f95c02504fb5a0fdd83b205e7e1aee05421a58b',
			'consumer_secret'=>'a3af2e9f06faaefb9408897388f0f916'
		);
		$MoeFM = new \web\org\MoeFMOAuth1('18f95c02504fb5a0fdd83b205e7e1aee05421a58b', 'a3af2e9f06faaefb9408897388f0f916', 'http://kloli.tk/fm/login.php');
		$r = $MoeFM->getAuthorizeURL();
		p($r);
		\View::make();
	}
	public function show() {
		\View::make();
	}
}