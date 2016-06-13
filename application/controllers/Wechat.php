<?php
/**
 * Wechat.php
 * User: wlq314@qq.com
 * Date: 16/6/12 Time: 16:41
 */

class Wechat extends MY_Controller{

    /**
     * 微信入口
     */
    public function index(){
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        SeasLog::debug("请求POST信息:".var_export($postArr, true));
        SeasLog::debug("请求GET信息:".var_export($_GET, true));
        $this->checkweixin();
    }

    /**
     * 验证微信TOKEN
     * @return bool
     */
    private function checkweixin(){
        $wechatInfo = $this->config->item('wechat');
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $tmpArr = array($wechatInfo['token'], $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            //第一次验证需要ECHO
            if ( isset($_GET['echostr']) ){
                echo $_GET['echostr'];
                exit;
            }
            return true;
        }else{
            die('请通过微信平台访问');
        }
    }
}