<?php
/**
 * Test.php
 * User: wlq314@qq.com
 * Date: 16/5/30 Time: 12:06
 */

class Test extends MY_Controller{
    
    public function log(){
        echo '开始new log test->';
        echo $this->logger->alert('new logger test');
    }
    
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
        foreach ($query->result() as $row){
            print_r($row);
        }
    }

    public function seaslog(){
        var_dump(SEASLOG_DEBUG,SEASLOG_INFO,SEASLOG_NOTICE);
    }
}