var $ = function(c, b, a) {
    if (!c.localStorage || !c.XMLHttpRequest) {
        return b.getElementsByTagName('body')[0].innerHTML = '<p class="loading">请更新现代浏览器。'
    };
    a = function(d) {
        return b.querySelector(d)
    };
    a.S = function(d) {
        return b.querySelectorAll(d)
    };
    a.css = function(e, d) {
        e.style.cssText += (';' + d)
    };
    a.x = function(d) {
        return function(h, l, j, k, g) {
            if (typeof l == 'function') {
                k = j;
                j = l;
                l = 0
            }
            if (d[h]) {
                return j(d[h])
            }
            (g = new XMLHttpRequest()).open(l ? 'POST': 'GET', h, 1);
            ! l || g.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            if (j || k) {
                g.onreadystatechange = function() {
                    if (g.readyState == 4) {
                        ((g.status > 199 && g.status < 301) || g.status == 304) ? j(d[h + (l || '')] = (g.getResponseHeader('Content-Type') || '').match(/json/) ? JSON.parse(g.responseText || 'null') : g.responseText) : (!k) || k(g.status)
                    }
                };
            }
            g.send(l || '');
            return g
        };
    } ({});
    a.j = function(g, j, k, h, d) {
        h = a.D.m('script');
        d = 'cb' + new Date().valueOf() + (Math.random() + '').substring(3);
        if (j && g.match(/\{cb\}/)) {
            c[d] = j
        }
        h.src = g.replace(/\{cb\}/, d);
        h.charset = 'UTF-8';
        h.onload = function() {
            if (j && !g.match(/\{cb\}/)) {
                j()
            }
            a.D.d(h)
        };
        h.onerror = function() {
            if (k) {
                k()
            }
            a.D.d(h)
        };
        a.D.a(h)
    };
    a.cookie = function(g, f, h) {
        if (typeof f != 'undefined') {
            h = h || 31536000;
            var j = new Date();
            j.setTime(j.getTime() + h * 1000);
            b.cookie = g + '=' + escape(f) + ';expires=' + j.toGMTString();
        } else {
            var e = b.cookie.match(new RegExp('(^| )' + g + '=([^;]*)(;|$)'));
            return e == null ? null: unescape(e[2]);
        }
    };
    a.b = a('html').className = self.ActiveXObject ? 'IE': self.chrome ? 'Cr': self.mozPaintCount > ~ [] ? 'FF': self.opera ? 'Op': self.chrome && !!self.WebKitPoint ? 'Sa': '';
    a.D = {
        m: function(d) {
            return b.createElement(d)
        },
        d: function(d) {
            if (d) {
                return d.parentNode.removeChild(d)
            }
        },
        a: function(e, d) { ! d && (d = e) && (e = b.body);
            return e.appendChild(d)
        },
        b: function(e, d) {
            e.insertBefore(d, e.childNodes[0])
        },
        c: function(d) {
            return d.cloneNode(1)
        }
    };
    a.swf = function(d) {
        return b[d] || a('#' + d)
    };
    a.re_date = function(e) {
        var h = new Date(),
        d = new Date((e + '').match(/[0-9]{10}/) ? e * 1000 : e),
        g = parseInt,
        f = g((h - d) / 1000);
        return ! e || f < 0 ? '刚刚': f < 60 ? (f + '秒前') : (f /= 60) < 60 ? g(f) + '分前': (f /= 60) < 24 ? g(f) + '时前': (f /= 24) < 7 ? g(f) + '天前': (f /= 7) < 2 ? g(f) + '周前': d > new Date(h.getFullYear() + '-01-01') ? (d.getMonth() + 1) + '月' + d.getDate() + '日': d.getFullYear() + '年' + (d.getMonth() + 1) + '月' + d.getDate() + '日'
    };
    a.unicode = {
        decToHex: function(str) {
            var res = [];
            for (var i = 0; i < str.length; i++) {
                res[i] = ("00" + str.charCodeAt(i).toString(16)).slice(-4)
            };
            return "\\u"+res.join("\\u");
        },
        hexToDec: function(str) {
            str = str.replace(/\\/g,"%");
            return unescape(str)
        }
    };
    String.prototype.enTxt = function() {
        return this.replace(/(^\s*)|(\s*$)/g, '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;').replace(/\'/g, '&#39;').replace(/\'/g, '&quot;').replace(/\n/g, '<br>')
    };
    String.prototype.enHtml = function() {
        return this.replace(/(^\s*)|(\s*$)/g, '').replace(/(http\:\/\/[0-9A-Za-z\/.#&!?%:;=_]+\.)(gif|jpg|jpeg|png)/g, '<img src="$1$2">').replace(/(http\:\/\/ww[0-9]{1}\.sinaimg\.cn\/)([\w]{4,10})(\/[\w]{16,32}\.)(gif|jpg|jpeg|png)/g, '$1mw1024$3$4').replace(/http:\/\/www\.xiami\.com\/song\/([0-9]{5,12})[\?\w\.\=]*/g, '<a href="//www.xiami.com/song/$1" target="_blank" class="xiami">http://www.xiami.com/song/$1</a>').replace(/(@)([\u4e00-\u9fa5\u0800-\u4e00A-Za-z0-9\-_]{2,32})/g, '<a href="//weibo.com/n/$2" target="_blank" class="at">$1$2</a>').replace(/(^|[^\'\'\]>])(http|ftp|mms|rstp|news|https|telnet)\:\/\/([0-9A-Za-z\/.#&!?%:;=\-_]+)/g, '$1<a href="$2://$3" rel="external nofollow noreferer" class="link" target="_blank">$2://$3</a>').replace(/\n/g, '<br>')
    };
    if (!c.console) {
        c.console = {
            log: function() {}
        };
    }
    a.lcss = function(d) {
        return function(g, e, f) {
            if (d.indexOf(g) < 0) {
                (e = a.D.m('link')).href = g;
                f = a('head');
                e.rel = 'stylesheet';
                e.charset = 'UTF-8';
                a.D.a(f, e);
                d += g + '|'
            }
        };
    } ('|');
    return a;
} (window, document);
W = function(b, a) {
    return {
        en: function(c) {
            for (a = b.length, i = 0; i < a; i++) {
                c = c.replace(new RegExp(b[i][0], 'g'), b[i][1])
            }
            return c;
        },
        de: function(c) {
            for (i = b.length - 1; i >= 0; i--) {
                c = c.replace(new RegExp(b[i][1], 'g'), b[i][0])
            }
            return c
        }
    };
} ([['me', '2'], ['ga2', '3'], ['om', '4'], ['e', '5'], ['o', '6'], ['/', '0'], ['c', '7'], ['www', '8'], ['x', '9']]);