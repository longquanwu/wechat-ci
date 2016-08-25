<?php
/**
 * Wechat.php
 * User: wlq314@qq.com
 * Date: 16/6/12 Time: 16:41
 */

class Wechat extends WECHAT_Controller{

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMAGE = 'image';
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';
    const MSG_TYPE_SHORTVIDEO = 'shortvideo';
    const MSG_TYPE_LOCATION = 'location';
    const MSG_TYPE_EVENT = 'event';

    const EVENT_TYPE_SUBSCRIBE = 'subscribe';
    const EVENT_TYPE_UNSUBSCRIBE = 'unsubscribe';

    /** @var  Wechat_model */
    public $wechat_model;
    
    public $userOpenId;
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * 微信入口
     */
    public function index(){
        $_GET && $this->logger->info('GET信息:' . var_export($_GET, true));
        $postStr = empty($GLOBALS['HTTP_RAW_POST_DATA']) ? '' : $GLOBALS['HTTP_RAW_POST_DATA'];
        if ($this->messageQueue->send($postStr, 'wlq'))
            $this->logger->info('RabbitMQ发送请求信息到队列');
        
        if ( !empty($postStr) ){
            $this->logger->info('POST信息:' . var_export($postStr, true));
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $this->checkweixin();
        echo "<xml>
<ToUserName><![CDATA[{$postObj->FromUserName}]]></ToUserName>
<FromUserName><![CDATA[$postObj->ToUserName]]></FromUserName>
<CreateTime>time()</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[success]]></Content>
</xml>";
        $this->userOpenId = $postObj->FromUserName;
        $this->saveUserInfo($this->userOpenId);

        switch ( $postObj->MsgType ){
            case self::MSG_TYPE_TEXT:
                $result = $this->executeText();
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
        echo 'success';
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

    /**
     * 保存访客信息到数据库
     * @param $openId
     * @return bool
     */
    private function saveUserInfo($openId){
        $this->load->model('wechat/User_model');
        /** @var User_model $userModel */
        $userModel = $this->User_model;
        
        if ($userModel->isExistByOpenId($openId)){
            return true;
        }else{
            $userInfo = $this->wechatlib->getUserInfoByOpenId($this->getAccessToken(), $openId);
            $data = [
                'open_id' => $userInfo['openid'],
                'nick_name' => $userInfo['nickname'],
                'icon' => $userInfo['headimgurl'],
                'sex' => $userInfo['sex'],
                'city' => $userInfo['city'],
                'province' => $userInfo['province'],
                'language' => $userInfo['language'],
                'sub_time' => $userInfo['subscribe_time'],
                'status' => 1,
            ];
            if ($userModel->addNewUser($data)){
                $this->logger->info('新用户信息成功保存到到数据库');
            }else{
                $this->logger->error('新用户信息保存到到数据库 失败! ');
            }
        }
    }
    
    private function executeText(){
        
    }
}