<?php
/**
 * 萌否
 */
class MoefmController extends Controller{
    /*public function __construct(){
    }*/
    public function actionIndex(){
        $o = new SaeTOAuthV2(Yii::app()->params['saet_api_key'], Yii::app()->params['saet_api_secret']);
        $wburl = $o->getAuthorizeURL(Yii::app()->request->hostinfo.$this->createUrl('saetcallback'), 'code', 'moefm', 'default');
        $this->render('index', array('wburl' => $wburl, 'saet' => $o));
    }
    public function actionSaetcallback(){
        $o = new SaeTOAuthV2(Yii::app()->params['saet_api_key'], Yii::app()->params['saet_api_secret']);
        if(isset($_REQUEST['code'])){
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = Yii::app()->request->hostinfo.Yii::app()->createUrl('saetcallback');
            try{
                $token = $o->getAccessToken('code', $keys);
            }catch(OAuthException $e){
                p($e);
            }
        }
        if($token){
            Yii::app()->session['token'] = $token;
            $jstoken = new CHttpCookie('weibojs_'.$o->client_id, http_build_query($token));
            // setcookie('weibojs_'.$o->client_id, http_build_query($token));
            $this->render('saetcallback');
        }else{
            echo '授权失败';
        }
    }
}
