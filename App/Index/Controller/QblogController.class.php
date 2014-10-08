<?php
/**
 *
 */
class QblogController extends Controller{
    /**
     * 初始化
     */
    public function __construct(){}
    /**
     * 首页
     */
    public function index(){
        $this->display();
    }
}