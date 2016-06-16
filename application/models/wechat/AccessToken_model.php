<?php
/**
 * AccessToken_model.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 13:51
 */

class AccessToken_model extends MY_Model{

    public function __construct(){
        parent::__construct();
    }

    public function setPrimary(){
        // TODO: Implement setPrimary() method.
        return 'id';
    }

    public function setTableName(){
        // TODO: Implement setTableName() method.
        return 'access_token';
    }

    /**
     * 添加AccessToken
     * @param array $data
     * @return bool
     */
    public function addAccessToken(array $data){
        return $this->insertData($data);
    }
    
    /**
     * 查询AccessToken
     * @param int $id
     * @return array|bool
     */
    public function getAccessToken($id = 1){
        return $this->getById($id);
    }

    /**
     * 更新AccessToken
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateAccessToken(array $data, $id){
        return $this->updateById($id, $data);
    }

}