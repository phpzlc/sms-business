<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * DateTime: 18-8-23下午3:34
 */

namespace App\Business\SmsBusiness;


class StringUtil
{

    //验证码类型字母
    public static $RANDOM_NUMBER = 1;
    //验证码类型字母
    public static $RANDOM_CAPTCHA = 2;
    public static $RANDOM_TYPE = ['1','2'];


    /**
     * 获取随机数
     *
     * @param $length
     * @return string
     */
    public function generateRandom($type,$length)
    {

        $chars = '';
        if(!in_array($type,self::$RANDOM_TYPE)){
            $type = self::$RANDOM_NUMBER;
        }

        if ($type == self::$RANDOM_CAPTCHA) {
            $chars = '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHJKLMNPQEST0123456789';
        } else if ($type == self::$RANDOM_NUMBER) {
            $chars = '0123456789';
        }
        $randomStr = '';

        $len = strlen($chars);
        for ($i=0; $i < $length; $i++){
            $randomStr .= $chars[rand(0,$len-1)];
        }

        return $randomStr;
    }
}