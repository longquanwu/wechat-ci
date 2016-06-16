<?php
/**
 * Wechat.php
 * User: wlq314@qq.com
 * Date: 16/6/12 Time: 16:41
 */

class Wechat extends MY_Controller{

    const TEXT = '<xml><ToUserName><![CDATA[gh_89fd7c49a140]]></ToUserName>
        <FromUserName><![CDATA[oTRnEvp99idL-IBkUpDsrT4za6UA]]></FromUserName>
        <CreateTime>1466046551</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[你好～]]></Content>
        <MsgId>6296621991369288861</MsgId>
        </xml>';

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMAGE = 'image';
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';
    const MSG_TYPE_SHORTVIDEO = 'shortvideo';
    const MSG_TYPE_LOCATION = 'location';
    const MSG_TYPE_EVENT = 'event';

    const EVENT_TYPE_SUBSCRIBE = 'subscribe';
    const EVENT_TYPE_UNSUBSCRIBE = 'unsubscribe';

    /** @var  Wechat_model $wechat_model */
    public $wechat_model;
    
    /** @var  WechatLib $wechatlib */
    public $wechatlib;
    
    public function __construct(){
        parent::__construct();
//        $this->load->model('wechat/wechat_model');
        $this->load->library('WechatLib');
    }

    /**
     * 微信入口
     */
    public function index(){
        $_GET && $this->logger->info('GET信息:' . var_export($_GET, true));
        $postStr = empty($GLOBALS['HTTP_RAW_POST_DATA']) ? '' : $GLOBALS['HTTP_RAW_POST_DATA'];
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
        
        $signature = empty($_GET["signature"]) ? '' : $_GET["signature"];
        $timestamp = empty($_GET["timestamp"]) ? '' : $_GET["timestamp"];
        $nonce     = empty($_GET["nonce"]) ? '' : $_GET["nonce"];

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