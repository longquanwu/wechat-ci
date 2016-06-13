<?php
/**
 * MY_Controller.php
 * User: wlq314@qq.com
 * Date: 16/6/5 Time: 00:10
 */

abstract class MY_Controller extends CI_Controller{
    
    /** @var  Logger $logger */
    public $logger;
    
    public function __construct(){
        parent::__construct();
        $this->load->library('Logger', [$this->config->item['logger']]);
    }

}