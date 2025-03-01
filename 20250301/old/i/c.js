var C=new MD.C(),
Q=function($,win,doc){
	var M=$('#m'),
	html=$('html'),
	body=$('body'),
	X,
	getM=function(name){
		return $('#_'+name+'_').innerHTML;
	},
	x=function(t,d,r,f){
		t=getM(t);
		X=$.x(d,function(i){
			f(Mustache.render(t,r(i)));
		});
	},
	tranH=function(t,f,ff){
		switch(typeof f){
			case'function':
				f(t);
				break;
			case'object':
				f.innerHTML=t;
				break;
			case'string':
				$(f).innerHTML=t;
				break;
			default:
				return t;
				break;
		}
		switch(typeof ff){
			case'function':
				ff(t);
				break;
		}
	},
	MD=function(t,i,r,f){
		if(!f){
			f=r;
			r=function(i){
				return i;
			};
		}
		t=getM(t);
		t=Mustache.render(t,r(i));
		tranH(t,f);
		return t;
	},
	MX=function(t,d,r,f,ff){
		if(!f||typeof r!='function'){
			ff=f;
			f=r;
			r=function(i){
				return i;
			};
		}
		t=getM(t);
		return $.x(d,function(i){
			console.log(i);
			if(i.error){
				return r?r(i):alert(i.error);
			}
			t=Mustache.render(t,r(i));
			if(f){
				tranH(t,f,ff);
			}
		});
	},
	Q={
		x:x,
		M:M,
		MD:MD,
		MX:MX,
		home:function(s,c,u){
			M.innerHTML='<p class="loading"></p>';
			var A;
			if(A=$('#nav a.a')){
				A.className='';
			}
			if(!c){
				document.title=Title;
			}else{
				document.title=INF.c[c][0]+' - '+INF.c[c][1];
				if(A=$('#nav a[href="#!c/'+c+'"]')){
					A.className='a';
				}
			}
			var oTime=2000000000,
			ppp;
			x('home','x/?a=h&s='+(s||'')+'&c='+(c||'')+'&u='+(u||''),function(o){
				ppp=o;
				for(var i in o){
					o[i].h1=o[i].title.replace(/^(\s|)【.+】/,'');
					if(i==0){
						B=o[i].text;
					}
					var a;
					o[i].html=C.makeHtml($.ex(o[i].text).replace(/<!--more-->/,'<p class="more"><a href="#!'+o[i].pid+'">- 查看更多 -</a></p>'));
					if(o[i].cover){
						if(o[i].cover.match(/^[\w]{16,32}$/)){
							o[i].pic='http://ww2.sinaimg.cn/mw1024/'+o[i].cover;
						}else{
							o[i].pic=o[i].cover;
						}
					}else if(a=o[i].text.match(/http\:\/\/[0-9A-Za-z\/.#&!?%:;=_\-]+\.(?:gif|jpg|jpeg|png)/)){
						o[i].pic=a;
						o[i].html=o[i].html.replace(/(<img.+src="|!\[.+\]\()http\:\/\/[0-9A-Za-z\/.#&!?%:;=_\-]+\.(?:gif|jpg|jpeg|png)(.+?>|\))/,'');
					}
					o[i].categoryName=INF.c[o[i].category][0];
					o[i].author=INF.u[o[i].authorId];
					o[i].date=$.re_date(o[i].created);
					if(oTime>o[i].created){
						oTime=o[i].created;
					}
				}
				var r={
					category:INF.c,
					p:o
				};
				if(c){
					r.nowCat=INF.c[c];
				}
				return r;
			},function(h){
				M.innerHTML=h;$.j('i/search.js');
				if(h.length>300&&ppp&&ppp.length==5){
					if(c){
						$('#more').innerHTML='<a href="#!c/'+c+'/'+oTime+'" class="more">加载 '+$.re_date(oTime)+' 之前的'+INF.c[c][0]+'…</a>';
					}else if(u){
						$('#more').innerHTML='<a href="#!u/'+u+'/'+oTime+'" class="more">加载 '+INF.u[u]+' '+$.re_date(oTime)+' 之前的文章…</a>';
					}else{
						$('#more').innerHTML='<a href="#!page/'+oTime+'" class="more">加载 '+$.re_date(oTime)+' 之前的文章…</a>';
					}
				}else{
					$('#more').innerHTML='<a href="#!home" class="more">查看最新文章…</a>';
				}
				if(s&&s.length){
					window.scrollTo(0,$('#m').offsetTop);
				}
				$('.more').onclick=function(){};
				if($('pre code')){
					$.lcss('i/md/monokai_sublime.css');
					$.j('i/md/highlight.pack.js',function(){
						hljs.initHighlighting();
					});
				}
			});
		},
		c:function(s){
			Q.home(s[1],s[0]);
		},
		u:function(s){
			Q.home(s[1],0,s[0]);
		},
		p:function(i){
			M.innerHTML='<p class="loading"></p>';
			x('p','x/?a=p&id='+i,function(o){
				o.h1=o.title.replace(/^(\s|)【.+】/,'');
				document.title=o.h1||'　-　';
				if(i==0){
					B=o.text;
				}
				o.html=C.makeHtml($.ex(o.text));
				if(!o.cover&&(a=o.text.match(/http\:\/\/[0-9A-Za-z\/.#&!?%:;=_\-]+\.(?:gif|jpg|jpeg|png)/))){
					o.pic=a;
				}else if(o.cover){
					o.pic='http://ww2.sinaimg.cn/mw1024/'+o.cover;
				}
				o.html=C.makeHtml(!o.cover?$.ex(o.text).replace(/(<img.+src="|!\[.+\]\()http\:\/\/[0-9A-Za-z\/.#&!?%:;=_\-]+\.(?:gif|jpg|jpeg|png)(.+?>|\))/,''):$.ex(o.text));
				o.categoryName=INF.c[o.category][0];
				o.url=INF.url+'?'+o.pid;
				var A;
				if(A=$('#nav a.a')){
					A.className='';
				}
				if(A=$('#nav a[href="#!c/'+o.category+'"]')){
					A.className='a';
				}
				o.author=INF.u[o.authorId];
				o.date=$.re_date(o.created);
				return o;
			},function(h){
				M.innerHTML=h;
				window.scrollTo(0,$('#m').offsetTop);
				if(!DS_cfg.id){
					if(window.cmt){
						cmt.load(i);
					}else{
						$.j('i/cmts.js',function(){
							cmt.load(i);
						});
					}
				}else{
					if(window.DS&&DS.load){
						DS.load();
					}else{
						$.j('i/DS.js');
					}
				}
				if($('pre code')){
					$.lcss('i/md/monokai_sublime.css');
					$.j('i/md/highlight.pack.js',function(){
						hljs.initHighlighting();
					});
				}
			});
		},
		vsco:function(){
			var A;
			if(A=$('#nav a.a')){
				A.className='';
			}
			if(A=$('#nav a[href="#!vsco"]')){
				A.className='a';
			}
			document.title='VSCO';
			M.innerHTML='<div id="grid"><p class="loading"></p></div>			<div class="VS-more"><div id="more"></div></div>			<div id="showImg" class="hide"></div>';
			$.lcss('i/vscam.css');
			$.j('i/vscam.js');
		},
		music:function(L){
			var A;
			if(A=$('#nav a.a')){
				A.className='';
			}
			if(A=$('#nav a[href="#!music"]')){
				A.className='a';
			}
			if(!$('.itunes')){
				M.innerHTML='<p class="loading"></p>';
			}
			if(win.music){
				music.open(L);
			}else{
				$.j('i/itunes.js');
			}
		},
		link:function(){
			M.innerHTML='<div id="link"><p class="loading"></p></div>';
			var A;
			if(A=$('#nav a.a')){
				A.className='';
			}
			if(A=$('#nav a[href="#!link"]')){
				A.className='a';
			}
			$.x('link.php',function(E){
				var O,
				h='<ul class="hp c">',
				l,
				i=0,
				D=0;
				for(E=E.split('\n'),l=E.length;i<l;i++){
					O=E[i].split(',');
					if(E[i].match(/^\!/)){

					}else if(O.length==1&&O[0]==''){
						h+='<li class="hr">';
					}else if(O.length==1){
						h+='<li class="hr"><h2>'+O+'</h2></li>';
					}else if(O.length==1){
						h+='<li><i class="i-'+O[0]+'"></i></li>';
					}else{
						if(!O[3]){
							O[3]='';
						}
						if(O[0].match(/^\?/)){
							O[0]=O[0].substr(1);
							h+='<li class="bad">';
						}else{
							h+='<li>';
						}
						h+='<a href="//'+O[1]+'" target="_blank">'+(function(){
							if(O[3].match(/^\d+$/)){
								return '<img src="http://tp4.sinaimg.cn/'+O[3]+'/180/0">';
							}else if(O[3].match(/^\w{32}$/)){
								return '<img src="http://gravatar.duoshuo.com/avatar/'+O[3]+'?s=60">';
							}else if(O[3]){
								return '<img src="'+O[3]+'">';
							}else{
								return '<img src="images/icons/link.svg" class="def">';
							}
						})()+'<h3>'+O[0]+'</h3>								<p>'+O[2]+'</p>								<span>'+O[1].replace(/^http:\/\//,'')+'</span>							</a>						</li>';
					}
				}
				document.title='链接';
				M.innerHTML=h+'</ul><div class="banner">異次元任意門</div>';
			});
			$.lcss('i/link.css');
		}
	};
	Q.page=Q.home;
	$('#nav').innerHTML=Mustache.render($('#_nav_').innerHTML,function(){
		var c=[];
		for(var i in INF.c){
			if(i&&INF.c[i][1]){
				c.push({
					id:i,
					name:INF.c[i][0],
					des:INF.c[i][1]
				});
			}
		}
		return {
			c:c,
			p:[{
				name:'音乐',
				url:'#!music'
			},{
				name:'链接',
				url:'#!link'
			}]
		};
	}());
	var Title=document.title,
	laHash='简直惨惨惨OAQ',
	popstate=function(){
		if('onhashchange'in win){
			win.onhashchange=popstate;
		}
		if(laHash==location.hash){
			return;
		}
		var lash=location.hash.substring(2),
		ER=/^(?:home|page|vsco|music|link|c|u|[0-9]{1,7})/,
		L=lash.split('/');
		if(!L[0].match(ER)){
			location.hash='#!home';
			return;
		}
		if(lash.match(ER)!=L[0]){
			M.style.cssText='transition:none';
			M.className='h';
			setTimeout(function(){
				M.style.cssText='';
				M.className='';
			},10);
		}
		laHash=location.hash;
		if(lash.match(/^[0-9]{1,7}$/)){
			body.className='P-body';
			Q.p(lash);
		}else{
			body.className=L[0];
			Q[L.shift()](L);
		}
		if($.cookie('wb')&&$.stat){
			$.stat($.cookie('wb'));
		}
	};
	setTimeout(popstate,100);
	if(!'onhashchange'in win){
		setInterval(function(){
			if(laHash!=location.hash){
				popstate();
				laHash=location.hash;
			}
		},100);
	}
	// $.j('http://tajs.qq.com/stats?sId=37471835');
	console.log('ヾ(≧∇≦)〃可能是世界最快博客 v2 @卜卜口<mouto.sinaapp.com> 2014/08/30');
	return Q;
}(iTorr,window,document);
