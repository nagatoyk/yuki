<?php
/**
 * 授权
 */
class OauthController extends Controller{
    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
    }
    /**
     * 回调处理
     */
    public function index(){
        echo $_SERVER['QUERY_STRING'];
    }
}