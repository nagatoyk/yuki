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
        $this->display();
    }
    /**
     * 新浪微博回调
     */
    public function wbcallback(){
        $o = new SaeTOAuthV2(C('SAET_AKEY'), C('SAET_SKEY'));
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = U('Index/Index/index');
            try {
                $token = $o->getAccessToken( 'code', $keys ) ;
            } catch (OAuthException $e) {
            }
        }
        if($token){
            $_SESSION['token'] = $token;
            setcookie('weibojs_'.$o->client_id, http_build_query($token));
        }else{
            echo '授权失败!!!!!!!';
        }
    }
}