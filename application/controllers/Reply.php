<?php
/**
 * Reply.php
 * User: wlq314@qq.com
 * Date: 16/5/31 Time: 15:39
 */
class Reply extends CI_Controller{

    public function _remap( $methed, $params = []){
        switch ($methed){
            case 'girl':
                echo 'PARAMS->';
                print_r($params);
                $this->replygirl();
                break;
            default:
                echo 'CANT SEARCH METHED';
        }
    }

    public function replygirl(){
        echo 'THIS IS REDIR METHOD';
    }
}