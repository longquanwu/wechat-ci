<?php
/**
 * MY_Model.php
 * User: wlq314@qq.com
 * Date: 16/6/5 Time: 00:11
 */

abstract class MY_Model extends CI_Model{
    
    //表名,子类必须填充
    protected $_table;
    
    //主键名,子类必须填充
    protected $_primary;
    
    //主数据库
    private $mdb;
    
    //从数据库
    private $sdb;
    
    /** @var  Logger $logger */
    public $logger;
    
    public function __construct(){
        parent::__construct();
        $this->mdb = $this->load->database('default', true);
        $this->sdb = $this->load->database('default', true);
        $this->_table = $this->setTableName();
        $this->_primary = $this->setPrimary();
        //加载日志类
        $this->load->library('Logger', $this->config->item('logger'));
    }
    
    abstract protected function setTableName();
    
    abstract protected function setPrimary();

    /**
     * 插入数据方法
     * @param array $data 要插入的数据数组 字段=>值
     * @return bool
     */
    public function insertData(array $data){
        if (empty($data)) return FALSE;
        $this->mdb->insert($this->_table, $data);
        return ( $result_id = $this->mdb->insert_id() ) ? $result_id : false;
    }

    /**
     * 删除方法,支持多条件删除
     * @param array $cond  数组格式  key代表字段名称  value代表字段值
     * @return bool
     */
    public function deleteByCond(array $cond){
        if (!is_array($cond)) return FALSE;
        foreach ($cond as $k => $v){
            $this->mdb->where($k, $v);
        }
        return $this->mdb->delete($this->_table);
    }

    /**
     * @param $id  待删除主键id
     * @return bool  成功删除返回被删除的行号,失败返回false
     */
    public function delById($id){
        $this->mdb->where($this->_primary, $id)->delete($this->_table);
        $rows = $this->mdb->affected_rows();
        if ($rows > 0){
            return $rows;
        }
        return false;
    }

    /**
     * 更新方法,支持多条件更新
     * @param array $cond
     * @param array $data
     * @return bool
     */
    public function updateByCond(array $cond, array $data){
        if (empty($cond)) return FALSE;
        foreach ($cond as $k => $v){
            $this->mdb->where($k, $v);
        }
        return $this->mdb->update($this->_table, $data);
    }

    /**
     * 根据主键更新数据
     * @param $id  主键
     * @param array $data  要修改的数据
     * @return mixed
     */
    public function updateById($id, array $data=[]){
        return $this->mdb->update($this->_table, $data, [$this->_primary => $id]);
    }

    /**
     * 根据条件获得指定数据
     * @param string $field  需要查询的字段 如 'id, name, sex'
     * @param array $cond  查询的限制条件 如 ['id' => '1', 'name' => 'wlq']
     * @param array $order  排序条件 如 ['id' => 'DESC', 'name' => 'ASC']
     * @return mixed
     */
    public function getAllByCond($field = '*', array $cond = [], array $order = [], $limit = '', $offset = ''){
        $db = $this->sdb;  //查询使用从服务器
        $db->select($field);
        $db->from($this->_table);
        if (!empty($cond)){
            $db->where($cond);
        }
        if ($limit != '' || $offset != ''){
            if ($offset == ''){
                $db->limit($limit);
            }else{
                $db->limit($offset, $limit);
            }
        }
        if (!empty($order) && is_array($order)){
            foreach ($order as $k => $v){
                $db->order_by($k, $v);
            }
        }
        return $db->get()->result_array();
    }

    /**
     * 根据ID查询
     * @param int $id  要查询的主键ID
     * @return array|boolean
     */
    public function getById($id){
        $query = $this->sdb->get_where($this->_table, [$this->_primary => $id]);
        return $query->row_array();
    }

    /**
     * 根据条件获得一列数据
     * @param string $field  需要查询的字段 如 'id, name, sex'
     * @param array $cond  查询的限制条件 如 ['id' => '1', 'name' => 'wlq']
     * @return mixed
     */
    public function getRowByCond($field = '*', array $cond = []){
        $db = $this->sdb;  //查询使用从服务器
        $db->from($this->_table);
        $db->select($field);
        if (!empty($cond)){
            $db->where($cond);
        }
        return $db->get()->row_array();
    }

    /**
     * 按SQL的查询方法
     * @param string $sql
     * @return bool
     * @throws Exception
     */
    public function query($sql){
        if (empty($sql)) return false;
        return $this->sdb->query($sql)->result_array();
    }

    /**
     * 查询数据库全部信息
     * @param string $limit
     * @param string $offset
     * @return mixed
     */
    public function getAll($limit = 0, $offset = 99999){
        return $this->sdb->get($this->_table, $offset, $limit)->result_array();
    }

    /**
     * 根据条件统计数据条数
     * @param array $cond  查询条件,默认不传表示查询所有数据数目
     * @return int
     */
    public function countByCond(array $cond = []){
        $this->sdb->from($this->_table);
        if (!empty($cond)){
            $this->sdb->where($cond);
        }
        return $this->sdb->count_all_results();
    }

    /**
     * 根据条件判断数据是否存在
     * @param array $cond  查询的限制条件 如 ['id' => '1', 'name' => 'wlq']
     * @return bool  存在返回TRUE,不存在返回FALSE
     */
    public function isExist(array $cond = []){
        $db = $this->sdb;  //查询使用从服务器
        $db->from($this->_table);
        $db->where($cond);
        $db->limit(1);
        return $db->count_all_results() ? TRUE : FALSE;
    }

//    /**
//     * 将对象转成数组
//     * @param Object $obj
//     * @return array
//     */
//    private function object_2_array($obj){
//        $result = [];
//        foreach ($obj as $k => $v) {
//            if (gettype($v) == 'array' || gettype($v) == 'object'){
//                $result[$k] = $this->object_2_array($v);
//            }else{
//                $result[$k] = $v;
//            }
//        }
//        return $result;
//    }

}