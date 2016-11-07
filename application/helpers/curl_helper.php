<?php
/**
 * curl_helper.php
 * User: wlq314@qq.com
 * Date: 16/6/16 Time: 10:53
 */

class CURL{

    /**
     * CURL GET抓取数据
     * @param $url
     * @return mixed
     */
    public static function get($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $res = curl_exec($ch) ;
        curl_close($ch);
        return $res;
    }

    /**
     * CURL POST
     * @param $url
     * @param array $data
     * @return bool|mixed
     */
    public static function post($url, array $data){
        if (!is_array($data) || empty($data))
            return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * CURL 上传文件
     * @param $url  请求地址
     * @param $name  参数名称
     * @param $file  文件绝对地址
     * @return mixed
     */
    public static function file($url, $name, $file){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [$name => '@' . $file]);
        return curl_exec($ch);
    }
    
}

