
<!doctype html>
<!--
        __  __            ______ __  __    _    _ _______ __  __ _     _____    ____  Last updated_on 2014.06.11_
       |  \/  |          |  ____|  \/  |  | |  | |__   __|  \/  | |   |  ___|  |  _ \            |_|           | |
       | \  / | ___   ___| |__  | \  / |  | |__| |  | |  | \  / | |   | |__    | |_) | __  ___    _  ___  ___ _| |__
       | |\/| |/ _ \ / _ \  __| | |\/| |  |  __  |  | |  | |\/| | |   |___ \   | ___/ V _\/ _ \  | |/ _ \/ __|_   _/
       | |  | | (_) |  __/ |    | |  | |  | |  | |  | |  | |  | | |___ ___) |  | |  |  / | (_) | | |  __/ |__  | |
       |_|  |_|\___/ \___|_|    |_|  |_|  |_|  |_|  |_|  |_|  |_|_____|____/   |_|  |_|   \___/__| |\___/\___| | |__
       Just a Net Radio based on Moefou Open API // (c) 864907600cc (ccloli) // License: GPLv3 \___/   V 1.1   \___/
-->
<html>
<head>
<meta charset="utf-8">
<title>MoeFM HTML5 Project (Beta)</title>
<link id="favicon" href="http://moefou.org/public/images/fm/favicon.ico" rel="icon" type="image/x-icon">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo Yii::app()->baseUrl; ?>/assets/js/sha1.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/assets/js/oauth.js"></script>
<!--[if lt IE 10]>
<script>
alert('很抱歉，看起来您的浏览器版本过老了……\n请使用 IE 10 及以上版本，建议使用 IE 11');
window.location.href='http://moe.fm/listen'+window.location.search;
</script>
<![endif]-->
<style>
@font-face{
    font-family:moefm-html5-icomoon;
    src:url(<?php echo Yii::app()->baseUrl; ?>/assets/font/moefm-html5-icomoon.ttf?_=20140611)
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/assets/css/moefm-html5.css">
</head>

<body>
<section>
    <audio autoplay="true" class="audio"></audio>
    <div class="cover"><div class="control2"><span class="c_play" title="播放" hidden="hidden">播</span><span class="c_pause" title="暂停">停</span></div><img class="cover_preload" width="0" height="0"><div class="cover_loading_notification"></div></div>
    <div class="info">
        <ul>
            <li class="title">Title</li>
            <li class="artist">Artist</li>
            <li class="album">Album</li>
        </ul>
    </div>
    <div class="control"><!--<span class="c_play" title="播放">播</span><span class="c_pause" title="暂停" hidden="hidden">停</span>--><span class="c_previous" title="上一曲">上</span><span class="c_next" title="下一曲">下</span><span class="c_like" title="喜欢">藏</span><span class="c_dislike" title="抛弃">弃</span><span class="c_volume" title="音量"><span class="c_volume_icon">大</span><input class="c_volume_range" type="range"></span><span class="c_share" title="分享">享</span></div>
    <div class="timeline">
        <div class="timeline_duration"></div>
        <div class="timeline_current"></div>
        <div class="timeline_duration_time"></div>
        <div class="timeline_current_time"></div>
    </div>
</section>
<aside>
    <ul>
        <li class="aside_album">我收藏的专辑</li>
        <li class="aside_song">我喜欢的曲目</li>
        <li class="aside_radio">我收藏的电台</li>
        <li class="aside_random">魔力播放</li>
    </ul>
</aside>
<footer>
    <div class="link_left"><a href="http://moe.fm/" target="_blank">电台首页</a><a href="http://moefm.ccloli.com">开始聆听</a><a href="http://moe.fm/explore" target="_blank">发现音乐</a><a href="http://moe.fm/about/client" target="_blank">客户端</a><a href="http://moefou.org/group/moefm" target="_blank">电台小组</a></div>
    <div class="link_right">
        <!--<span class="link_setting">设置</span>-->
        <span class="link_about">关于本站</span>
        <span class="link_setting_background">设置背景</span>
        <div class="link_right_user"></div>
    </div>
    <img class="background_preload" width="0" height="0">
</footer>

<script>
var audio_pretest=document.createElement('audio');
if(audio_pretest.canPlayType('audio/mpeg')==''){
    alert('很抱歉，看起来您的浏览器不支持 MPEG (MP3) 文件……\n支持 MPEG 编码的浏览器请参考 http://caniuse.com/#feat=mpeg4');
    window.location.href='http://moe.fm/listen'+window.location.search;
    throw 'It seems that the browser doesn\'t support MPEG media...';
}

var setting=JSON.parse(localStorage.getItem('moefm-html5-setting'))||{},
    audio=document.getElementsByClassName('audio')[0],
    cover=document.getElementsByClassName('cover')[0],
    cover_preload=document.getElementsByClassName('cover_preload')[0],
    cover_loading_notification=document.getElementsByClassName('cover_loading_notification')[0],
    title=document.getElementsByClassName('title')[0],
    artist=document.getElementsByClassName('artist')[0],
    album=document.getElementsByClassName('album')[0],
    c_play=document.getElementsByClassName('c_play')[0],
    c_pause=document.getElementsByClassName('c_pause')[0],
    c_previous=document.getElementsByClassName('c_previous')[0],
    c_next=document.getElementsByClassName('c_next')[0],
    c_like=document.getElementsByClassName('c_like')[0],
    c_dislike=document.getElementsByClassName('c_dislike')[0],
    c_volume=document.getElementsByClassName('c_volume')[0],
    c_volume_icon=document.getElementsByClassName('c_volume_icon')[0],
    c_volume_range=document.getElementsByClassName('c_volume_range')[0],
    c_share=document.getElementsByClassName('c_share')[0],
    timeline=document.getElementsByClassName('timeline')[0],
    timeline_duration=document.getElementsByClassName('timeline_duration')[0],
    timeline_current=document.getElementsByClassName('timeline_current')[0],
    timeline_duration_time=document.getElementsByClassName('timeline_duration_time')[0],
    timeline_current_time=document.getElementsByClassName('timeline_current_time')[0],
    link_right_user=document.getElementsByClassName('link_right_user')[0],
    aside_album=document.getElementsByClassName('aside_album')[0],
    aside_song=document.getElementsByClassName('aside_song')[0],
    aside_radio=document.getElementsByClassName('aside_radio')[0],
    aside_random=document.getElementsByClassName('aside_random')[0],
    background_preload=document.getElementsByClassName('background_preload')[0],
    link_setting_background=document.getElementsByClassName('link_setting_background')[0],
    link_about=document.getElementsByClassName('link_about')[0],
    playlist=[],
    playlist_fetching=0,
    count=-1,
    volume=setting.volume||80,
    next=0,
    url_data,
    cover_retry=0,
    login_retry=0,
    p=0,
    background_list=setting.background||[],
    background_count_time,
    background_count_time_value=0,
    loop=0,
    is_login=false,
    // Thanks to QB
    generateOauthUrl = function (url, options) {
        var accessor = {
            consumerKey: options.consumerKey,
            consumerSecret: options.consumerSecret
        };
        if ('token' in options) {
            accessor['token'] = options.token;
            accessor['tokenSecret'] = options.tokenSecret;
        }
        var message = {
            action: url,
            method: options.method,
            parameters: {}
        };
        if ('verifier' in options) {
            message.parameters['oauth_verifier'] = options.verifier;
        }
        if('callback' in options){
            message.parameters['oauth_callback'] = options.callback;
        }
        OAuth.completeRequest(message, accessor);
        OAuth.SignatureMethod.sign(message, accessor);
        return url + (url.indexOf('?')>=0?'&':'?') + OAuth.formEncode(message.parameters);
    },
    ck = '\u0065\u0064\u0039\u0038\u0034\u0066\u0038\u0062\u0036\u0063\u0063\u0065\u0035\u0066\u0030\u0064\u0032\u0038\u0066\u0038\u0064\u0062\u0037\u0063\u0066\u0030\u0034\u0066\u0038\u0034\u0035\u0065\u0030\u0035\u0034\u0033\u0032\u0064\u0064\u0033\u0061',
    cs = '\u0062\u0066\u0030\u0066\u0063\u0039\u0062\u0066\u0064\u0030\u0030\u0066\u0038\u0031\u0066\u0030\u0061\u0064\u0064\u0035\u0065\u0035\u0061\u0064\u0062\u0038\u0031\u0064\u0035\u0066\u0032\u0030',
    accessToken,
    accessTokenSecret,
    options;

/*if(audio.canPlayType('audio/mpeg')==''){
    alert('很抱歉，看起来您的浏览器不支持 MPEG (MP3) 文件……\n支持 MPEG 编码的浏览器请参考 http://caniuse.com/#feat=mpeg4');
    //alert('Sorry, but seems that your browser doesn\'t support MPEG audio (mp3 file)...\nThe list of browsers which support MPEG could be seen at http://caniuse.com/#feat=mpeg4\nThe window will be closed...');
    window.close();
}*/
function cookie(g, f, h) {
    if (typeof f != 'undefined') {
        h = h || 31536000;
        var j = new Date();
        j.setTime(j.getTime() + h * 1000);
        b.cookie = g + '=' + escape(f) + ';expires=' + j.toGMTString();
    } else {
        var e = b.cookie.match(new RegExp('(^| )' + g + '=([^;]*)(;|$)'));
        return e == null ? null: unescape(e[2]);
    }
}
function audio_play(c){
    if(c==null)c=1;
    if(count<playlist.length-1){
        count+=c;
        audio.src=playlist[count].url;
        update_info();
        if(count>playlist.length-5)update_playlist(null,false);
        //if(_check_music==true)check_file_exist(playlist[count+1].url);
        //else if(_preload_music==true)preload_music(playlist[count+1].url);
    }
    else if(loop==1){
        count=0;
        audio.src=playlist[count].url;
        update_info();
    }
    else update_playlist(null,false);
}
function update_info(){
    cover_retry=0;
    if(cover_preload.src!=playlist[count].cover.large){
        cover_loading_notification.style.opacity=1;
    }
    cover_preload.src=playlist[count].cover.large;
    if(c_like.hasAttribute('meow'))c_like.removeAttribute('meow');
    if(c_dislike.hasAttribute('meow'))c_dislike.removeAttribute('meow');
    if(location.search.indexOf('music=')>=0?location.search.split('music=')[1].split('&')[0].indexOf(playlist[count].wiki_id)<0:
        (location.search.indexOf('song=')>=0?location.search.split('song=')[1].split('&')[0].indexOf(playlist[count].sub_id)<0:
        location.search.indexOf('radio=')<0)){
            window.history.replaceState(null,'','?song='+playlist[count].sub_id);
    }
    if(playlist[count].sub_title){
        title.setAttribute('title',playlist[count].sub_title);
        title.innerHTML=playlist[count].sub_title;
        document.title=decodeURIComponent(playlist[count].sub_title.replace(/(^\s*)|(\s*$)/g,''))+' | 萌否电台';
    }
    else{
        title.innerHTML='';
        document.title='收听音乐 | 萌否电台';
    }
    if(playlist[count].artist){
        artist.innerHTML=playlist[count].artist;
        artist.setAttribute('title',playlist[count].artist);
    }
    else artist.innerHTML='';
    if(playlist[count].wiki_title){
        album.innerHTML=playlist[count].wiki_title;
        album.setAttribute('title',playlist[count].wiki_title);
    }
    else album.innerHTML='';
    if(playlist[count].fav_sub){
        if(playlist[count].fav_sub.fav_type==1)c_like.setAttribute('meow','1');
        else c_dislike.setAttribute('meow','1');
    }
    if(playlist[count].fav_wiki){
        if(playlist[count].fav_wiki.fav_type==1)album.innerHTML='(♥) '+playlist[count].wiki_title||'&nbsp;';
    }
}
function update_error(t,c){
    var div=document.createElement('div'),
        context;
    div.className='error_notification';
    switch(t){
        case 'audio':
            context='播放音频时发生错误<br>'+c;
            break;
        case 'log':
            context='记录播放历史失败<br>'+c;
            break;
        case 'fav':
            context='添加收藏/抛弃记录失败<br>'+c;
            break;
        case 'playlist':
            context='获取播放列表失败<br>'+c;
            break;
        case 'cover':
            context='获取专辑图片失败<br>'+c;
            break;
        case 'background':
            context='获取背景图片失败<br>'+c;
            break;
        case 'login':
            context='获取登录数据失败<br>'+c;
            break;
        default:
            context='Seems that something lovely goes wrong...<br>'+c;
    }
    div.innerHTML=context;
    document.body.appendChild(div);
    div.style.opacity=1;
    div.style.bottom='30px';
    setTimeout(function(){
        div.style.opacity=0;
        div.style.bottom='0px';
        setTimeout(function(){
            div.parentElement.removeChild(div);
        },1000);
    },5000);
}
function update_log(){
    if(is_login==true){
        var xhr=new XMLHttpRequest(),
            options={
                method:'get',
                consumerKey: ck,
                consumerSecret: cs,
                token:accessToken,
                tokenSecret:accessTokenSecret,
                //obj_id:playlist[count].sub_id
            },
            url='http://moe.fm/ajax/log?api=json&log_obj_type=sub&log_type=listen&obj_type=song&obj_id='+playlist[count].sub_id+'&_='+new Date().getTime();
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    if(JSON.parse(xhr.responseText).status==false){
                        update_error('log',JSON.parse(xhr.responseText).msg);
                    }
                }
                else if(xhr.responseText){
                    update_error('log',JSON.parse(xhr.responseText).response.error.message);
                }
                else{
                    update_error('log','XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                }
            }
        }
        xhr.open('GET',generateOauthUrl(url,options));
        xhr.send();
    }
}
function update_fav(t,d){
    var xhr=new XMLHttpRequest(),
        url='http://api.moefou.org/fav/'+(d==0?'add':'delete')+'.json?fav_type='+t+'&fav_obj_type=song&fav_obj_id='+playlist[count].sub_id+'&_='+new Date().getTime(),
        options={
            method:'get',
            consumerKey: ck,
            consumerSecret: cs,
            token:accessToken,
            tokenSecret:accessTokenSecret,
            //fav_type:d,
            //fav_obj_type:'song',
            //fav_obj_id:playlist[count].sub_id
        };
    xhr.onreadystatechange=function(){
        if(xhr.readyState==4){
            if(xhr.status==200){
                if(JSON.parse(xhr.responseText).status==false){
                    update_error('fav',JSON.parse(xhr.responseText).msg);
                }
                else{
                    switch(t){
                        case 1:
                            switch (d){
                                case 1:
                                    c_like.removeAttribute('meow');
                                    playlist[count].fav_sub=null;
                                    break;
                                case 0:
                                    c_like.setAttribute('meow','1');
                                    playlist[count].fav_sub={};
                                    playlist[count].fav_sub.fav_type=1;
                                    break;
                            }
                            break;
                        case 2:
                            switch (d){
                                case 1:
                                    c_dislike.removeAttribute('meow');
                                    playlist[count].fav_sub=null;
                                    break;
                                case 0:
                                    c_dislike.setAttribute('meow','1');
                                    playlist[count].fav_sub={};
                                    playlist[count].fav_sub.fav_type=2;
                                    break;
                            }
                            break;
                    }
                }
            }
            else if(xhr.responseText){
                update_error('fav',JSON.parse(xhr.responseText).response.error.message);
            }
            else{
                update_error('fav','XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
            }
        }
    }
    xhr.open('GET',generateOauthUrl(url,options));
    xhr.send();
}
function update_playlist(d,k,m){
    if(playlist_fetching==0){
        var is_update=1;
        if(d!=null){
            url_data=d;
            is_update=0;
            count=-1;
            if(/\d+/.test(d))p=1;
        }
        //if(m!=null&&m==true)is_update=0;
        playlist_fetching=1;
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    if(JSON.parse(xhr.responseText).playlist){
                            playlist_fetching=0;
                            //count=-1;
                            if(k==false&&is_update==1){
                                //playlist=/*playlist.concat(*/JSON.parse(xhr.responseText).playlist/*)*/;
                                for(var i=0,j=JSON.parse(xhr.responseText).playlist;i<j.length;i++){playlist.push(j[i]);}
                                if(JSON.parse(xhr.responseText).info.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                                //count=-1;

                            }
                            else{
                                playlist=JSON.parse(xhr.responseText).playlist;
                                if(JSON.parse(xhr.responseText).info.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                                //count=-1;
                            }
                            if(k!=false){
                                audio_play();
                            }
                            /*playlist=JSON.parse(xhr.responseText).playlist;
                            playlist_fetching=0;
                            count=-1;
                            if(p!=0){
                                if(JSON.parse(xhr.responseText).info.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                            }
                            audio_play();*/
                    }
                    else if(JSON.parse(xhr.responseText).response.playlist){
                            playlist_fetching=0;
                            //count=-1;
                            /*if(p!=0){
                                playlist=JSON.parse(xhr.responseText).response.playlist;
                                count=-1;
                                if(JSON.parse(xhr.responseText).response.information.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                            }
                            else{
                                playlist=JSON.parse(xhr.responseText).response.playlist;
                                count=-1;
                            }
                            audio_play();*/
                            if(k==false&&is_update==1){
                                //playlist=/*playlist.concat(*/JSON.parse(xhr.responseText).playlist/*)*/;
                                for(var i=0,j=JSON.parse(xhr.responseText).response.playlist;i<j.length;i++){playlist.push(j[i]);}
                                if(JSON.parse(xhr.responseText).response.information.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                                //count=-1;

                            }
                            else{
                                playlist=JSON.parse(xhr.responseText).response.playlist;
                                if(JSON.parse(xhr.responseText).response.information.may_have_next==true)p++;
                                else{
                                    p=0;
                                    url_data=null;
                                }
                                //count=-1;
                            }
                            if(k!=false){
                                audio_play();
                            }
                    }
                    else if(JSON.parse(xhr.responseText).response.error){
                        update_error('playlist',JSON.parse(xhr.responseText).response.error.message);
                    }
                }
                else if(xhr.status==401){
                    check_login();
                    is_login=false;
                    playlist_fetching=0;
                    update_playlist(d);
                }
                else if(xhr.responseText){
                    playlist_fetching=0;
                    update_error('playlist',JSON.parse(xhr.responseText).response.error.message);
                }
                else{
                    playlist_fetching=0;
                    update_error('playlist','XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                }
            }
        }
        if(is_login==true){
            var options={
                    method:'get',
                    consumerKey: ck,
                    consumerSecret: cs,
                    token:accessToken,
                    tokenSecret:accessTokenSecret
                };
            if(url_data==null)var url='http://moe.fm/listen/playlist?api=json&share_buttons=1&perpage=30&_='+new Date().getTime();
            else var url='http://moe.fm/listen/playlist?api=json&share_buttons=1&perpage=30&page='+p+'&'+url_data+'&_='+new Date().getTime();

            console.log(url);
            console.log(generateOauthUrl(url,options));
            xhr.open('GET',generateOauthUrl(url,options));
        }
        else{
            if(url_data==null)xhr.open('GET','http://moe.fm/listen/playlist?share_buttons=1&perpage=30&_='+new Date().getTime());
            else xhr.open('GET','http://moe.fm/listen/playlist?share_buttons=1&perpage=30&page='+p+'&'+url_data+'&_='+new Date().getTime());
        }
        xhr.send();
    }
}
function update_volume_icon(v){
    if(v>66)c_volume_icon.innerHTML='大';
    else if(v>33)c_volume_icon.innerHTML='中';
    else c_volume_icon.innerHTML='小';
}
function update_background(){
    if(background_list.length==0){
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    background_list=JSON.parse(xhr.responseText).background_list;
                    update_background();
                }
                else{
                    update_error('background','获取背景图片列表失败<br>XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                }
            }
        }
        xhr.open('GET','http://moefm.ccloli.com/background/');
        xhr.send();
    }
    else{
        var num=parseInt(Math.random()*(background_list.length-1));
        background_preload.src=background_list[num];
    }
}
function update_background_count(v){
    if(v==1){
        background_count_time=setInterval(function(){
            if(background_count_time_value>=60){
                update_background();
                background_count_time_value=0;
            }
            else{
                background_count_time_value++;
            }
        },1000);
    }
    else clearInterval(background_count_time);
}
function share(){
    var div=document.createElement('div'),
        div2=document.createElement('div');
    div.className='share_panel';
    div2.className='share_panel_background';
    div2.title='点击黑色区域以退出';
    div.innerHTML='<button onclick="var p=prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\',\''+playlist[count].sub_url+'#'+playlist[count].sub_title+' | 萌否电台\');if(p!=null)window.open(\''+playlist[count].sub_url+'\',\'information\')">复制当前曲目地址</button><button onclick="var p=prompt(\'请按下 Ctrl + C 以复制，点击确定可跳转至该页面，点击取消返回。\',\''+playlist[count].wiki_url+'#'+playlist[count].wiki_title+' | 萌否电台\');if(p!=null)window.open(\''+playlist[count].wiki_url+'\',\'information\')">复制当前专辑地址</button><span class="share_buttons">'+playlist[count].share_buttons+'</span>';
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click',function(){
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2);
    })
}
function set_login(){
    link_right_user.innerHTML='<a class="right" target="_blank" href="http://moefou.org/register?redirect=http%3A%2F%2Fi.loli-yuki.tk">萌否注册</a><a class="right">萌否登入</a><a class="right wb">微博登录</a>';
    document.getElementsByTagName('aside')[0].setAttribute('hidden','hidden');
    link_right_user.getElementsByTagName('a')[1].addEventListener('click',function(){login()})
    link_right_user.getElementsByTagName('a')[2].addEventListener('click',function(){wb_login()})
    //start();
}
function check_login(){
    accessToken=localStorage.getItem('accessToken');
    accessTokenSecret=localStorage.getItem('accessTokenSecret');
    link_right_user.innerHTML='正在获取用户信息......';
    var xhr=new XMLHttpRequest(),
        url='http://api.moefou.org/user/detail.json',
        options={
            method:'get',
            consumerKey: ck,
            consumerSecret: cs,
            token:accessToken,
            tokenSecret:accessTokenSecret
        };
    xhr.onreadystatechange=function(){
        if(xhr.readyState==4){
            if(xhr.status==200){
                is_login=true;
                var data=JSON.parse(xhr.responseText).response.user;
                link_right_user.innerHTML='';
                var user_btn=document.createElement('span');
                user_btn.innerHTML=data.user_nickname;
                user_btn.className='link_right_user_btn';
                link_right_user.appendChild(user_btn);
                var user_pan=document.createElement('div');
                user_pan.innerHTML='<div style="padding:6px"><div style="float:left"><img class="avatar" style="width:48px;height:48px" src="'+data.user_avatar.small+'" alt=""></div><div style="padding-left:6px;margin-left:48px;width:108px"><a title="个人主页" href="'+data.user_fm_url+'">我的主页</a><a target="_blank" class="external" href="http://moefou.org/user/setting">个人设定</a><a onclick="logout()">登出</a><div style="clear:both"></div></div><div style="clear:both"></div></div>';
                user_pan.style.cssText='position:fixed;background:rgba(0,0,0,0.5);bottom:0px;right:10px;opacity:0';
                user_pan.setAttribute('hidden','hidden');
                user_btn.appendChild(user_pan);
                user_btn.addEventListener('mouseover',function(){
                    user_pan.removeAttribute('hidden');
                    user_pan.style.opacity=1;
                    user_pan.style.bottom='20px';
                })
                user_btn.addEventListener('mouseout',function(){
                    user_pan.setAttribute('hidden','hidden');
                    user_pan.style.opacity=0;
                    user_pan.style.bottom='0px';
                })
                if(document.getElementsByTagName('aside')[0].hasAttribute('hidden'))document.getElementsByTagName('aside')[0].removeAttribute('hidden');
                if(playlist.length==0)start();
                /*options={
                    method:'get',
                    consumerKey: ck,
                    consumerSecret: cs,
                    token:accessToken,
                    tokenSecret:accessTokenSecret
                }*/
                /*if(playlist.length!=0){
                    for(var i=0;i<playlist.length;i++){

                    }*/
                //update_playlist(null,false,true)
                //}
            }
            else if(xhr.status==401){
                update_error('login','用户信息验证失败，可能是因为您曾经取消授权，请尝试重新授权登录。');
                set_login();
                start();
            }
            else{
                login_retry++;
                if(login_retry<3){
                    update_error('login','无法获取用户信息，可能是网络问题或服务器故障，正在尝试重新连接......<br>XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                    check_login();
                }
                else{
                    update_error('login','无法获取用户信息，可能是网络问题或服务器故障，请稍候刷新重试......<br>XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                    set_login();
                }
            }
        }
    }
    xhr.open('GET',generateOauthUrl(url,options));
    xhr.send();
}
function login(){
    var div=document.createElement('div'),
        div2=document.createElement('div');
    div.className='login_panel';
    div2.className='login_panel_background';
    div2.title='点击黑色区域以退出';
    div.innerHTML='您即将使用您的萌否账号授权登录本站点，授权过程均在萌否服务器完成，本站点不会记录您的密码，但您授权后的 access token 数据将以可能不安全的方式储存在浏览器中。<br>如果您之前已授权却仍被要求重新授权，那么可能是您目前在另一台计算机上，或您曾经清除过浏览器数据集而误清理了 access token。<br><button class="login_confirm">我已了解，开始授权</button>';
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click',function(){
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2);
    })
    div.getElementsByClassName('login_confirm')[0].addEventListener('click',function(){
        div.innerHTML='正在拉取授权数据，请耐心等待......';
        var url='http://api.moefou.org/oauth/request_token',
            options={
                method: 'get',
                consumerKey: ck,
                consumerSecret: cs,
                callback: '<?php echo Yii::app()->request->hostinfo.$this->createUrl('moefmcallback'); ?>'
            },
            xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4){
                if(xhr.status==200){
                    var data=xhr.responseText,
                        rt=data.replace(/.*\boauth_token=([a-z0-9]+).*/, '$1'),
                        rs=data.replace(/.*\boauth_token_secret=([a-z0-9]+).*/, '$1'),
                        url='http://api.moefou.org/oauth/authorize',
                        options={
                            method:'get',
                            consumerKey:ck,
                            consumerSecret:cs,
                            token:rt,
                            tokenSecret:rs
                        },
                        re_url=generateOauthUrl(url, options);
                    div.innerHTML='请在新弹出的页面中完成授权，完成授权后请复制萌否开放平台提供的验证码，并粘贴于下面的文本框中。<br>如果浏览器未弹出授权页面，请手动<a href="'+re_url+'" target="_blank">点击此处</a>打开授权页面。<br>请于 1 小时内完成授权。<br><input type="text" placeholder="请在此输入验证码" class="login_verifier"></input><br><button class="login_confirm_last">确定</button>';
                    div.getElementsByClassName('login_confirm_last')[0].addEventListener('click',function(){
                        var url='http://api.moefou.org/oauth/access_token',
                            options={
                                method:'get',
                                consumerKey:ck,
                                consumerSecret:cs,
                                token:rt,
                                tokenSecret:rs,
                                verifier:div.getElementsByClassName('login_verifier')[0].value
                            }
                            xhr2=new XMLHttpRequest();
                        xhr2.onreadystatechange=function(){
                            if(xhr2.readyState==4){
                                if(xhr2.status==200){
                                    var data=xhr2.responseText,
                                        accessToken=data.replace(/.*\boauth_token=([a-z0-9]+).*/,'$1'),
                                        accessTokenSecret=data.replace(/.*\boauth_token_secret=([a-z0-9]+).*/,'$1');
                                    localStorage.setItem('accessToken',accessToken);
                                    localStorage.setItem('accessTokenSecret',accessTokenSecret);
                                    check_login();
                                    div.parentElement.removeChild(div);
                                    div2.parentElement.removeChild(div2);
                                }
                                else update_error('login','XHR Ready State: '+xhr2.readyState+'<br>XHR Status: '+xhr2.statusText);
                            }
                        }
                        xhr2.open('GET',generateOauthUrl(url,options));
                        xhr2.send();
                    })
                    window.open(re_url,'authorized');
                }
                else{
                    update_error('login','XHR Ready State: '+xhr.readyState+'<br>XHR Status: '+xhr.statusText);
                }
            }
        }
        xhr.open('GET',generateOauthUrl(url,options));
        xhr.send();
    })
}
function wb_login(){
    window.open('<?php echo $wburl; ?>', 'authorized');
    return false
}
function wb_check(){
    weibojs = 'weibojs_<?php echo Yii::app()->params['saet_api_key']; ?>';
    if (typeof cookie(weibojs) !== 'undefined') {
        console.log(cookie(weibojs))
    } else {
        console.log('未找到')
    }
}
function logout(){
    var c=confirm('您即将登出，在下次登录时需要重新授权，是否继续？');
    if(c==true){
        localStorage.removeItem('accessToken');
        localStorage.removeItem('accessTokenSecret');
        set_login();
        var c=confirm('数据已清除，您已成功登出本站。\n是否需要前往萌否开放平台取消授权？');
        if(c==true)window.open('http://open.moefou.org/apps/authorized','authorized');
    }
}
function about(){
    var div=document.createElement('div'),
        div2=document.createElement('div');
    div.className='about_panel';
    div2.className='about_panel_background';
    div2.title='点击黑色区域以退出';
    div.innerHTML='<strong>MoeFM HTML5 Project (Beta)</strong><br>萌否电台 HTML5 版本（非官方）<br>作者：<a href="http://moefou.org/home/864907600cc" target="_blank">864907600cc</a><br>致谢：<a href="http://blog.likelikeslike.com/" target="_blank">Jak Wings</a>（提供萌否 OAuth 认证 example）、<a href="http://moefou.org/home/zanko" target="_blank">zanko</a>（提供 API 使用支持）<br>桌面设备测试：Chrome 32/33 (Windows 7 64-bit/Ubuntu 13.10 64-bit)、Firefox 25 (Windows 7 64-bit)、Internet Explorer (Windows 7 64-bit)<br>移动设备测试：Android 自带浏览器（Android 4.1/4.3）、UC 浏览器（Android 2.2/4.3，不完全支持）、海豚浏览器（Android 4.3）<br>Powered by <a href="http://moe.fm" target="_blank">Moe.FM</a> | Hosted on <a href="https://www.openshift.com">OpenShift</a><br><p><a href="http://moefou.org/group/moefm_html5_project" target="_blank">项目小组</a> <a href="http://moefou.org/topic/1730" target="_blank">Bug 反馈</a></p>';
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click',function(){
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2);
    })
}
function 穿越OAO(){
    audio.pause();
    var div=document.createElement('div'),
        div2=document.createElement('div');
    div.className='穿越_panel';
    div2.className='穿越_panel_background';
    div2.title='点击黑色区域以退出';
    div.innerHTML='扫描二维码，在移动设备上继续收听，无需重新登录<br><img src="http://moefm.ccloli.com/qr.php?data='+encodeURIComponent('http://moefm.ccloli.com/?song='+playlist[count].sub_id+'#'+(accessToken!=null?('accessToken='+accessToken+',accessTokenSecret='+accessTokenSecret)+',':'')+'currentTime='+audio.currentTime)+'" alt="" width="180" height="180">';
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click',function(){
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2);
        audio.play();
    })
}
function start(){
    if(location.search.indexOf('song')>=0)update_playlist(location.search.match(/song=[0-9,]*/)[0]);
    else if(location.search.indexOf('music')>=0)update_playlist(location.search.match(/music=[0-9,]*/)[0]);
    else if(location.search.indexOf('radio')>=0)update_playlist(location.search.match(/radio=[0-9,]*/)[0]);
    else update_playlist();
}

