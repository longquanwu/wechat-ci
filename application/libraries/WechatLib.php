<?php
/**
 * WechatLib.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 11:36
 */

class WechatLib{

    /** @var  Logger $logger */
    public $logger;

    private $CI;

    private $appId;

    private $secret;

    private $apiUrl = [
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

    public function __construct(){
        $this->CI = & get_instance();
        $this->appId = $this->CI->config->item('wechat')['appID'];
        $this->secret = $this->CI->config->item('wechat')['appSecret'];
        
        $this->CI->load->helper('curl');
        //读取日志模块
        $this->CI->load->library('Logger', $this->CI->config->item('logger'));
        $this->logger = $this->CI->logger;
    }

    /**
     * 获得accessToken
     * @return mixed
     */
    public function getAccessToken(){
        $url = sprintf($this->apiUrl['access_token'], $this->appId, $this->secret);
        $resJson = curl($url);
        $this->logger->info('从微信获取新AccessToken:'.$resJson);
        if (empty($resJson)){
            return [];
        }else{
            return json_decode($resJson, true);
        }
    }

    /**
     * 根据OpenId获取用户信息
     * @param $accessToken
     * @param $openId
     * @return array|mixed
     */
    public function getUserInfoByOpenId($accessToken, $openId){
        $url = sprintf($this->apiUrl['user_info'], $accessToken, $openId);
        $resJson = curl($url);
        $this->logger->info("根据用户OpenId {$openId} 从微信获取用户信息:" . $resJson);
        if (empty($resJson)){
            return [];
        }else {
            return json_decode($resJson, true);
        }
    }

}