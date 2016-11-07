<?php
/**
 * MY_Controller.php
 * User: wlq314@qq.com
 * Date: 16/6/5 Time: 00:10
 */

abstract class MY_Controller extends CI_Controller{
    
    /** @var  Logger $logger */
    public $logger;
    
    /** @var  MessageQueue */
    public $messageQueue;
    
    public function __construct(){
        parent::__construct();
        //读取日志模块
        $this->load->library('Logger', $this->config->item('logger'));
        $this->load->library('MessageQueue', $this->config->item('mq'), 'messageQueue');
    }

}

abstract class WECHAT_Controller extends CI_Controller{

    /** @var  WechatLib */
    public $wechatlib;

    /** @var  Logger $logger */
    public $logger;

    /** @var  MessageQueue */
    public $messageQueue;

    public function __construct(){
        parent::__construct();
        $this->load->library('WechatLib');  //读取微信Lib库
        $this->load->library('Logger', $this->config->item('logger'));  //读取日志Lib库
        $this->load->library('MessageQueue', $this->config->item('mq'), 'messageQueue');  //读取队列Lib库
    }

    /**
     * 获得AccessToken
     * @return mixed
     */
    protected function getAccessToken(){
        $this->load->model('wechat/AccessToken_model');
        /** @var AccessToken_model $accessTokenModel */
        $accessTokenModel = $this->AccessToken_model;

        $accessTokenInfo = $accessTokenModel->getAccessToken();
        $res = $accessTokenInfo['token'];

        //如果没有拿到accessToken的值或者accessToken已经过期,从微信获取新的accessToken值并保存到数据库
        if (empty($res) || $accessTokenInfo['due_time'] < time()) {
            $accessTokenInfo = $this->wechatlib->getAccessToken();
            $data = [
                'token' => $accessTokenInfo['access_token'],
                'due_time' => time() + $accessTokenInfo['expires_in'] - 200,
            ];

            //没拿到accessToken值则写入一条新数据,否则更新数据
            if (empty($res)) {
                $accessTokenModel->addAccessToken($data);
            } else {
                $accessTokenModel->updateAccessToken($data);
            }
            $res = $accessTokenInfo['access_token'];
        }
        return $res;
    }
    
}