audio.addEventListener('play',function(){
    c_play.setAttribute('hidden','hidden');
    c_pause.removeAttribute('hidden');
    update_info();
})
audio.addEventListener('pause',function(){
    c_pause.setAttribute('hidden','hidden');
    c_play.removeAttribute('hidden');
})
audio.addEventListener('timeupdate',function(){
    if(!isNaN(audio.duration)){
        timeline_current.style.width=(audio.currentTime/audio.duration)*100+'%';
        if(audio.buffered.length>0)timeline_duration.style.width=(audio.buffered.end(audio.buffered.length-1).toFixed(2))/(audio.duration.toFixed(2))*100+'%';
        timeline_current_time.innerHTML=parseInt(audio.currentTime/60)+':'+(parseInt(audio.currentTime)%60<10?'0'+parseInt(audio.currentTime)%60:parseInt(audio.currentTime)%60);
        timeline_duration_time.innerHTML=parseInt(audio.duration/60)+':'+(parseInt(audio.duration)%60<10?'0'+parseInt(audio.duration)%60:parseInt(audio.duration)%60);
    }
})
audio.addEventListener('error',function(){
    var context;
    switch (audio.error.code){
        case 1:
            context='MEDIA_ERR_ABORTED（文件在取回时被用户中止）';
            break;
        case 2:
            context='MEDIA_ERR_NETWORK（文件在下载时发生错误）';
            break;
        case 3:
            context='MEDIA_ERR_DECODE（文件在解码时发生错误）';
            break;
        case 4:
            context='MEDIA_ERR_SRC_NOT_SUPPORTED（不支持的音频格式）';
            break;
        default:
            context='MEDIA_ERR_UNKNOWN（未知错误，错误代码：'+audio.error.code+'）';
    }
    switch (audio.networkState){
        case 0:
            context+='<br>NETWORK_EMPTY（音频尚未初始化）';
            break;
        case 1:
            context+='<br>NETWORK_IDLE（音频已缓存）';
            break;
        case 2:
            context+='<br>NETWORK_LOADING（浏览器正在下载数据）';
            break;
        case 3:
            context+='<br>NETWORK_NO_SOURCE（未找到音频来源）';
            break;
        default:
            context+='NETWORK_UNKNOWN（未知错误，错误代码：'+audio.error.code+'）';
    }
    update_error('audio',context);
    audio_play();
})
audio.addEventListener('ended',function(){
    if(next==0)update_log();
    else next=0;
    audio_play();
})
cover_preload.addEventListener('load',function(){
    cover.style.backgroundImage='url('+playlist[count].cover.large+')';
    cover_loading_notification.style.opacity=0;
})
cover_preload.addEventListener('error',function(){
    if(cover_retry<3){
        update_error('cover','正在重试加载......');
        cover_preload.src=playlist[count].cover.large;
        cover_retry++;
    }
    else{
        update_error('cover','超过最大重新加载次数限制');
        cover_retry=0;
    }
})
c_play.addEventListener('click',function(){audio.play();})
c_pause.addEventListener('click',function(){audio.pause();})
c_previous.addEventListener('click',function(){
    if(count>0){
        next=1;
        count-=2;
        audio_play();
    }
})
c_next.addEventListener('click',function(){
    next=1;
    audio_play();
})
c_like.addEventListener('click',function(){
    if(c_like.hasAttribute('meow')){
        update_fav(1,1);
    }
    else{
        update_fav(1,0);
    }
})
c_dislike.addEventListener('click',function(){
    if(c_dislike.hasAttribute('meow')){
        update_fav(2,1);
    }
    else{
        update_fav(2,0);
    }
})
c_volume_icon.addEventListener('click',function(){
    if(c_volume_range.hasAttribute('disabled')){
        c_volume_range.removeAttribute('disabled');
        c_volume_range.style.opacity=1;
        audio.volume=volume/100;
        update_volume_icon(volume);
    }
    else{
        volume=audio.volume*100;
        c_volume_icon.innerHTML='静';
        c_volume_range.setAttribute('disabled','disabled');
        c_volume_range.style.opacity=0.75;
        audio.volume=0;
    }
})
c_volume_range.addEventListener('change',function(){
    volume=c_volume_range.value;
    audio.volume=volume/100;
    update_volume_icon(volume);
    setting.volume=volume;
    localStorage.setItem('moefm-html5-setting',JSON.stringify(setting));
})
c_share.addEventListener('click',function(){share();})
timeline.addEventListener('mouseup',function(event){audio.currentTime=(event.clientX/document.body.clientWidth)*audio.duration;})
aside_album.addEventListener('click',function(){p=0;update_playlist('fav=music');})
aside_song.addEventListener('click',function(){p=0;update_playlist('fav=song');})
aside_radio.addEventListener('click',function(){p=0;update_playlist('fav=radio');})
aside_random.addEventListener('click',function(){p=0;url_data=null;update_playlist();})
background_preload.addEventListener('load',function(){document.documentElement.style.backgroundImage='url('+background_preload.src+')';})
background_preload.addEventListener('error',function(){update_error('background','获取背景图片时发生错误');})
link_setting_background.addEventListener('click',function(){
    var div=document.createElement('div'),
        div2=document.createElement('div'),
        t=document.createElement('textarea'),
        b=document.createElement('button');
    div.className='setting_background_panel';
    div2.className='setting_background_panel_background';
    div2.title='点击黑色区域以退出';
    b.innerHTML='确定';
    t.setAttribute('title','请在文本框内输入图片地址，以回车间隔，一行一个');
    t.setAttribute('autofocus','autofocus');
    div.appendChild(t);
    div.appendChild(b);
    if(background_list.length!=0)t.value=background_list.join('\n');
    document.body.appendChild(div);
    document.body.appendChild(div2);
    div2.addEventListener('click',function(){
        div.parentElement.removeChild(div);
        div2.parentElement.removeChild(div2);
    })
    b.addEventListener('click',function(){
        var l=t.value.split('\n'),
            r=[];
        for(var i=0;i<l.length;i++){
            if(l!='')r.push(l[i]);
        }
        background_list=r;
        div2.click();
        update_background();
        setting.background=r;
        localStorage.setItem('moefm-html5-setting',JSON.stringify(setting));
    })
})
link_about.addEventListener('click',function(){about();})
album.addEventListener('click',function(){
    if(confirm('播放专辑『'+playlist[count].wiki_title+'』？')==true)update_playlist('music='+playlist[count].wiki_id);
})
window.addEventListener('keydown',function(e){
    switch(e.keyCode){
        case 32:
            audio.paused==false?audio.pause():audio.play();
            break;
        case 39:
            next=1;
            audio_play();
            break;
        case 38:
            c_like.click();
            break;
        case 40:
            c_dislike.click();
            break;
        case 37:
            next=1;
            count-=2;
            audio_play();
            //穿越OAO();
            break;
        case 13:
            穿越OAO();
            break;
    }
})

if(location.hash.indexOf('accessToken')>=0){
    localStorage.setItem('accessToken',location.hash.match(/accessToken=([0-9a-f]{41})/)[1]);
    localStorage.setItem('accessTokenSecret',location.hash.match(/accessTokenSecret=([0-9a-f]{32})/)[1]);
}

if(localStorage.getItem('accessToken'))check_login();
else {
    set_login();
    start();
}

if(location.hash.indexOf('currentTime')>=0){
    audio.onplay=function(){
        audio.currentTime=location.hash.match(/currentTime=([0-9.]+)/)[1];
        audio.onplay=null;
    }
}

c_volume_range.value=volume;
audio.volume=volume/100;
update_volume_icon(volume);
if(location.hash.indexOf('background=0')<0){
    update_background();
    update_background_count(1);
    window.addEventListener('focus',function(){
        update_background_count(1);
    })
    window.addEventListener('blur',function(){
        update_background_count(0);
    })
}
if(location.hash.indexOf('loop=1')>0){
    loop=1;
}

  /////////////////////////////////////////////////////////////////
 //////     MoeFM HTML5 Project - Experimental Features     //////
/////////////////////////////////////////////////////////////////
/* Coming Soon...... */
</script>
</body>
</html>