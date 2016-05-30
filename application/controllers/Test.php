<?php
/**
 * Test.php
 * User: wlq314@qq.com
 * Date: 16/5/30 Time: 12:06
 */

class Test extends CI_Controller{
    /**
     * @param string $name
     * @param string $content
     */
    public function wlq($name = 'wlq', $content = ''){
        echo $name." SAY: ".$content;
    }

    /**
     * @param string $content
     */
    public function index($content = 'test/index'){
        echo $content;
    }

    /**
     * 测试view的使用
     * Test constructor.
     * @param string $name
     * @param string $age
     */
    public function viewtest($name = 'baby', $age = '10'){
        $data = [];
        $data['name'] = $name;
        $data['age'] = $age;
        $this->load->view('viewtest/test', $data);
    }

    public function dbtest(){
        $this->load->database();
        $query = $this->db->query('SELECT * FROM ks_news');
        var_dump($query);
    }
}