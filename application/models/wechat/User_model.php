<?php
/**
 * User_model.php
 * User: wlq314@qq.com
 * Date: 16/6/13 Time: 16:43
 */

class User_model extends MY_Model{

    //删除的用户状态为0
    const STATUS_DELETE = 0;

    public function setPrimary(){
        // TODO: Implement setPrimary() method.
        return 'id';
    }

    public function setTableName(){
        // TODO: Implement setTableName() method.
        return 'wechat_user';
    }

    /**
     * 根据OpenId获得用户信息
     * @param $openId  用户OpenId
     * @param string $field  查询的字段,默认查询全部
     * @return mixed
     */
    public function getUserInfoByOpenId($openId, $field = '*'){
        $cond = ['open_id' => $openId];
        return $this->getRowByCond($field, $cond);
    }

    /**
     * 添加新用户
     * @param array $data
     * @return bool
     */
    public function addNewUser(array $data){
        return $this->insertData($data);
    }

    /**
     * 根据OpenId删除用户
     * @param $openId
     * @return bool
     */
    public function deleteUserByOpenId($openId){
        $cond = ['open_id' => $openId];
        $data = ['status' => self::STATUS_DELETE];
        return $this->updateByCond($cond, $data);
    }

    /**
     * 根据OpenId更新用户信息
     * @param $openId
     * @param array $data
     * @return bool
     */
    public function updateUserByOpenId($openId, array $data){
        $cond = ['open_id' => $openId];
        return $this->updateByCond($cond, $data);
    }

    /**
     * 根据OpenId判断用户是否在平台存在
     * @param $openId
     * @return bool
     */
    public function isExistByOpenId($openId){
        $cond = ['open_id' => $openId];
        return $this->isExist($cond);
    }
    
}
