<?php
/**
 * Admin.php
 * User: wlq314@qq.com
 * Date: 16/8/10 Time: 10:23
 */

class Admin extends WECHAT_Controller{
    
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->load->view('admin/index');
    }
    
    public function addKF(){
        $account = trim($this->input->post('account', true));
        $nickName = trim($this->input->post('nickname', true));
        $password = trim($this->input->post('password', true));
        
        $accessToken = $this->getAccessToken();
        print_r( $this->wechatlib->addKF($accessToken, $account, $nickName, $password));
        $this->load->view('admin/index');
    }
    
    public function test(){
        echo 1234;
        $this->load->library('email');

        $this->email->from('your@example.com', 'Your Name');
        $this->email->to('wlq314@qq.com');
        $this->email->cc('longquangege@gmail.com');
//        $this->email->bcc('them@their-example.com');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        $this->email->send();
    }
    
}