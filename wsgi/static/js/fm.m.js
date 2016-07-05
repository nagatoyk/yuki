var $ = function(win, doc, $) {
	if (!/MSIE 10.0|rv:11.0/.test(navigator.userAgent) || !win.localStorage || !win.XMLHttpRequest) {
		// doc.title = "除火狐和谷歌之外的浏览器 就别来了";
		// return doc.getElementsByTagName("body")[0].innerHTML = '<p class="loading">IE和弱渣浏览器就不用看本站了<br/>用火狐(FF)或谷歌(Chrome)来浏览<br/>----------By 小熊</p>'
	};
	$ = function(i) {
		return doc.querySelector(i)
	};
	$("html").className = self.ActiveXObject ? "IE": self.chrome ? "GC": self.mozPaintCount > ~ [] ? "FF": self.opera ? "Op": self.chrome && !!self.WebKitPoint ? "Sa": "Un";
	$.c = function(p, c) {
		! c && (c = p) && (p = doc);
		for (var n = " ", e = p.getElementsByTagName("*"), r = [], i = 0, j; j = e[i]; i++) {
			(n + j.className + n).indexOf(n + c + n) == -1 || r.push(j)
		};
		return r
	};
	$.css = function(e, d) {
		e.style.cssText += (";" + d)
	};
	$.cookie = function(g, f, h) {
		if (typeof f != "undefined") {
			h = h || 31536000;
			var j = new Date();
			j.setTime(j.getTime() + h * 1000);
			doc.cookie = g + "=" + escape(f) + ";expires=" + j.toGMTString()
		} else {
			var e = doc.cookie.match(new RegExp("(^| )" + g + "=([^;]*)(;|$)"));
			return e == null ? null: unescape(e[2])
		}
	};
	$.D = {
		m: function(d) {
			return doc.createElement(d)
		},
		d: function(d) {
			if (d) {
				return d.parentNode.removeChild(d)
			}
		},
		a: function(e, d) {
			! d && (d = e) && (e = doc.body);
			return e.appendChild(d)
		},
		b: function(e, d) {
			e.insertBefore(d, e.childNodes[0])
		},
		c: function(d) {
			return d.cloneNode(1)
		}
	};
	$.l = function(str) {
		for (var _a = parseInt(str), _n = str.substr(1), _c = Math.floor(_n.length / _a), _y = _n.length % _a, _s = new Array(), i = 0; i < _y; i++) {
			_s[i] = _n.substr((_c + 1) * i, _c + 1)
		};
		for (i = _y; i < _a; i++) {
			_s[i] = _n.substr(_c * (i - _y) + (_c + 1) * _y, _c)
		};
		for (i = 0, _c = _n = ""; i < _s[0].length; i++) {
			for (j = 0; j < _s.length; j++) {
				_c += _s[j].substr(i, 1)
			}
		};
		for (i = 0, _c = unescape(_c); i < _c.length; i++) {
			_c.substr(i, 1) == "^" ? _n += "0": _n += _c.substr(i, 1)
		};
		return _n
	};
	$.j = function(g, j, k, h, d) {
		h = $.D.m("script");
		d = "cb" + new Date().valueOf() + (Math.random() + "").substring(3);
		if (j && g.match(/\{cb\}/)) {
			win[d] = j
		};
		h.src = g.replace(/\{cb\}/, d);
		h.charset = "UTF-8";
		h.onload = function() {
			if (j && !g.match(/\{cb\}/)) {
				j()
			};
			$.D.d(h)
		};
		h.onerror = function() {
			if (k) {
				k()
			};
			$.D.d(h)
		};
		$.D.a(h)
	};
	$.os = /iP(hone|ad|od)|Android/.test(navigator.userAgent);
	$.re_date = function(e) {
		var h = new Date(),
		d = new Date((e + "").match(/[0-9]{10}/) ? e * 1000 : e),
		g = parseInt,
		f = g((h - d) / 1000);
		return ! e || f < 0 ? "刚刚": f < 60 ? (f + "秒前") : (f /= 60) < 60 ? g(f) + "分前": (f /= 60) < 24 ? g(f) + "时前": (f /= 24) < 7 ? g(f) + "天前": (f /= 7) < 2 ? g(f) + "周前": d > new Date(h.getFullYear() + "-01-01") ? (d.getMonth() + 1) + "月" + d.getDate() + "日": d.getFullYear() + "年" + (d.getMonth() + 1) + "月" + d.getDate() + "日"
	};
	$.S = function(d) {
		return doc.querySelectorAll(d)
	};
	$.swf = function(i) {
		return doc[i] || $(i)
	};
	$.t = function(p, i) {
		! i && (i = p) && (p = doc);
		return p.getElementsByTagName(i)
	};
	! win.JSON && (win.JSON = {
		parse: function(i) {
			return eval("(" + i + ")")
		}
	});
	! win.console && (win.console = {
		log: function(i) {
			return i
		}
	});
	$.x = function(d) {
		return function(h, l, j, k, g) {
			if (typeof l == "function") {
				k = j;
				j = l;
				l = 0
			};
			if (d[h]) {
				return j(d[h])
			};
			g = new XMLHttpRequest();
			g.open(l ? "POST": "GET", h, true);
			! l || g.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			if (j || k) {
				g.onreadystatechange = function() {
					if (g.readyState == 4) {
						((g.status > 199 && g.status < 301) || g.status == 304) ? j(d[h + (l || "")] = (g.getResponseHeader("Content-Type") || "").match(/json/) ? JSON.parse(g.responseText || "null") : g.responseText) : (!k) || k(g.status)
					}
				}
			};
			g.send(l || "");
			return g
		}
	} ({});
	return $
} (window, document);
