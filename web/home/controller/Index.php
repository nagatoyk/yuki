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
		$MoeFM = new \web\org\MoeFMOAuth('18f95c02504fb5a0fdd83b205e7e1aee05421a58b', 'a3af2e9f06faaefb9408897388f0f916', u('login'));
		$MoeFM->getAuthorizeURL(true);
		\View::make();
	}
	public function login() {
		p(q('get.'));
	}
	public function show() {
		\View::make();
	}
}