<?php
/**
 * Reply.php
 * User: wlq314@qq.com
 * Date: 16/5/31 Time: 15:39
 */
class Reply extends CI_Controller{

    public function _remap($method, $params = []){
        $method = trim($method);
        if (method_exists($this, $method)){
            return call_user_func_array([$this, $method], $params);
        }else{
            echo 'CANT SEARCH THIS METHOD ->'.$method;
        }
    }

    public function girl($name, $age){
        echo 'THIS GIRL '.$name.' IS '.$age;
    }

    public function boy($name, $age, $height){
        $str = $name.' 今年 '.$age.' 岁了,身高 '.$height.'CM';
        $this->load->view('user/reply/boy', $str);
    }

    public function _output($data){
        echo '_OUTPUT:'.$data;
    }
}