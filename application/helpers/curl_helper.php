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

/**
 * @param $url
 * @return mixed
 */
function curl($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $resJson = curl_exec($ch) ;
    return $resJson;
}

////临时加的curl的GET方法，有时间在修改
//function curl($url, $header){
//    $ch = curl_init();
//
//    //设置请求头
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    // 执行HTTP请求
//    curl_setopt($ch , CURLOPT_URL , $url);
//    $res = curl_exec($ch);
//    curl_close($ch);
//
//    return $res;
//}

/**
 * 执行post命令
 * post($url, ["params"=>$array]);
 * @param string $url 提交地址
 * @param array | string $postData 数组数据或编码后的字符串
 * @return mixed
 * @throws Exception
 * @deprecated
 */
function post($url, $postData){
    $str = json_encode($postData);
    $headerArr = array(
        'Content-Type: application/json; charset=utf-8',
        'Accept:application/json',
        'Content-Length: ' . strlen($str),
    );

    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    //curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
    curl_setopt($curl, CURLOPT_SSLVERSION, 4);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        ////捕抓异常
        throw new Exception(curl_error($curl));
    }
    curl_close($curl); // 关闭CURL会话
    return $result; // 返回数据
}