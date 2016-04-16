/* <i.mouto.org> 音乐<专辑>部分 @卜卜口 */
$.lcss('i/itunes.css');
var music=function(win,doc){
	var seToMin=function(s){
		return (Math.floor(s/60)||'0')+':'+((s%60>9)?s%60:('0'+s%60))
	},
	music={
		open:function(G){
			document.title='专辑目录';
			Q.MX('music','music.php',function(r){
				var i,ii,o,oo,rr=[],rrr,num;
				for(i in r){
					o=r[i];
					rrr=[];
					num=0;
					for(ii in o[3]){
						oo=o[3][ii];
						num++;
						rrr.push({i:num,xid:ii,title:oo[0],time:seToMin(oo[1])})
					}
					rr.push({aid:i,title:o[0],singer:o[1],img:o[2],songs:rrr})
				}
				return rr
			},Q.M)
		}
	};
	music.open();
	return music
}(window,document);
/* 音乐 End */
