var fm = function() {
	var run = 0,
	img = $.t($(".cover"), "img")[0],
	title = $.t($(".info"), "a")[0],
	album = $.t($(".info"), "a")[1],
	list = [],
	_oimg,
	tmp_img = new Image(),
	planTime,
	add = function(i) {
		$.x('/fm', ((i + "").match(/[0-9]{6,13}/) ? "a=song&xid=" + i : "a=radio"), function(json) {
			list = list.concat(json);
			run || fm.next();
			run = true;
		});
	},
	fm = {
		load: function() {
			add(location.hash.substring(1, 2) == "!" ? location.hash.substring(2) : "");
		},
		open: function() {
			$("#pause").className = "pause";
			$("#next").className = "next";
			$.css($.t($("#plan"), "b")[0], "width:0%");
			$.css($.t($("#plan"), "i")[0], "width:0%");
		},
		song: function(i) {
			$.x("?a=song&song=" + i, function(json) {
				list.unshift(json[0]);
				fm.next();
				run = true;
			});
		},
		next: function() {
			if (!list[0]) return console.log("列表载入中");
			if (!$.os) $.swf("fm").Load($.l(list[0][3]));
			else {
				audio.src = $.l(list[0][3]);
				audio.play();
				fm.open();
			};
			fm.plan();
			_popstate_run = true;
			location.hash = "#!" + list[0][0];
			_popstate_run = false;
			_oimg = list[0][2];
			$("#icon").href = $("#fav").href = img.src = _oimg.replace(/_(3|2|1)\./, "_4.");
			title.text = list[0][1].replace(/(^\s*)|(\s*$)/g, "");
			title.href = "javascript:open('http://www.xiami.com/song/" + list[0][0] + "')";
			$("#s").value = $("#x").innerHTML = document.title = title.text;
			// $("#add_hop").href = "javascript:add_hop('" + list[0][0] + "', '" + document.title + "')";
			album.href = "javascript:open('http://www.xiami.com/album/" + list[0][6] + "')";
			if (!$.os) {
				album.text = "「" + list[0][4] + "」" + list[0][5];
			} else {
				album.text = list[0][5];
			};
			if (list[1]) tmp_img.src = _oimg.replace(/_(3|2|1)\./, ".");
			dm.song(list[0][0]);
			Lrc.song(list[0][0]);
			$("#x").href = "http://www.xiami.com/song/" + list[0][0];
			img.className = "boom";
			$("#wb").href = "http://service.weibo.com/share/share.php?url=" + encodeURIComponent(location.href) + "&title=" + encodeURIComponent("#凉宫春日# #偷揉电台# 正在播放『" + document.title + "』，这里可以边听歌变发射弹幕哟~") + "&type=button&ralateUid=&language=zh_cn&appkey=1LefGk&pic=" + encodeURIComponent(img.src);
			$("#tq").href = "http://share.v.t.qq.com/index.php?c=share&a=index&url=" + encodeURIComponent(location.href) + "%236482817-tqq-1-31785-d6bec79f6188eacf45f691034fc59fbc&title=" + encodeURIComponent("#凉宫春日# #偷揉电台# 正在播放『" + document.title + "』，这里可以边听歌变发射弹幕哟~") + "&pic=" + encodeURIComponent(img.src) + "&appkey=801005675&assname=ngtyuki";
			$.css($("#wb"), "opacity:1");
			list.shift();
			if (list.length < 2) add();
		},
		t: function() {
			return  !$.os ? $.swf("fm").Time() : audio.currentTime;
		},
		plan: function() {
			clearTimeout(planTime);
			if (!$.os) {
				$.css($.t($("#plan"), "i")[0], "width: " + $.swf("fm").Plan()[0] * 100+ "%");
				$.css($.t($("#plan"), "b")[0], "width: " + $.swf("fm").Plan()[1] * 100 + "%");
			} else {
				if (audio.buffered.length > 0) $.css($.t($("#plan"), "i")[0], "width: " + (audio.buffered.end(audio.buffered.length - 1).toFixed(2)) / (audio.duration.toFixed(2)) * 100 + "%");
				$.css($.t($("#plan"), "b")[0], "width: " + audio.currentTime / audio.duration * 100 + "%");
			}
			/*if ($.t($("#plan"), "b")[0].style.width == cWidth + "px") {
				$.x("log.php", "log[sid]=" + location.hash.substring(2) + "&log[name]=" + encodeURIComponent(document.title), function(json) {
					console.log(json)
				})
			}*/
			$.t($(".info"), "span")[0].innerHTML = (parseInt(fm.t() / 60) < 10 ? "0" + parseInt(fm.t() / 60) : parseInt(fm.t() / 60)) + ":" + (parseInt(fm.t()) % 60 < 10 ? "0" + parseInt(fm.t()) % 60 : parseInt(fm.t()) % 60);
			planTime = setTimeout(fm.plan, 100);
		},
	};
	img.onerror = function() {
		this.src = _oimg;
	};
	img.onload = function() {
		setTimeout(function() {
			img.className = "";
		}, 300);
	};
	return fm;
} (window, document),
Lrc = function(obj) {
	var lrc_arr = [],
	interval,
	Lrc = {
		num: 0,
		song: function(id) {
			$.x("lrc.php?id=" + id, function(txt) {
				var txt_arr = txt.split("\n");
				clearTimeout(interval);
				obj.innerHTML = "";
				Lrc.num = 0;
				lrc_arr = [];
				for (var i in txt_arr) {
					var _time = txt_arr[i].match(/\[\d{2}:\d{2}((\.|\:)\d{2})\]/g),
					_txt = txt_arr[i].replace(/\[[0-9:.]{5,8}\]/g, "");
					for (var _i in _time) {
						var _t_text = String(_time[_i]);
						lrc_arr.push([(_t_text.match(/\[[0-9]{2}/) + "").substr(1) * 60 + (_t_text.match(/\:[0-9]{2}/) + "").substr(1) * 1 + (_t_text.match(/[0-9]{2}\]/) + "").substr(0, 2) * 0.01666, _txt])
					}
				};
				lrc_arr.sort(function(a, b) {
					return a[0] < b[0] ? -1 : 1;
				});
				interval = setTimeout(Lrc.step, 200);
			});
		},
		step: function() {
			var Song_time = fm.t() + 0.5;
			for (var _i = Lrc.num, _l = lrc_arr.length; _i < _l; _i++) {
				if (lrc_arr[_i][0] < Song_time) {
					obj.innerHTML = lrc_arr[_i][1];
					Lrc.num++;
				} else {
					break;
				}
			}
			if (_l > Lrc.num) {
				interval = setTimeout(Lrc.step, 200);
			}
		}
	};
	return Lrc;
} ($("#lrc")),
dm = function() {
	var interval, dm_list = [],
	project_num = 0,
	p_height = 48,
	dm = {
		song: function(i) {
			$.x("dm.php?sid=" + i + "&r=" + Math.random(), function(json) {
				obj.innerHTML = "";
				dm_list = json;
				project_num = 0;
				clearTimeout(interval);
				interval = setTimeout(dm.step, 100)
			});
			var _in = $("#dm_msg"),
			_dm_ti = $("#dm_tip");
			_in.onmouseover = function() {
				_dm_ti.className = "a";
			};
			_in.onmouseout = function() {
				_dm_ti.className = "";
			};
			$("#dm_f").onsubmit = function() {
				if (!_in.value) {
					_in.focus();
					return false;
				}
				$.x("dm.php?_=" + new Date().getTime(), "dms[dm]=" + i + "&dms[title]=" + encodeURIComponent(document.title) + "&dms[t]=" + parseInt(fm.t()) + "&dms[msg]=" + encodeURIComponent(_in.value), function() {
					var span = $.D.m("span");
					span.innerHTML = _in.value;
					var _html = $.t("html")[0],
					_width = _html.clientWidth,
					_height = _html.clientHeight,
					_left = (_width - 20);
					span.setAttribute("left", _left);
					$.css(span, "top:" + (parseInt(Math.random() * (_height - 170)) + 60) + "px;left:" + _left + "px;color:#0079FF;border:1px solid #0079FF;");
					$.D.a(obj, span);
					_in.value = "";
				});
				return false;
			};
		},
		exit: function() {
			obj.innerHTML = "";
			clearTimeout(interval);
		},
		step: function() {
			var _width = obj.clientWidth;
			var _height = obj.clientHeight;
			for (var o = $.t(obj, "span"), l = o.length, i = 0; i < l; i++) {
				var _left = o[i].getAttribute("left");
				_left -= 9;
				if (_left + o[i].clientWidth > 0) {
					o[i].setAttribute("left", _left);
					$.css(o[i], "left:" + _left + "px;");
				} else {
					$.D.d(o[i]);
					i--;
					l--;
				}
			}
			var t = parseInt(fm.t());
			for (var o = dm_list, l = dm_list.length, i = project_num; i < l; i++) {
				if (o[i][0] < t && o[i][1] != false) {
					if (o[i][0] > (t - 2)) {
						var span = $.D.m("span");
						span.innerHTML = o[i][1];
						_left = (_width - 20);
						span.setAttribute("left", _left);
						$.css(span, "top:" + (Math.random() * 70 + 5) + "%;left:" + _left + "px;");
						$.D.a(obj, span);
					}
					project_num++;
				}
			}
			interval = setTimeout(dm.step, 100);
		}
	};
	var obj = $.D.m("div");
	obj.id = "dm_b";
	$.D.a(obj);
	return dm;
} (),
ad_close = function() {
	$("#ad").className = "";
	$.cookie("ad", 1);
	return false;
},
fm_search_re = function(re) {
	var text = "";
	for (var i in re) {
		text += '<a href="#!' + re[i].id + '">' + decodeURIComponent(re[i].title) + " - " + decodeURIComponent(re[i].author) + "</a>";
	}
	$("#s_d").innerHTML = text + "<span>关闭搜索结果</span>";
},
search_close = function() {
	if (!$("#s_d").innerHTML && $("#s").value == $("#x").innerHTML) {
		$("#s_f").className = "";
	}
},
add_hop = function(sid, name) {
	$.x("hop.php?_=" + new Date().getTime(), "hop[sid]=" + parseInt(sid) + "&hop[name]=" + encodeURIComponent(name), function(json) {
		$("#add_hop").innerHTML = json[0];
		setTimeout(function() {
			$("#add_hop").innerHTML = "申请加入电台列表";
		}, 300);
		console.log(json);
	});
},
open = function(url) {
	var vra = document.createElement("a");
	vra.target = "_blank";
	vra.href = url;
	document.body.appendChild(vra);
	vra.click();
},
login_btn = function() {
	if (!$.cookie("login")) {
		open("https://api.weibo.com/oauth2/authorize?client_id=1093396876&redirect_uri=" + encodeURIComponent("https://yukimax.sinaapp.com/call.php") + "&response_type=code&state=fm&display=default");
	}
},
login_check = function() {
	console.log($.cookie("login"));
};
$("#s_f").onsubmit = function() {
	if ($("#s").value) {
		$("#box").className = "a";
		$.j("/key/" + encodeURI($("#s").value) + "/callback/fm_search_re");
		$("#s_d").innerHTML = "<span>正在搜索中...</span>";
	}
	return false;
};
$("#s").onfocus = function() {
	$("#s_f").className = "a";
	return false;
};
$("#s").onblur = function() {
	if (!$("#s_d").innerHTML) {
		$("#s_f").className = "";
	}
};
$("#s_d").onclick = function(e) {
	e = e || window.event;
	obj = e.srcElement || e.target;
	if (obj.tagName == "A") {
		var xid = obj.href.match(/[0-9]{6,13}/);
		fm.song(xid);
		_popstate_run = true;
	} else if (obj.tagName == "SPAN") {
		$("#s_d").innerHTML = "";
		search_close();
	}
};
$("#wb").onclick = function() {
	window.open(this.href, "_blank", "resizable=0,scrollbars=0,width=800,height=600");
	return false;
};
$("#tq").onclick = function() {
	window.open(this.href, "_blank", "resizable=0,scrollbars=0,width=800,height=600");
	return false;
};
$("#logo").onclick = function() {
	$("#ad").className = "show";
};
$.t($("#ad"), "span")[0].onclick = ad_close;
$.t($("#ad"), "b")[0].onclick = ad_close;
$.t($("#ad"), "b")[1].onclick = login_btn;
if (!$.cookie("ad")) $("#ad").className = "show";
if (!$.cookie("login")) $.css($("#dm_f"), "display:none");
$("#next").onclick = fm.next;
$("#pause").onclick = function() {
	if ($.os) {
		if (audio.paused) {
			audio.play();
			this.className = "pause";
			$("#next").className = "next";
		} else {
			audio.pause();
			this.className = "play";
			$("#next").className = "next hide";
		}
	} else {
		this.className = $.swf("fm").Pause() ? "pause" : "play";
	}
};
var _popstate_run = true;
window.onpopstate = function() {
	if (!_popstate_run && location.hash.substring(1, 2) == "!") {
		run = false;
		fm.song(location.hash.substring(2));
	}
	_popstate_run = false;
};
console.log("从卜卜口(http://itorr.sinaapp.com/fm)那抓来的第五版的偷揉电台QWQ @卜卜口");
if ($.os) {
	var audio = new Audio();
	audio.rel = "noreferer";
	audio.onerror = function(e) {
		console.log("出错了: " + e);
	};
	audio.addEventListener("ended", function() {
		if (location.hash.substring(1, 2) == "!") {
			$.x("log.php", "log[sid]=" + location.hash.substring(2) + "&log[name]=" + encodeURIComponent(document.title), function(json) {
				console.log(json);
				fm.next();
			});
		}
	}, false);
	fm.load();
}
