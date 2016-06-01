<?php
/**
 * Seaslog.php
 * User: wlq314@qq.com
 * Date: 16/6/1 Time: 09:52
 */
class Seaslog extends CI_Controller{
    
    private $_basepath;
    
    public function __construct()
    {
        parent::__construct();
        $this->_basepath = '/data/logs/seaslog/';
    }

    public function setbasepath($path){
        return Seaslog::setBasePath($path);
    }

    public function log($content){
        return Seaslog::debug($content);
    }
}
