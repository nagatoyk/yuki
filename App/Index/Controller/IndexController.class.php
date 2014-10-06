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
        $this->display();
    }
}