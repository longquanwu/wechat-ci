<?php
/**
 * WechatLib.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 11:36
 */

class WechatLib{
    
    private $CI;

    /** @var  Logger $logger */
    public $logger;

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

    public function getAccessToken(){
        $this->CI->load->model('wechat/AccessToken_model');
        
        /** @var AccessToken_model $accessTokenModel */
        $accessTokenModel = $this->CI->AccessToken_model;
        $accessTokenInfo = $accessTokenModel->getAccessToken();
        $res = $accessTokenInfo['token'];
        
        //如果没有拿到accessToken的值或者accessToken已经过期,从微信获取新的accessToken值并保存到数据库
        if (empty($accessTokenInfo) || $accessTokenInfo['due_time'] < time()){
            $url = sprintf($this->apiUrl['access_token'], $this->appId, $this->secret);
            $resJson = curl($url);
            $this->logger->info('从微信获取新AccessToken:'.$resJson);
            !empty($resJson) && $resArr = json_decode($resJson, true);
            $data = [
                'token' => $resArr['access_token'],
                'due_time' => time() + $resArr['expires_in'] - 200,
            ];

            //没拿到accessToken值则写入一条新数据,否则更新数据
            if (empty($accessTokenInfo)){
                $accessTokenModel->addAccessToken($data);
            }else{
                $accessTokenModel->updateAccessToken($data);
            }
            $res = $resArr['access_token'];
        }
        return $res;
    }

    public function getUserInfoByUserOpenId(){
        $accessToken = $this->getAccessToken();
    }
}