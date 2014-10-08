var audio_pretest = $.D.m('audio');
if (audio_pretest.canPlayType('audio/mpeg') == '') {
	alert('很抱歉，看起来您的浏览器不支持 MPEG (MP3) 文件……\n支持 MPEG 编码的浏览器请参考 http://caniuse.com/#feat=mpeg4');
	window.location.href = 'http://moe.fm/listen'+window.location.search;
	throw 'It seems that the browser doesn\'t support MPEG media...';
};
var setting = JSON.parse(localStorage.getItem('moefm-html5-setting')) || {},
audio = $('.audio'),
cover = $('.cover'),
cover_preload = $('.cover_preload'),
cover_loading_notification = $('.cover_loading_notification'),
title = $('.title'),
artist = $('.artist'),
album = $('.album'),
c_play = $('.c_play'),
c_pause = $('.c_pause'),
c_previous = $('.c_previous'),
c_next = $('.c_next'),
c_like = $('.c_like'),
c_dislike = $('.c_dislike'),
c_volume = $('.c_volume'),
c_volume_icon = $('.c_volume_icon'),
c_volume_range = $('.c_volume_range'),
c_share = $('.c_share'),
timeline = $('.timeline'),
timeline_duration = $('.timeline_duration'),
timeline_current = $('.timeline_current'),
timeline_duration_time = $('.timeline_duration_time'),
timeline_current_time = $('.timeline_current_time'),
link_right_user = $('.link_right_user'),
aside_album = $('.aside_album'),
aside_song = $('.aside_song'),
aside_radio = $('.aside_radio'),
aside_random = $('.aside_random'),
background_preload = $('.background_preload'),
link_setting_background = $('.link_setting_background'),
link_about = $('.link_about'),
playlist = [],
playlist_fetching = 0,
count = -1,
volume = setting.volume || 80,
next = 0,
url_data = undefined,
cover_retry = 0,
login_retry = 0,
p = 0,
background_list = setting.background || [],
background_count_time = undefined,
background_count_time_value = 0,
loop = setting.loop,
is_login = false,
ck = '\u0031\u0038\u0066\u0039\u0035\u0063\u0030\u0032\u0035\u0030\u0034\u0066\u0062\u0035\u0061\u0030\u0066\u0064\u0064\u0038\u0033\u0062\u0032\u0030\u0035\u0065\u0037\u0065\u0031\u0061\u0065\u0065\u0030\u0035\u0034\u0032\u0031\u0061\u0035\u0038\u0062',
cs = '\u0061\u0033\u0061\u0066\u0032\u0065\u0039\u0066\u0030\u0036\u0066\u0061\u0061\u0065\u0066\u0062\u0039\u0034\u0030\u0038\u0038\u0039\u0037\u0033\u0038\u0038\u0066\u0030\u0066\u0039\u0031\u0036',
accessToken,
accessTokenSecret,
options;
generateOauthUrl = function (url, options) {
	var accessor = {
		consumerKey: options.consumerKey,
		consumerSecret: options.consumerSecret
	};
	if ('token' in options) {
		accessor['token'] = options.token;
		accessor['tokenSecret'] = options.tokenSecret;
	};
	var message = {
		action: url,
		method: options.method,
		parameters: {}
	};
	if ('verifier' in options) {
		message.parameters['oauth_verifier'] = options.verifier
	};
	if('callback' in options){
		message.parameters['oauth_callback'] = options.callback
	};
	OAuth.completeRequest(message, accessor);
	OAuth.SignatureMethod.sign(message, accessor);
	return url + (url.indexOf('?') >= 0 ? '&' : '?') + OAuth.formEncode(message.parameters)
},
audio_play = function(c) {
	if (c == null) {
		c = 1;
	};
	if (count < playlist.length - 1) {
		count += c;
		audio.src = playlist[count].url;
		update_info();
		if (count > playlist.length-5) {
			update_playlist(null, false)
		}
		/*if (_check_music == true) {
			check_file_exist(playlist[count+1].url)
		} else if (_preload_music == true) {
			preload_music(playlist[count+1].url)
		}*/
	} else if(loop) {
		count = 0;
		audio.src = playlist[count].url;
		update_info()
	} else {
		update_playlist(null, false)
	}
},
update_info = function() {
	cover_retry = 0;
	if(cover_preload.src != playlist[count].cover.large) {
		$.css(cover_loading_notification, 'opacity: 1')
	};
	cover_preload.src = playlist[count].cover.large;
	if (c_like.hasAttribute('meow')) {
		c_like.removeAttribute('meow')
	};
	if (c_dislike.hasAttribute('meow')) {
		c_dislike.removeAttribute('meow')
	};
	if (location.search.indexOf('music=') >= 0 ? location.search.split('music=')[1].split('&')[0].indexOf(playlist[count].wiki_id) < 0 : (location.search.indexOf('song=') >= 0 ? location.search.split('song=')[1].split('&')[0].indexOf(playlist[count].sub_id) < 0 : location.search.indexOf('radio=') < 0)) {
		window.history.replaceState(null, '', '?song=' + playlist[count].sub_id)
	};
	if (playlist[count].sub_title) {
			title.setAttribute('title', playlist[count].sub_title);
		title.innerHTML = playlist[count].sub_title;
		document.title = playlist[count].sub_title + ' | 萌否电台'
	} else {
		title.innerHTML = '';
		document.title = '收听音乐 | 萌否电台'
	};
	if (playlist[count].artist) {
		artist.innerHTML = playlist[count].artist;
		artist.setAttribute('title', playlist[count].artist)
	} else {
		artist.innerHTML = ''
	};
	if (playlist[count].wiki_title) {
		album.innerHTML = playlist[count].wiki_title;
		album.setAttribute('title', playlist[count].wiki_title)
	} else {
		album.innerHTML = ''
	};
	if (playlist[count].fav_sub != null) {
		if (playlist[count].fav_sub.fav_type == 1) {
			c_like.setAttribute('meow', '1');
			c_dislike.setAttribute('hidden', 'hidden')
		}
		if (playlist[count].fav_sub.fav_type == 2) {
			c_dislike.setAttribute('meow', '1');
			c_like.setAttribute('hidden', 'hidden')
		}
	} else {
		c_like.removeAttribute('hidden');
		c_dislike.removeAttribute('hidden')
	};
	if (playlist[count].fav_wiki) {
		if(playlist[count].fav_wiki.fav_type == 1) album.innerHTML = '(♥) ' + playlist[count].wiki_title || '&nbsp;'
	}
},
update_error = function(t, c) {
	var div = $.D.m('div'),
	context;
	div.className = 'error_notification';
	switch (t) {
		case 'audio':
			context = '播放音频时发生错误<br>' + c;
			break;
		case 'log':
			context = '记录播放历史失败<br>' + c;
			break;
		case 'fav':
			context = '添加收藏/抛弃记录失败<br>' + c;
			break;
		case 'playlist':
			context = '获取播放列表失败<br>' + c;
			break;
		case 'cover':
			context = '获取专辑图片失败<br>' + c;
			break;
		case 'background':
			context = '获取背景图片失败<br>' + c;
			break;
		case 'login':
			context = '获取登录数据失败<br>' + c;
			break;
		default:
			context = 'Seems that something lovely goes wrong...<br>' + c
	};
	div.innerHTML = context;
	$.D.a(div);
	$.css(div, 'opacity: 1;bottom: 30px');
	setTimeout(function(){
		$.css(div, 'opacity: 0;bottom: 0px');
		setTimeout(function(){
			$.D.d(div)
		}, 1000)
	}, 5000)
},
update_log = function() {
	if (is_login == true) {
		var xhr = new XMLHttpRequest(),
		options = {
			method: 'get',
			consumerKey: ck,
			consumerSecret: cs,
			token: accessToken,
			tokenSecret: accessTokenSecret
		},
		url = 'http://moe.fm/ajax/log?api=json&log_obj_type=sub&log_type=listen&obj_type=song&obj_id=' + playlist[count].sub_id + '&_=' + new Date().getTime();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					if (JSON.parse(xhr.responseText).status == false) {
						update_error('log', JSON.parse(xhr.responseText).msg)
					}
				} else if(xhr.responseText) {
					update_error('log', JSON.parse(xhr.responseText).response.error.message)
				} else{
					update_error('log','XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
				}
			}
		};
		xhr.open('GET', generateOauthUrl(url, options));
		xhr.send()
	}
},
update_fav = function(t, d) {
	var xhr = new XMLHttpRequest(),
	url = 'http://api.moefou.org/fav/' + (d == 0 ? 'add' : 'delete') + '.json?fav_type=' + t + '&fav_obj_type=song&fav_obj_id=' + playlist[count].sub_id + '&_=' + new Date().getTime(),
	options = {
		method: 'get',
		consumerKey: ck,
		consumerSecret: cs,
		token: accessToken,
		tokenSecret: accessTokenSecret,
		// fav_type: d,
		// fav_obj_type: 'song',
		// fav_obj_id: playlist[count].sub_id
	};
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				if (JSON.parse(xhr.responseText).status == false) {
					update_error('fav', JSON.parse(xhr.responseText).msg)
				} else {
					switch (t) {
						case 1:
							switch (d) {
								case 1:
									c_like.removeAttribute('meow');
									c_dislike.removeAttribute('hidden', 'hidden');
									playlist[count].fav_sub = null;
									break;
								case 0:
									c_like.setAttribute('meow','1');
									c_dislike.setAttribute('hidden', 'hidden');
									playlist[count].fav_sub = {};
									playlist[count].fav_sub.fav_type = 1;
									break;
							}
							break;
						case 2:
							switch (d) {
								case 1:
									c_like.removeAttribute('hidden', 'hidden');
									c_dislike.removeAttribute('meow');
									playlist[count].fav_sub = null;
									break;
								case 0:
									c_like.setAttribute('hidden', 'hidden');
									c_dislike.setAttribute('meow','1');
									playlist[count].fav_sub = {};
									playlist[count].fav_sub.fav_type = 2;
									break;
							}
							break;
					}
				}
			} else if(xhr.responseText) {
				update_error('fav', JSON.parse(xhr.responseText).response.error.message)
			} else {
				update_error('fav', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
			}
		}
	};
	xhr.open('GET', generateOauthUrl(url, options));
	xhr.send()
},
update_playlist = function(d, k, m) {
	if (playlist_fetching == 0) {
		var is_update = 1;
		if (d != null) {
			url_data = d;
			is_update = 0;
			count = -1;
			if (/\d+/.test(d)) {
				p = 1
			}
		};
		/*if (m != null && m == true) {
			is_update = 0
		};*/
		playlist_fetching = 1;
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					if (JSON.parse(xhr.responseText).playlist) {
						playlist_fetching = 0;
						// count = -1;
						if (k == false && is_update == 1) {
							// playlist = /*playlist.concat(*/JSON.parse(xhr.responseText).playlist/*)*/;
							for (var i = 0, j = JSON.parse(xhr.responseText).playlist; i < j.length; i++) {
								playlist.push(j[i])
							};
							if (JSON.parse(xhr.responseText).info.may_have_next == true) {
								p++
							} else {
								p = 0;
								url_data = null
							}
							// count= -1;
						} else {
							playlist = JSON.parse(xhr.responseText).playlist;
							if (JSON.parse(xhr.responseText).info.may_have_next == true) {
								p++
							} else {
								p = 0;
								url_data = null
							}
							// count = -1;
						};
						if (k != false) {
							audio_play()
						}
						/*playlist = JSON.parse(xhr.responseText).playlist;
						playlist_fetching = 0;
						count = -1;
						if (p != 0) {
							if (JSON.parse(xhr.responseText).info.may_have_next == true) {
								p++
							} else {
								p = 0;
								url_data = null
							}
						};
						audio_play()*/
					} else if (JSON.parse(xhr.responseText).response.playlist) {
						playlist_fetching = 0;
						// count = -1;
						/*if (p != 0) {
							playlist = JSON.parse(xhr.responseText).response.playlist;
							count = -1;
							if (JSON.parse(xhr.responseText).response.information.may_have_next == true) {
								p++
							} else {
								p = 0;
								url_data = null
							}
						} else {
							playlist = JSON.parse(xhr.responseText).response.playlist;
							count = -1
						};
						audio_play()*/
						if (k == false && is_update == 1) {
							// playlist = /*playlist.concat(*/JSON.parse(xhr.responseText).playlist/*)*/;
							for (var i = 0, j = JSON.parse(xhr.responseText).response.playlist; i < j.length; i++){
								playlist.push(j[i])
							};
							if (JSON.parse(xhr.responseText).response.information.may_have_next == true){
								p++
							} else {
								p = 0;
								url_data = null
							}
							// count = -1
						} else {
							playlist = JSON.parse(xhr.responseText).response.playlist;
							if (JSON.parse(xhr.responseText).response.information.may_have_next == true) {
								p++
							} else {
								p = 0;
								url_data = null
							}
							// count = -1
						}
						if (k != false) {
							audio_play()
						}
					} else if (JSON.parse(xhr.responseText).response.error) {
						update_error('playlist', JSON.parse(xhr.responseText).response.error.message)
					}
				} else if(xhr.status == 401) {
					check_login();
					is_login = false;
					playlist_fetching = 0;
					update_playlist(d)
				} else if(xhr.responseText) {
					playlist_fetching = 0;
					update_error('playlist', JSON.parse(xhr.responseText).response.error.message)
				} else {
					playlist_fetching = 0;
					update_error('playlist', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
				}
			}
		};
		if (is_login == true) {
			var options = {
				method:'get',
				consumerKey: ck,
				consumerSecret: cs,
				token: accessToken,
				tokenSecret: accessTokenSecret
			};
			if (url_data == null) {
				var url = 'http://moe.fm/listen/playlist?api=json&share_buttons=1&perpage=30&_=' + new Date().getTime()
			} else {
				var url='http://moe.fm/listen/playlist?api=json&share_buttons=1&perpage=30&page=' + p + '&' + url_data + '&_=' + new Date().getTime()
			};
			console.log(url);
			console.log(generateOauthUrl(url,options));
			xhr.open('GET', generateOauthUrl(url, options))
		} else {
			if (url_data == null) {
				xhr.open('GET', 'http://moe.fm/listen/playlist?share_buttons=1&perpage=30&_=' + new Date().getTime())
			} else {
				xhr.open('GET', 'http://moe.fm/listen/playlist?share_buttons=1&perpage=30&page=' + p + '&' + url_data + '&_=' + new Date().getTime())
			}
		};
		xhr.send()
	}
},
update_volume_icon = function(v) {
	if (v > 66) {
		c_volume_icon.innerHTML = '大'
	} else if (v > 33){
		c_volume_icon.innerHTML = '中'
	} else {
		c_volume_icon.innerHTML = '小'
	}
},
update_background = function() {
	if (background_list.length == 0) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					background_list = JSON.parse(xhr.responseText).background_list;
					update_background()
				} else {
					update_error('background', '获取背景图片列表失败<br>XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
				}
			}
		};
		xhr.open('GET', 'http://moefm.ccloli.com/background/');
		xhr.send()
	} else {
		var num = parseInt(Math.random() * (background_list.length - 1));
		background_preload.src = background_list[num]
	}
},
update_background_count = function(v) {
	if (v == 1) {
		background_count_time = setInterval(function() {
			if (background_count_time_value >= 60) {
				update_background();
				background_count_time_value = 0
			} else {
				background_count_time_value++
			}
		},1000)
	} else {
		clearInterval(background_count_time)
	}
},
share = function() {
	var div = $.D.m('div'),
	div2 = $.D.m('div');
	div.className = 'share_panel';
	div2.className = 'share_panel_background';
	div2.title = '点击黑色区域以退出';
	div.innerHTML = '<button onclick="var p = prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\',\'' + playlist[count].sub_url + '#' + playlist[count].sub_title + ' | 萌否电台\');if (p != null) {window.open(\'' + playlist[count].sub_url + '\', \'information\');return false}">复制当前曲目地址</button><button onclick="var p = prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\', \'' + playlist[count].wiki_url + '#'+playlist[count].wiki_title + ' | 萌否电台\');if (p!=null) {window.open(\'' + playlist[count].wiki_url + '\',\'information\');return false)">复制当前专辑地址</button><span class="share_buttons">' + playlist[count].share_buttons + '</span>';
	$.D.a(div);
	$.D.a(div2);
	div2.addEventListener('click',function(){
		setTimeout(function() {
			$.D.d(div);
			setTimeout(function() {
				$.D.d(div2)
			}, 200)
		}, 200)
	})
},
set_login = function() {
	link_right_user.innerHTML = '<a class="right" target="_blank" href="http://moefou.org/register?redirect=http%3A%2F%2Fmoefm.ccloli.com">注册</a><a class="right">登入</a>';
	$('aside').setAttribute('hidden', 'hidden');
	link_right_user.addEventListener('click', login, false)
},
check_login = function() {
	accessToken = localStorage.getItem('accessToken');
	accessTokenSecret = localStorage.getItem('accessTokenSecret');
	link_right_user.innerHTML = '正在获取用户信息......';
	var xhr = new XMLHttpRequest(),
	url = 'http://api.moefou.org/user/detail.json',
	options = {
		method: 'get',
		consumerKey: ck,
		consumerSecret: cs,
		token: accessToken,
		tokenSecret: accessTokenSecret
	};
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				is_login = true;
				var data = JSON.parse(xhr.responseText).response.user;
				link_right_user.innerHTML = '';
				var user_btn = $.D.m('span');
				user_btn.innerHTML = data.user_nickname;
				user_btn.className = 'link_right_user_btn';
				// link_right_user.appendChild(user_btn);
				$.D.a(link_right_user, user_btn);
				var user_pan = $.D.m('div');
				user_pan.innerHTML = '<div style="padding:6px"><div style="float:left"><img class="avatar" style="width:48px;height:48px" src="' + data.user_avatar.small + '" alt=""></div><div style="padding-left:6px;margin-left:48px;width:108px"><a title="个人主页" href="' + data.user_fm_url + '">我的主页</a><a target="_blank" class="external" href="http://moefou.org/user/setting">个人设定</a><a onclick="logout()">登出</a><div style="clear:both"></div></div><div style="clear:both"></div></div>';
				$.css(user_pan, 'position:fixed;background:rgba(0,0,0,0.5);bottom:0px;right:10px;opacity:0');
				user_pan.setAttribute('hidden', 'hidden');
				$.D.a(user_btn, user_pan);
				user_btn.addEventListener('mouseover', function() {
					user_pan.removeAttribute('hidden');
					$.css(user_pan, 'opacity: 1;bottom: 20px')
				}, false);
				user_btn.addEventListener('mouseout', function() {
					user_pan.setAttribute('hidden', 'hidden');
					$.css(user_pan, 'opacity: 0;bottom: 0px')
				}, false);
				if ($('aside').hasAttribute('hidden')) {
					$('aside').removeAttribute('hidden');
				}
				if (playlist.length == 0) {
					start()
				}
				/*options = {
					method: 'get',
					consumerKey: ck,
					consumerSecret: cs,
					token: accessToken,
					tokenSecret: accessTokenSecret
				};
				if (playlist.length != 0) {
					for (var i = 0; i < playlist.length; i++) {
					}
					update_playlist(null, false, true)
				}*/
			} else if (xhr.status == 401) {
				update_error('login', '用户信息验证失败，可能是因为您曾经取消授权，请尝试重新授权登录。');
				set_login();
				start()
			} else {
				login_retry++;
				if (login_retry < 3) {
					update_error('login', '无法获取用户信息，可能是网络问题或服务器故障，正在尝试重新连接......<br>XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText);
					check_login()
				} else {
					update_error('login', '无法获取用户信息，可能是网络问题或服务器故障，请稍候刷新重试......<br>XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText);
					set_login()
				}
			}
		}
	};
	xhr.open('GET', generateOauthUrl(url, options));
	xhr.send()
},
login = function() {
	var div = $.D.m('div'),
	div2 = $.D.m('div');
	div.className = 'login_panel';
	div2.className = 'login_panel_background';
	div2.title = '点击黑色区域以退出';
	div.innerHTML = '您即将使用您的萌否账号授权登录本站点，授权过程均在萌否服务器完成，本站点不会记录您的密码，但您授权后的 access token 数据将以可能不安全的方式储存在浏览器中。<br>如果您之前已授权却仍被要求重新授权，那么可能是您目前在另一台计算机上，或您曾经清除过浏览器数据集而误清理了 access token。<br><button class="login_confirm">我已了解，开始授权</button>';
	$.D.a(div);
	$.D.a(div2);
	div2.addEventListener('click', function() {
		setTimeout(function() {
			$.D.d(div);
			setTimeout(function() {
				$.D.d(div2)
			}, 200)
		}, 200)
	});
	$('.login_confirm').addEventListener('click', function() {
		div.innerHTML = '正在拉取授权数据，请耐心等待......';
		var url = 'http://api.moefou.org/oauth/request_token',
		options = {
			method: 'get',
			consumerKey: ck,
			consumerSecret: cs,
			// callback: 'http://moefm.ccloli.com/oauth_callback.html'
			callback: 'http://yukimax.cn/lab/mf/callback.php'
		},
		xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					var data = xhr.responseText,
					rt = data.replace(/.*\boauth_token=([a-z0-9]+).*/, '$1'),
					rs = data.replace(/.*\boauth_token_secret=([a-z0-9]+).*/, '$1'),
					url = 'http://api.moefou.org/oauth/authorize',
					options = {
						method: 'get',
						consumerKey: ck,
						consumerSecret: cs,
						token: rt,
						tokenSecret: rs
					},
					re_url = generateOauthUrl(url, options);
					div.innerHTML = '请在新弹出的页面中完成授权，完成授权后请复制萌否开放平台提供的验证码，并粘贴于下面的文本框中。<br>如果浏览器未弹出授权页面，请手动<a href="' + re_url + '" target="_blank">点击此处</a>打开授权页面。<br>请于 1 小时内完成授权。<br><input type="text" placeholder="请在此输入验证码" class="login_verifier"></input><br><button class="login_confirm_last">确定</button>';
					$('.login_confirm_last').addEventListener('click',function(){
						var url='http://api.moefou.org/oauth/access_token',
						options = {
							method: 'get',
							consumerKey: ck,
							consumerSecret: cs,
							token: rt,
							tokenSecret: rs,
							verifier: $('.login_verifier').value
						}
						xhr2 = new XMLHttpRequest();
						xhr2.onreadystatechange = function(){
							if (xhr2.readyState == 4) {
								if (xhr2.status == 200) {
									var data = xhr2.responseText,
									accessToken = data.replace(/.*\boauth_token=([a-z0-9]+).*/, '$1'),
									accessTokenSecret = data.replace(/.*\boauth_token_secret=([a-z0-9]+).*/, '$1');
									localStorage.setItem('accessToken', accessToken);
									localStorage.setItem('accessTokenSecret', accessTokenSecret);
									check_login();
									setTimeout(function() {
										$.D.d(div);
										setTimeout(function() {
											$.D.d(div2)
										}, 200)
									}, 200)
								} else {
									update_error('login', 'XHR Ready State: ' + xhr2.readyState + '<br>XHR Status: ' + xhr2.statusText)
								}
							}
						};
						xhr2.open('GET', generateOauthUrl(url, options));
						xhr2.send()
					});
					window.open(re_url, 'authorized');
				} else {
					update_error('login', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
				}
			}
		};
		xhr.open('GET',generateOauthUrl(url,options));
		xhr.send()
	}, false)
},
logout = function() {
	var c = confirm('您即将登出，在下次登录时需要重新授权，是否继续？');
	if (c == true) {
		localStorage.removeItem('accessToken');
		localStorage.removeItem('accessTokenSecret');
		set_login();
		var c = confirm('数据已清除，您已成功登出本站。\n是否需要前往萌否开放平台取消授权？');
		if (c == true) {
			window.open('http://open.moefou.org/apps/authorized', 'authorized')
		}
	}
},
about = function() {
	var div = $.D.m('div'),
	div2 = $.D.m('div');
	div.className = 'about_panel';
	div2.className = 'about_panel_background';
	div2.title = '点击黑色区域以退出';
	div.innerHTML = '<strong>MoeFM HTML5 Project (Beta)</strong><br>萌否电台 HTML5 版本（非官方）<br>作者：<a href="http://moefou.org/home/864907600cc" target="_blank">864907600cc</a><br>致谢：<a href="http://blog.likelikeslike.com/" target="_blank">Jak Wings</a>（提供萌否 OAuth 认证 example）、<a href="http://moefou.org/home/zanko" target="_blank">zanko</a>（提供 API 使用支持）<br>桌面设备测试：Chrome 32/33 (Windows 7 64-bit/Ubuntu 13.10 64-bit)、Firefox 25 (Windows 7 64-bit)、Internet Explorer (Windows 7 64-bit)<br>移动设备测试：Android 自带浏览器（Android 4.1/4.3）、UC 浏览器（Android 2.2/4.3，不完全支持）、海豚浏览器（Android 4.3）<br>Powered by <a href="http://moe.fm" target="_blank">Moe.FM</a> | Hosted on <a href="https://www.openshift.com">OpenShift</a><br><p><a href="http://moefou.org/group/moefm_html5_project" target="_blank">项目小组</a> <a href="http://moefou.org/topic/1730" target="_blank">Bug 反馈</a></p>';
	$.D.a(div);
	$.D.a(div2);
	div2.addEventListener('click', function(){
		setTimeout(function() {
			$.D.d(div);
			setTimeout(function() {
				$.D.d(div2)
			}, 200)
		}, 200)
	})
},
\u7a7f\u8d8aOAO = function() {
	audio.pause();
	var div = $.D.m('div'),
	div2 = $.D.m('div');
	div.className = '穿越_panel';
	div2.className = '穿越_panel_background';
	div2.title = '点击黑色区域以退出';
	div.innerHTML = '扫描二维码，在移动设备上继续收听，无需重新登录<br><img src="http://moefm.ccloli.com/qr.php?data=' + encodeURIComponent('http://moefm.ccloli.com/?song=' + playlist[count].sub_id + '#' + (accessToken != null ? ('accessToken=' + accessToken + ',accessTokenSecret=' + accessTokenSecret) + ',' : '') + 'currentTime=' + audio.currentTime) + '" alt="" width="180" height="180">';
	$.D.a(div);
	$.D.a(div2);
	div2.addEventListener('click', function() {
		setTimeout(function() {
			$.D.d(div);
			setTimeout(function() {
				$.D.d(div2)
			}, 200)
		}, 200);
		audio.play()
	})
},
start = function() {
	if (location.search.indexOf('song') >= 0) {
		update_playlist(location.search.match(/song=[0-9,]*/)[0])
	} else if(location.search.indexOf('music') >= 0) {
		update_playlist(location.search.match(/music=[0-9,]*/)[0])
	} else if(location.search.indexOf('radio') >= 0) {
		update_playlist(location.search.match(/radio=[0-9,]*/)[0])
	} else {
		update_playlist()
	}
};
audio.addEventListener('play', function() {
	c_play.setAttribute('hidden', 'hidden');
	c_pause.removeAttribute('hidden');
	update_info()
}, false);
audio.addEventListener('pause', function() {
	c_pause.setAttribute('hidden', 'hidden');
	c_play.removeAttribute('hidden')
}, false);
audio.addEventListener('timeupdate', function() {
	if (!isNaN(audio.duration)) {
		$.css(timeline_current, 'width: ' + (audio.currentTime / audio.duration) * 100 + '%');
		if (audio.buffered.length>0) {
			$.css(timeline_duration, 'width: ' + (audio.buffered.end(audio.buffered.length-1).toFixed(2)) / (audio.duration.toFixed(2)) * 100 + '%')
		};
		timeline_current_time.innerHTML = parseInt(audio.currentTime / 60) + ':' + (parseInt(audio.currentTime) % 60 < 10 ? '0' + parseInt(audio.currentTime) % 60 : parseInt(audio.currentTime) % 60);
		timeline_duration_time.innerHTML = parseInt(audio.duration / 60) + ':' + (parseInt(audio.duration) % 60 < 10 ? '0' + parseInt(audio.duration) % 60 : parseInt(audio.duration) % 60)
	}
}, false);
audio.addEventListener('error', function() {
	var context;
	switch (audio.error.code) {
		case 1:
			context = 'MEDIA_ERR_ABORTED（文件在取回时被用户中止）';
			break;
		case 2:
			context = 'MEDIA_ERR_NETWORK（文件在下载时发生错误）';
			break;
		case 3:
			context = 'MEDIA_ERR_DECODE（文件在解码时发生错误）';
			break;
		case 4:
			context = 'MEDIA_ERR_SRC_NOT_SUPPORTED（不支持的音频格式）';
			break;
		default:
			context = 'MEDIA_ERR_UNKNOWN（未知错误，错误代码：'+audio.error.code+'）';
	};
	switch (audio.networkState) {
		case 0:
			context += '<br>NETWORK_EMPTY（音频尚未初始化）';
			break;
		case 1:
			context += '<br>NETWORK_IDLE（音频已缓存）';
			break;
		case 2:
			context += '<br>NETWORK_LOADING（浏览器正在下载数据）';
			break;
		case 3:
			context += '<br>NETWORK_NO_SOURCE（未找到音频来源）';
			break;
		default:
			context += 'NETWORK_UNKNOWN（未知错误，错误代码：' + audio.error.code + '）';
	};
	update_error('audio', context);
	audio_play()
}, false);
audio.addEventListener('ended', function() {
	if (next == 0) {
		update_log()
	} else {
		next = 0
	};
	audio_play()
}, false);
cover_preload.addEventListener('load', function(){
	$.css(cover, 'background-image: url(' + playlist[count].cover.large + ')');
	$.css(cover_loading_notification, 'opacity: 0');
}, false);
cover_preload.addEventListener('error', function() {
	if (cover_retry < 3) {
		update_error('cover', '正在重试加载......');
		cover_preload.src = playlist[count].cover.large;
		cover_retry++
	} else {
		update_error('cover', '超过最大重新加载次数限制');
		cover_retry = 0
	}
}, false);
c_play.addEventListener('click', audio.play, false);
c_pause.addEventListener('click', audio.pause, false);
c_previous.addEventListener('click', function() {
	if (count > 0) {
		next = 1;
		count -= 2;
		audio_play()
	}
}, false);
c_next.addEventListener('click', function() {
	next = 1;
	audio_play()
}, false);
c_like.addEventListener('click', function() {
	if (c_like.hasAttribute('meow')) {
		update_fav(1, 1)
	} else {
		update_fav(1, 0)
	}
}, false);
c_dislike.addEventListener('click', function() {
	if (c_dislike.hasAttribute('meow')) {
		update_fav(2, 1)
	} else {
		update_fav(2, 0)
	}
}, false);
c_volume_icon.addEventListener('click', function() {
	if (c_volume_range.hasAttribute('disabled')) {
		c_volume_range.removeAttribute('disabled');
		$.css(c_volume_range, 'opacity: 1');
		audio.volume = volume / 100;
		update_volume_icon(volume)
	} else {
		volume = audio.volume * 100;
		c_volume_icon.innerHTML = '静';
		c_volume_range.setAttribute('disabled', 'disabled');
		$.css(c_volume_range, 'opacity: 0.75');
		audio.volume = 0;
	}
}, false);
c_volume_range.addEventListener('change', function() {
	volume = c_volume_range.value;
	audio.volume = volume / 100;
	update_volume_icon(volume);
	setting.volume = volume;
	localStorage.setItem('moefm-html5-setting', JSON.stringify(setting))
}, false);
c_share.addEventListener('click', share, false);
timeline.addEventListener('mouseup', function(event) {
	audio.currentTime = (event.clientX / document.body.clientWidth) * audio.duration
}, false);
aside_album.addEventListener('click', function() {
	p = 0;
	update_playlist('fav=music')
}, false);
aside_song.addEventListener('click', function() {
	p = 0;
	update_playlist('fav=song')
}, false);
aside_radio.addEventListener('click', function() {
	p = 0;
	update_playlist('fav=radio')
}, false);
aside_random.addEventListener('click', function() {
	p = 0;
	url_data = null;
	update_playlist()
}, false);
background_preload.addEventListener('load', function() {
	$.css($('html'), 'background-image: url(' + background_preload.src + ')')
}, false);
background_preload.addEventListener('error', function() {
	update_error('background', '获取背景图片时发生错误');
}, false);
link_setting_background.addEventListener('click', function() {
	var div = $.D.m('div'),
	div2 = $.D.m('div'),
	t = $.D.m('textarea'),
	b = $.D.m('button');
	div.className = 'setting_background_panel';
	div2.className = 'setting_background_panel_background';
	div2.title = '点击黑色区域以退出';
	b.innerHTML = '确定';
	t.setAttribute('title', '请在文本框内输入图片地址，以回车间隔，一行一个');
	t.setAttribute('autofocus', 'autofocus');
	$.D.a(div ,t);
	$.D.a(div, b);
	if (background_list.length != 0) {
		t.value = background_list.join('\n')
	};
	$.D.a(div);
	$.D.a(div2);
	div2.addEventListener('click', function() {
		setTimeout(function() {
			$.D.d(div);
			setTimeout(function() {
				$.D.d(div2);
			}, 200)
		}, 200)
	}, false);
	b.addEventListener('click', function() {
		var l = t.value.split('\n'),
		r = [];
		for (var i = 0; i < l.length; i++) {
			if (l != '') {
				r.push(l[i])
			}
		};
		background_list = r;
		div2.click();
		update_background();
		setting.background = r;
		localStorage.setItem('moefm-html5-setting', JSON.stringify(setting))
	}, false)
}, false);
link_about.addEventListener('click', about, false);
album.addEventListener('click', function() {
	if (confirm('播放专辑『' + playlist[count].wiki_title + '』？') == true) {
		update_playlist('music=' + playlist[count].wiki_id)
	}
}, false);
window.addEventListener('keydown', function(e) {
	switch (e.keyCode) {
		case 32:
			audio.paused == false ? audio.pause() : audio.play();
			break;
		case 39:
			next = 1;
			audio_play();
			break;
		case 38:
			c_like.click();
			break;
		case 40:
			c_dislike.click();
			break;
		case 37:
			next = 1;
			count -= 2;
			audio_play();
			//穿越OAO();
			break;
		case 13:
			穿越OAO();
			break;
	}
}, false);
if (location.hash.indexOf('accessToken') >= 0) {
	localStorage.setItem('accessToken', location.hash.match(/accessToken=([0-9a-f]{41})/)[1]);
	localStorage.setItem('accessTokenSecret', location.hash.match(/accessTokenSecret=([0-9a-f]{32})/)[1]);
};
if (localStorage.getItem('accessToken')) {
	check_login()
} else {
	set_login();
	start()
};
if (location.hash.indexOf('currentTime') >= 0) {
	audio.onplay = function() {
		audio.currentTime = location.hash.match(/currentTime=([0-9.]+)/)[1];
		audio.onplay = null;
	}
};
c_volume_range.value = volume;
audio.volume = volume / 100;
update_volume_icon(volume);
if (location.hash.indexOf('background=0') < 0) {
	update_background();
	update_background_count(1);
	window.addEventListener('focus', function() {
		update_background_count(1)
	}, false);
	window.addEventListener('blur', function() {
		update_background_count(0)
	}, false)
};
if (location.search.indexOf('loop=1') > 0) {
	setting.loop = 1;
	localStorage.setItem('moefm-html5-setting', JSON.stringify(setting))
};