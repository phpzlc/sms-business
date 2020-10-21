<?php
/**
 * 聚合数据短信接口
 * User: Jay
 * Date: 2020/6/10
 */

namespace App\Business\SmsBusiness\HhirdParty\Juhe;


use App\Business\SmsBusiness\AbstractSms;
use App\Business\SmsBusiness\Curl;
use PHPZlc\PHPZlc\Abnormal\Errors;

class JuheSms extends AbstractSms
{
    function getSendPhoneSaltParams($phone, $type, $salt, $params)
    {
        $tpl_id = '220420';
        $tpl_value = urlencode("#salt#={$salt}&#other#={$params[1]}");

        return array(
            'tpl_id' => $tpl_id,
            'tpl_value' => $tpl_value
        );
    }

    function sendSms($phone, $params = null)
    {
        $curlClass = new Curl();

        return $this->returnRewrite($curlClass->request_get(
            "http://v.juhe.cn/sms/send?mobile={$phone}&tpl_id={$params['tpl_id']}&tpl_value={$params['tpl_value']}&key=". $this->getParameter('SMS_juhe_key')
        ));
    }

    function returnRewrite($result)
    {
        if(empty($result)){
            Errors::setErrorMessage('发送失败');
            return false;
        }else {
            $result = json_decode($result, true);
            if(!is_array($result)){
                Errors::setErrorMessage('发送失败');
                return false;
            }

            if($result['error_code'] != 0){
                Errors::setErrorMessage($result['reason']);
                return false;
            }
        }

        return true;
    }
}