var audio_pretest = document.createElement('audio');
if (audio_pretest.canPlayType('audio/mpeg') == '') {
    alert('很抱歉，看起来您的浏览器不支持 MPEG (MP3) 文件……\n支持 MPEG 编码的浏览器请参考 http://caniuse.com/#feat=mpeg4');
    throw 'It seems that the browser doesn\'t support MPEG media...';
};
var user, user_panel;
if (typeof is_login != 'undefined' && is_login == true) {
    user = $('.navi-panel-button').innerHTML;
    user_panel = $('.navi-panel-content').innerHTML
};
var setting = JSON.parse(localStorage.getItem('moefm-html5-setting')) || {},
audio = $('audio'),
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
playlist = [],
playlist_fetching = 0,
count = -1,
volume = setting.volume || 80,
next = 0,
url_data,
cover_retry = 0,
p = 0,
background_list = setting.background || [],
background_count_time,
background_count_time_value = 0,
audio_play = function(c) {
    if (c == null) {
        c = 1
    }
    if (count < playlist.length - 1) {
        count += c;
        audio.src = playlist[count].url;
        update_info();
        if (count > playlist.length - 5) {
            update_playlist(null, false)
        }
    } else {
        update_playlist(null, false)
    }
},
update_info = function() {
    cover_retry = 0;
    if (cover_preload.src != playlist[count].cover.large) {
        cover_loading_notification.style.opacity = 1
    }
    cover_preload.src = playlist[count].cover.large;
    if (c_like.hasAttribute('meow')) {
        c_like.removeAttribute('meow')
    }
    if (c_dislike.hasAttribute('meow')) {
        c_dislike.removeAttribute('meow')
    }
    if (location.search.indexOf('music=') >= 0 ? location.search.split('music=')[1].split('&')[0].indexOf(playlist[count].wiki_id) < 0 : (location.search.indexOf('song=') >= 0 ? location.search.split('song=')[1].split('&')[0].indexOf(playlist[count].sub_id) < 0 : location.search.indexOf('radio=') < 0)) {
        window.history.replaceState(null, '?song=' + playlist[count].sub_id, '?song=' + playlist[count].sub_id)
    }
    if (playlist[count].sub_title) {
        title.setAttribute('title', playlist[count].sub_title);
        title.innerHTML = playlist[count].sub_title;
        document.title = playlist[count].sub_title + ' | 萌否电台'
    } else {
        title.innerHTML = '';
        document.title = '收听音乐 | 萌否电台'
    }
    if (playlist[count].artist) {
        artist.innerHTML = playlist[count].artist;
        artist.setAttribute('title', playlist[count].artist)
    } else {
        artist.innerHTML = ''
    }
    if (playlist[count].wiki_title) {
        album.innerHTML = playlist[count].wiki_title;
        album.setAttribute('title', playlist[count].wiki_title)
    } else {
        album.innerHTML = ''
    }
    if (playlist[count].fav_sub) {
        if (playlist[count].fav_sub.fav_type == 1) {
            c_like.setAttribute('meow', '1')
        } else {
            c_dislike.setAttribute('meow', '1')
        }
    }
    if (playlist[count].fav_wiki) {
        if (playlist[count].fav_wiki.fav_type == 1) {
            album.innerHTML = '(♥) ' + playlist[count].wiki_title || '&nbsp;'
        }
    }
},
update_error = function(t, c) {
    var div = document.createElement('div');
    div.className = 'error_notification';
    switch (t) {
    case 'audio':
        context = '播放音频时发生错误<br>' + c;
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
    default:
        context = c
    }
    div.innerHTML = context;
    document.body.appendChild(div);
    div.style.opacity = 1;
    div.style.bottom = '30px';
    setTimeout(function() {
        div.style.opacity = 0;
        div.style.bottom = '0px';
        setTimeout(function() {
            div.parentElement.removeChild(div)
        }, 1000)
    }, 5000)
},
update_log = function() {
    if (typeof is_login != 'undefined' && is_login == true) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    if (JSON.parse(xhr.responseText).status == false) {
                        update_error('log', JSON.parse(xhr.responseText).msg)
                    }
                } else if (xhr.responseText) {
                    update_error('log', JSON.parse(xhr.responseText).response.error.message)
                } else {
                    update_error('log', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
                }
            }
        }
        xhr.open('GET', 'http://moe.fm/ajax/log?log_obj_type=sub&log_type=listen&obj_type=song&obj_id=' + playlist[count].sub_id + '&_=' + new Date().getTime());
        xhr.send()
    } else {
        update_error('log', '您尚未登录，请先登录......')
    }
},
update_fav = function(t, d) {
    if (typeof is_login != 'undefined' && is_login == true) {
        var xhr = new XMLHttpRequest(),
        f = new FormData();
        xhr.onreadystatechange = function() {
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
                                playlist[count].fav_sub = null;
                                break;
                            case 0:
                                c_like.setAttribute('meow', '1');
                                playlist[count].fav_sub = {};
                                playlist[count].fav_sub.fav_type = 1;
                                break
                            }
                            break;
                        case 2:
                            switch (d) {
                            case 1:
                                c_dislike.removeAttribute('meow');
                                playlist[count].fav_sub = null;
                                break;
                            case 0:
                                c_dislike.setAttribute('meow', '1');
                                playlist[count].fav_sub = {};
                                playlist[count].fav_sub.fav_type = 2;
                                break
                            }
                            break
                        }
                    }
                } else if (xhr.responseText) {
                    update_error('fav', JSON.parse(xhr.responseText).response.error.message)
                } else {
                    update_error('fav', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
                }
            }
        }
        f.append('fav_data[fav_type]', t);
        f.append('fav_data[obj_id]', playlist[count].sub_id);
        f.append('fav_data[obj_type]', 'song');
        f.append('fav_data[delete]', d);
        xhr.open('POST', 'http://moe.fm/ajax/fav?_=' + new Date().getTime());
        xhr.send(f)
    } else {
        update_error('fav', '您尚未登录，请先登录......')
    }
},
update_playlist = function(d, k) {
    if (playlist_fetching == 0) {
        var is_update = 1;
        if (d != null) {
            url_data = d;
            is_update = 0;
            count = -1;
            if (/\d+/.test(d)) p = 1
        }
        playlist_fetching = 1;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    if (JSON.parse(xhr.responseText).playlist) {
                        playlist_fetching = 0;
                        if (k == false && is_update == 1) {
                            for (var i = 0, j = JSON.parse(xhr.responseText).playlist; i < j.length; i++) {
                                playlist.push(j[i])
                            }
                            if (JSON.parse(xhr.responseText).info.may_have_next == true) {
                                p++
                            } else {
                                p = 0;
                                url_data = null
                            }
                        } else {
                            playlist = JSON.parse(xhr.responseText).playlist;
                            if (JSON.parse(xhr.responseText).info.may_have_next == true) {
                                p++
                            } else {
                                p = 0;
                                url_data = null
                            }
                        }
                        if (k != false) {
                            audio_play()
                        }
                    } else if (JSON.parse(xhr.responseText).response.error) {
                        update_error('playlist', JSON.parse(xhr.responseText).response.error.message)
                    }
                } else if (xhr.responseText) {
                    update_error('playlist', JSON.parse(xhr.responseText).response.error.message)
                } else {
                    update_error('playlist', 'XHR Ready State: ' + xhr.readyState + '<br>XHR Status: ' + xhr.statusText)
                }
            }
        }
        if (url_data == null) {
            xhr.open('GET', 'http://moe.fm/listen/playlist?share_buttons=1&perpage=30&_=' + new Date().getTime())
        } else {
            xhr.open('GET', 'http://moe.fm/listen/playlist?share_buttons=1&perpage=30&page=' + p + '&' + url_data + '&_=' + new Date().getTime())
        }
        xhr.send()
    }
},
update_volume_icon = function(v) {
    if (v > 66) {
        c_volume_icon.innerHTML = '大'
    } else if (v > 33) {
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
        }
        xhr.open('GET', 'http://ext.ccloli.com/moe-fm/html5/background/');
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
        },
        1000)
    } else {
        clearInterval(background_count_time)
    }
},
share = function() {
    var div = document.createElement('div'),
    div2 = document.createElement('div');
    div.className = 'share_panel';
    div2.className = 'share_panel_background';
    div2.title = '点击黑色区域以退出';
    div.innerHTML = '<button onclick="var p=prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\',\'' + playlist[count].sub_url + '#' + playlist[count].sub_title + ' | 萌否电台\');if(p!=null)window.open(\'' + playlist[count].sub_url + '\',\'_blank\')">复制当前曲目地址</button><button onclick="var p=prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\',\'' + playlist[count].wiki_url + '#' + playlist[count].wiki_title + ' | 萌否电台\');if(p!=null)window.open(\'' + playlist[count].wiki_url + '\',\'_blank\')">复制当前专辑地址</button><span class="share_buttons">' + playlist[count].share_buttons + '</span>';
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click', function() {
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2)
    })
};
audio.addEventListener('play', function() {
    c_play.setAttribute('hidden', 'hidden');
    c_pause.removeAttribute('hidden');
    update_info()
});
audio.addEventListener('pause', function() {
    c_pause.setAttribute('hidden', 'hidden');
    c_play.removeAttribute('hidden')
});
audio.addEventListener('timeupdate', function() {
    if (!isNaN(audio.duration)) {
        timeline_current.style.width = (audio.currentTime / audio.duration) * 100 + '%';
        if (audio.buffered.length > 0) {
            timeline_duration.style.width = (audio.buffered.end(audio.buffered.length - 1).toFixed(2)) / (audio.duration.toFixed(2)) * 100 + '%'
        }
        timeline_current_time.innerHTML = parseInt(audio.currentTime / 60) + ':' + (parseInt(audio.currentTime) % 60 < 10 ? '0' + parseInt(audio.currentTime) % 60 : parseInt(audio.currentTime) % 60);
        timeline_duration_time.innerHTML = parseInt(audio.duration / 60) + ':' + (parseInt(audio.duration) % 60 < 10 ? '0' + parseInt(audio.duration) % 60 : parseInt(audio.duration) % 60)
    }
});
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
        context = 'MEDIA_ERR_UNKNOWN（未知错误，错误代码：' + audio.error.code + '）'
    }
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
        context += '<br>NETWORK_UNKNOWN（未知错误，错误代码：' + audio.error.code + '）'
    }
    update_error('audio', context);
    audio_play()
});
audio.addEventListener('ended', function() {
    if (next == 0) update_log();
    else next = 0;
    audio_play()
});
cover_preload.addEventListener('load', function() {
    cover.style.backgroundImage = 'url(' + playlist[count].cover.large + ')';
    cover_loading_notification.style.opacity = 0
});
cover_preload.addEventListener('error', function() {
    if (cover_retry < 3) {
        update_error('cover', '正在重试加载......');
        cover_preload.src = playlist[count].cover.large;
        cover_retry++
    } else {
        update_error('cover', '超过最大重新加载次数限制');
        cover_retry = 0
    }
});
c_play.addEventListener('click', function() {
    audio.play()
});
c_pause.addEventListener('click', function() {
    audio.pause()
});
c_previous.addEventListener('click', function() {
    if (count > 0) {
        next = 1;
        count -= 2;
        audio_play()
    }
});
c_next.addEventListener('click', function() {
    next = 1;
    audio_play()
});
c_like.addEventListener('click', function() {
    if (c_like.hasAttribute('meow')) {
        update_fav(1, 1)
    } else {
        update_fav(1, 0)
    }
});
c_dislike.addEventListener('click', function() {
    if (c_dislike.hasAttribute('meow')) {
        update_fav(2, 1)
    } else {
        update_fav(2, 0)
    }
});
c_volume_icon.addEventListener('click', function() {
    if (c_volume_range.hasAttribute('disabled')) {
        c_volume_range.removeAttribute('disabled');
        c_volume_range.style.opacity = 1;
        audio.volume = volume / 100;
        update_volume_icon(volume)
    } else {
        volume = audio.volume * 100;
        c_volume_icon.innerHTML = '静';
        c_volume_range.setAttribute('disabled', 'disabled');
        c_volume_range.style.opacity = 0.75;
        audio.volume = 0
    }
});
c_volume_range.addEventListener('change', function() {
    volume = c_volume_range.value;
    audio.volume = volume / 100;
    update_volume_icon(volume);
    setting.volume = volume;
    localStorage.setItem('moefm-html5-setting', JSON.stringify(setting))
});
c_share.addEventListener('click', function() {
    share()
});
timeline.addEventListener('mouseup', function(event) {
    audio.currentTime = (event.clientX / document.body.clientWidth) * audio.duration
});
aside_album.addEventListener('click', function() {
    p = 0;
    update_playlist('fav=music')
});
aside_song.addEventListener('click', function() {
    p = 0;
    update_playlist('fav=song')
});
aside_radio.addEventListener('click', function() {
    p = 0;
    update_playlist('fav=radio')
});
aside_random.addEventListener('click', function() {
    p = 0;
    url_data = null;
    update_playlist()
});
background_preload.addEventListener('load', function() {
    document.documentElement.style.backgroundImage = 'url(' + background_preload.src + ')'
});
background_preload.addEventListener('error', function() {
    update_error('background', '获取图片时发生错误');
    update_background()
});
link_setting_background.addEventListener('click', function() {
    var div = document.createElement('div'),
    div2 = document.createElement('div'),
    t = document.createElement('textarea'),
    b = document.createElement('button');
    div.className = 'setting_background_panel';
    div2.className = 'setting_background_panel_background';
    div2.title = '点击黑色区域以退出';
    b.innerHTML = '确定';
    t.setAttribute('title', '请在文本框内输入图片地址，以回车间隔，一行一个');
    t.setAttribute('autofocus', 'autofocus');
    div.appendChild(t);
    div.appendChild(b);
    if (background_list.length != 0) t.value = background_list.join('\n');
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click', function() {
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2)
    })
    b.addEventListener('click', function() {
        var l = t.value.split('\n'),
        r = [];
        for (var i = 0; i < l.length; i++) {
            if (l != '') {
                r.push(l[i])
            }
        }
        background_list = r;
        div2.click();
        update_background();
        setting.background = r;
        localStorage.setItem('moefm-html5-setting', JSON.stringify(setting))
    })
});
if (location.search.indexOf('song') >= 0) {
    update_playlist(location.search.match(/song=[0-9,]*/)[0])
} else if (location.search.indexOf('music') >= 0) {
    update_playlist(location.search.match(/music=[0-9,]*/)[0])
} else if (location.search.indexOf('radio') >= 0) {
    update_playlist(location.search.match(/radio=[0-9,]*/)[0])
} else {
    update_playlist()
}
c_volume_range.value = volume;
audio.volume = volume / 100;
update_volume_icon(volume);
update_background();
update_background_count(1);
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
        break
    }
});
window.addEventListener('focus', function() {
    update_background_count(1)
});
window.addEventListener('blur', function() {
    update_background_count(0)
});
if (typeof is_login != 'undefined' && is_login == true) {
    var user_btn = document.createElement('span');
    user_btn.innerHTML = user;
    user_btn.className = 'link_right_user_btn';
    link_right_user.appendChild(user_btn);
    var user_pan = document.createElement('div');
    user_pan.innerHTML = user_panel;
    user_pan.style.cssText = 'position:fixed;background:rgba(0,0,0,0.5);bottom:0px;right:10px;opacity:0';
    user_pan.setAttribute('hidden', 'hidden');
    user_btn.appendChild(user_pan);
    user_btn.addEventListener('mouseover', function() {
        user_pan.removeAttribute('hidden');
        user_pan.style.opacity = 1;
        user_pan.style.bottom = '20px'
    })
    user_btn.addEventListener('mouseout', function() {
        user_pan.setAttribute('hidden', 'hidden');
        user_pan.style.opacity = 0;
        user_pan.style.bottom = '0px'
    })
} else {
    link_right_user.innerHTML = '<a class="right" target="_blank" href="http://moefou.org/register?redirect=http%3A%2F%2Fmoe.fm%2Flogin">注册</a><a class="right" href="http://moe.fm/login">登入</a>';
    document.getElementsByTagName('aside')[0].setAttribute('hidden', 'hidden')
}