<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2018/8/19
 * Time: 下午6:01
 *
 * 第三方短信接口必须实现该接口
 */

namespace  App\Business\SmsBusiness;


interface SmsInterface
{
    const MSG_CONFIG_ERROR = '配置文件错误';
    const MSG_TYPE_ERROR = '类型溢出';
    const MSG_SALE_USED_OUT_ERROR = '该短信验证码验证次数超过限制';
    const MSG_SALE_USED_ERROR = '该短信验证码已被验证';
    const MSG_SALE_ERROR = '短信验证码不正确';

    const MSG_SALE_EXPIRE_ERROR = '短信验证码失效';
    const MSG_SALE_EMPTY_ERROR = '短信验证码不能为空';
    const MSG_SUCCESS = '发送成功';


    /**
     * 发送短信
     *
     * @param $phone
     * @param $params
     * @return mixed
     */
     function sendSms($phone, $params = null);

    /**
     * 发送短信验证码
     *
     * @param $phone
     * @param $type
     * @param $params
     * @return mixed
     */
    function sendPhoneSalt($phone, $type, $params = null);

    /**
     * 得到发送短信验证码的发送参数
     *
     * @param $phone
     * @param $type
     * @param $salt
     * @param $params
     * @return mixed
     */
    function getSendPhoneSaltParams($phone, $type, $salt, $params);

    /**
     * 短信验证码验证
     *
     * @param $phone
     * @param $salt
     * @param $type
     * @return boolean
     */
     function isSalt($phone, $salt, $type = null);

    /**
     * 重写返回值
     *
     * @param $result
     * @return boolean
     */
     function returnRewrite($result);
}