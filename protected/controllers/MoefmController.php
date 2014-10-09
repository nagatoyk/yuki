<?php
/**
 * 萌否
 */
class MoefmController extends Controller{
    /*public function __construct(){
    }*/
    public function actionIndex(){
        $o = new SaeTOAuthV2(Yii::app()->params['saet_api_key'], Yii::app()->params['saet_api_secret']);
        $wburl = $o->getAuthorizeURL('http://i.loli-yuki.tk'.$this->createUrl('saetcallback'), 'code', 'moefm', 'mobile');
        $this->render('index', array('wburl' => $wburl));
    }
    public function actionSaetcallback(){
        $o = new SaeTOAuthV2(Yii::app()->params['saet_api_key'], Yii::app()->params['saet_api_secret']);
        if(isset($_REQUEST['code'])){
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $o->getAuthorizeURL('http://i.loli-yuki.tk'.$this->createUrl('saetcallback'), 'code', 'moefm', 'mobile');
            p($keys);
            try{
                $token = $o->getAccessToken('code', $keys);
            }catch(OAuthException $e){
            }
        }
        p($o);
        p($token);
        if($token){
            $_SESSION['token'] = $token;
            setcookie('weibojs_'.$o->client_id, http_build_query($token));
            echo 1;
        }else{
            echo 0;
        }
        p($_SESSION);
    }
}
