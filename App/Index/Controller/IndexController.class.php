<?php
/**
 * 前台
 */
class IndexController extends Controller{
    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
    }
    /**
     * 首页
     */
    public function index(){
        $this->display('index.v2.html');
    }
    /**
     * 抓取播放列表
     */
    public function catch_listen(){
        header('Content-Type:application/json;charset=utf-8');
        $url = 'http://moe.fm/listen/playlist?';
        echo $this->curl($url.$_SERVER['QUERY_STRING']);

    }
    /**
     * 抓取个人信息
     */
    public function catch_detail(){
        header('Content-Type:application/json;charset=utf-8');
        $url = 'http://api.moefou.org/user/detail.json?';
        echo $this->curl($url.$_SERVER['QUERY_STRING']);
    }
    private function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}