<?php
/**
 * Wechat_model.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 10:27
 */

class Wechat_model extends CI_Model{

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
        parent::__construct();
        $this->appId = $this->config->item('wechat')['appID'];
        $this->secret = $this->config->item('wechat')['appSecret'];
        $this->load->helper('curl');
        //读取日志模块
        $this->load->library('Logger', $this->config->item('logger'));
    }
    
    public function getAccessToken(){
        $url = sprintf($this->apiUrl['access_token'], $this->appId, $this->secret);
        $res = curl($url);
        print_r($this->logger);
        print_r($res);exit;
        $this->logger->info(var_export($res, true));
        return isset($res['access_token']) ? $res['access_token'] : false;
    }
}

