<?php
/**
 * PhpStorm.
 * User: Jay
 * Date: 2019/10/15
 */

namespace App\Business\SmsBusiness;


class Curl
{
    /**
     * 发送post请求
     *
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    function request_post($url , $params = array(), $headers = array())
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        // 设置post请求参数
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //执行命令
        $data = curl_exec($curl);
        // 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据

        return $data;
    }


    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}