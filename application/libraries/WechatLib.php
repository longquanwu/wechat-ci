<?php
/**
 * WechatLib.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 11:36
 */

class WechatLib{

    /** @var  Logger $logger */
    public $logger;

    /** @var CI_Controller  */
    private $CI;

    private $appId;

    private $secret;

    private $apiUrl = [
        //获得access_token
        'access_token' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
        //获得微信服务器地址
        'ip_list' => "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s",
        //根据用户openId获得用户基本信息
        'user_info' => "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN",
        
        //自定义菜单
        'menu_create' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s",
        //获得自定义菜单
        'menu_get' => "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s",
        //删除全部自定义菜单
        'menu_delete' => "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s",
        //创建个性化菜单
        'menu_addconditional' => "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s",
        
        /**
        access_token	是	调用接口凭证
        kf_account	是	完整客服账号，格式为：账号前缀@公众号微信号
        kf_nick	是	客服昵称
        kf_id	是	客服工号
        nickname	是	客服昵称，最长6个汉字或12个英文字符
        password	否	客服账号登录密码，格式为密码明文的32位加密MD5值。该密码仅用于在公众平台官网的多客服功能中使用，若不使用多客服功能，则不必设置密码
        media	是	该参数仅在设置客服头像时出现，是form-data中媒体文件标识，有filename、filelength、content-type等信息
         */
        //添加客服账号
        'kf_add' => "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=%s",
        //修改客服账号
        'kf_update' => "https://api.weixin.qq.com/customservice/kfaccount/update?access_token=%s",
        //删除客服账号
        'kf_del' => "https://api.weixin.qq.com/customservice/kfaccount/del?access_token=%s",
        //修改客服头像
        'kf_uploadheadimg' => "http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=%s&kf_account=%s",
        //获得所有客服账号
        'kf_list' => "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=%s",
        
        /**
        access_token	是	调用接口凭证
        touser	是	普通用户openid
        msgtype	是	消息类型，文本为text，图片为image，语音为voice，视频消息为video，音乐消息为music，图文消息（点击跳转到外链）为news，图文消息（点击跳转到图文消息页面）为mpnews，卡券为wxcard
        content	是	文本消息内容
        media_id	是	发送的图片/语音/视频/图文消息（点击跳转到图文消息页）的媒体ID
        thumb_media_id	是	缩略图的媒体ID
        title	否	图文消息/视频消息/音乐消息的标题
        description	否	图文消息/视频消息/音乐消息的描述
        musicurl	是	音乐链接
        hqmusicurl	是	高品质音乐链接，wifi环境优先使用该链接播放音乐
        url	否	图文消息被点击后跳转的链接
        picurl	否	图文消息的图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80
         */
        //客服发送消息
        'kf_send' => "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s",



    ];

    /**
     * 加载Wechat配置, 加载Log类
     * WechatLib constructor.
     */
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
        if (empty($resJson))
            return [];
        return json_decode($resJson, true);
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
        if (empty($resJson))
            return [];
        return json_decode($resJson, true);
    }

    /**
     * 获得客服列表
     * @param string $accessToken
     * @return array|mixed
     */
    public function getKFList($accessToken){
        $url = sprintf($this->apiUrl['kf_list'], $accessToken);
        $resJson = CURL::get($url);
        $this->logger->info("获得客服列表: " . $resJson);
        if (empty($resJson))
            return [];
        return json_decode($resJson, true);
    }

    /**
     * 添加客服
     * @param string $accessToken  AccessToken
     * @param string $kf_account  客服账号前缀
     * @param string $nickname  昵称
     * @param string $password  密码,可以为空
     * @return array|mixed
     */
    public function addKF($accessToken, $kf_account, $nickname, $password = ''){
        $url = sprintf($this->apiUrl['kf_add'], $accessToken);
        $kf_account .= '@' . $this->CI->config->item('wechat')['name'];
        $data = ['kf_account' => $kf_account, 'nickname' => $nickname, 'password' => $password];
        $resJson = CURL::post($url, $data);
        $this->logger->info("添加客服 " . var_export($data, true) . ' . ' . $resJson);
        if (empty($resJson))
            return [];
        return json_decode($resJson, true);
    }

}