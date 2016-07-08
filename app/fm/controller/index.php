<?php
namespace app\fm\controller;
class Index {
	public function index() {
		\View::make();
	}
	public function xiami() {
		$act = q('act', 'radio');
		$pid = q('id');
		$out = array();
		switch ($act) {
			case 'radio':
				$url = 'http://www.xiami.com/radio/xml/type/8/id/6961722?v='.time();
				$xml = \Curl::get($url);
				$data = \Xml::toSimpleArray($xml);
				foreach($data['trackList']['track'] as $k => $v){
					$out[] = array(
						'xid'=>$v['song_id'],
						'title'=>htmlspecialchars_decode($v['title'], ENT_QUOTES),
						'img'=>str_replace('http://img.xiami.net/images/album/', '', $v['pic']),
						'mp3'=>$v['location'],
						'album_name'=>htmlspecialchars_decode($v['album_name'], ENT_QUOTES),
						'artist'=>htmlspecialchars_decode($v['artist'], ENT_QUOTES),
						'album_id'=>$v['album_id'],
						'length'=>$v['length'],
						'play'=>$this->get_playcount($v['song_id'], 11)
					);
				}
				break;
			case 'song':
				$url = 'http://m.xiami.com/song/playlist/id/'.$pid.'/object_name/default/object_id/0/cat/json?_='.time();
				$file = \Curl::get($url);
				$json = json_decode($file, true);
				$data = $json['data']['trackList'][0];
				$out[] = array(
					'xid'=>$data['song_id'],
					'title'=>htmlspecialchars_decode($data['title'], ENT_QUOTES),
					'img'=>$data['album_pic'],
					'mp3'=>$data['location'],
					'album_name'=>htmlspecialchars_decode($data['album_name'], ENT_QUOTES),
					'artist'=>htmlspecialchars_decode($data['artist'], ENT_QUOTES),
					'album_id'=>$data['album_id'],
					'length'=>$data['length'],
					'play'=>$this->get_playcount($data['song_id'], 11)
				);
				break;
		}
		\Response::ajax($out);
	}
	public function moefm() {
		$url = 'http://moe.fm/listen/playlist?api=json&api_key=18f95c02504fb5a0fdd83b205e7e1aee05421a58b&_='.time();
		$act = q('act', 'radio');
		$pid = q('id');
		$out = array();
		switch ($act) {
			case 'radio':
				$url .= '&perpage=3';
				$json = json_decode(file_get_contents($url), true);
				$data = $json['response']['playlist'];
				foreach($data as $key => $val){
					$out[] = array(
						'xid' => $val['sub_id'],
						'title'=>htmlspecialchars_decode($val['sub_title'], ENT_QUOTES),
						'img'=>$val['cover']['large'],
						'mp3'=>$val['url'],
						'album_name'=>htmlspecialchars_decode($val['wiki_title'], ENT_QUOTES),
						'artist'=>htmlspecialchars_decode($val['artist'], ENT_QUOTES),
						'album_id'=>$val['wiki_id'],
						'length'=>$this->time2sec($val['stream_time']),
						'play'=>$this->get_playcount($val['sub_id'])
					);
				}
				break;
			case 'song':
				$url .= '&song='.$pid;
				$json = json_decode(file_get_contents($url), true);
				$data = $json['response']['playlist'][0];
				$out[] = array(
					'xid' => $data['sub_id'],
					'title'=>htmlspecialchars_decode($data['sub_title'], ENT_QUOTES),
					'img'=>$data['cover']['large'],
					'mp3'=>$data['url'],
					'album_name'=>htmlspecialchars_decode($data['wiki_title'], ENT_QUOTES),
					'artist'=>htmlspecialchars_decode($data['artist'], ENT_QUOTES),
					'album_id'=>$data['wiki_id'],
					'length'=>$this->time2sec($data['stream_time']),
					'play'=>$this->get_playcount($val['sub_id'])
				);
				break;
		}
		\Response::ajax($out);
	}
	public function playcount() {
		$db = \Db::table('playcount');
		$pid = q('pid');
		$rid = q('rid');
		if(preg_match('/^[0-9]{5,12}$/', $pid) && preg_match('/^[0-9]{1,3}$/', $rid)){
			$r = $db->where('pid', $pid)->where('rid', $rid)->first();
			if($r){
				$db->where('pid', $pid)->where('rid', $rid)->increment('pcount');
				$json = array(
					'id'=>$r['id'],
					'pid'=>$pid,
					'msg'=>'播放次数更新成功,当前->'.($r['pcount']+1).'次'
				);
			}else{
				$id = $db->insert(['pid'=>$pid, 'rid'=>$rid, 'pcount'=>'1']);
				$json = array(
					'id'=>$id,
					'pid'=>$pid,
					'msg'=>'播放次数添加成功,当前->1次'
				);
			}
			\Response::ajax($json);
		}
	}
	public function show() {
		p($_SERVER);
		// echo $this->get_playcount(1);
		echo 'http://www.xiami.com/radio/xml/type/8/id/6961722?v='.time();
	}
	private function time2sec($time){
		$t = split(':', $time);
		switch (count($t)) {
			case 2:
				$m = preg_replace('/^0+/', '', $t[0]);
				$s = preg_replace('/^0+/', '', $t[1]);
				$m = $m * 60;
				$time = $m + $s;
				break;
			
			case 3:
				$h = preg_replace('/^0+/', '', $t[0]);
				$m = preg_replace('/^0+/', '', $t[1]);
				$s = preg_replace('/^0+/', '', $t[2]);
				$h = $h * 60 * 60;
				$m = $m * 60;
				$time = $m + $s;
				break;
		}
		return $time;
	}
	private function get_playcount($pid, $rid='12'){
		$db = \Db::table('playcount');
		$r = $db->where('pid', $pid)->where('rid', $rid)->first();
		if($r)
			return $r['pcount'];
		else
			return 0;
	}
}
