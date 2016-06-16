<?php
/**
 * Wechat.php
 * User: wlq314@qq.com
 * Date: 16/6/12 Time: 16:41
 */

class Wechat extends MY_Controller{

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMAGE = 'image';
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';
    const MSG_TYPE_SHORTVIDEO = 'shortvideo';
    const MSG_TYPE_LOCATION = 'location';
    const MSG_TYPE_EVENT = 'event';

    const EVENT_TYPE_SUBSCRIBE = 'subscribe';
    const EVENT_TYPE_UNSUBSCRIBE = 'unsubscribe';

    
    public $apiUrl = [
        //获得access_token
        'access_token' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
        //获得微信服务器地址
        'ip_list' => "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s",
        //自定义菜单
        'menu_create' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s",
        //获得自定义菜单
        'menu_get' => "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s",
        //删除全部自定义菜单
        'menu_delete' => "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s",
        //创建个性化菜单
        'menu_addconditional' => "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s",
        //根据用户openId获得用户基本信息
        'user_info' => "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN",
    ];

    /**
     * 微信入口
     */
    public function index(){
        $_GET && $this->logger->info('GET信息:' . var_export($_GET, true));
        $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        if ( !empty($postStr) ){
            $this->logger->info('POST信息:' . var_export($postStr, true));
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $this->checkweixin();
        switch ( $postObj->MsgType ){
            case self::MSG_TYPE_TEXT:
                break;
            case self::MSG_TYPE_IMAGE:
                break;
            case self::MSG_TYPE_VOICE:
                break;
            case self::MSG_TYPE_VIDEO;
                break;
            case self::MSG_TYPE_SHORTVIDEO:
                break;
            case self::MSG_TYPE_LOCATION:
                break;
            case self::MSG_TYPE_EVENT:
                break;
            default:
                
        }
        $postObj->ToUserName;
        $postObj->FromUserName;
    }
    
    private function execute(){
        
    }

    /**
     * 验证微信TOKEN
     * @return bool
     */
    private function checkweixin(){
        $token = $this->config->item('wechat')['token'];
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            //第一次验证需要ECHO
            if ( $_GET['echostr'] ){
                echo $_GET['echostr'];
                exit;
            }
            return true;
        }else{
            die('请通过微信平台访问');
        }
    }
}