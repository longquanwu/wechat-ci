<?php
/**
 * RabbitMQ生产者类
 * MessageQueue.php
 * User: wlq314@qq.com
 * Date: 16/8/4 Time: 16:10
 */

class MessageQueue{
    
    private $config = [];
    
    /** @var AMQPConnection */
    private $conn = Null;
    
    /** @var AMQPChannel */
    private $channel = Null;
    
    /** @var  AMQPExchange */
    private $exchange = Null;
    
    public $is_ready = False;

    /**
     * 创建连接并指定交换机
     * MessageQueue constructor.
     * @param $conf
     */
    public function __construct($conf){
        $config = $conf['config'];
        $e_name = $conf['e_name'];
        if (!$config || !$e_name)
            return False;
        $this->config = $config;
        if (!$this->connect())
            return False;
        $this->channel = new AMQPChannel($this->conn);
        $this->establishExchange($e_name);

        $this->is_ready = True;
    }

    /**
     * 发送消息
     * @param $msg  消息体
     * @param $k_route  路由键
     * @return bool
     */
    public function send($msg, $k_route){
        $msg = trim(strval($msg));
        if (!$this->exchange || $msg === '' || !$k_route)
            return False;
        $ret = $this->exchange->publish($msg, $k_route);
        return $ret;
    }

    /**
     * 创建连接
     * @return bool
     */
    private function connect(){
        $this->conn = new AMQPConnection($this->config);
        if ($this->conn->connect())
            return True;
        return Fales;
    }

    /**
     * 创建交换机
     * @param string $name 名称
     * @return void
     */
    private function establishExchange($name){
        $this->exchange = new AMQPExchange($this->channel);
        $this->exchange->setName($name);
        return True;
    }

    /**
     * 断开连接
     */
    public function __destruct(){
        if ($this->conn){
            $this->conn->disconnect();
        }
    }
    
}