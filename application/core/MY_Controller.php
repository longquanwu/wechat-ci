<?php
/**
 * MY_Controller.php
 * User: wlq314@qq.com
 * Date: 16/6/5 Time: 00:10
 */

abstract class MY_Controller extends CI_Controller{
    
    private $_basepath = '/data/logs/';
    private $_logger = 'wechat-ci';
    
    public function __construct(){
        parent::__construct();
        Seaslog::setbasepath($this->_basepath);
        SeasLog::setLogger($this->_logger);
    }
    
}