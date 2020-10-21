<?php
/**
 * PhpStorm.
 * User: Jay
 * Date: 2020/10/21
 */

namespace App\Business\SmsBusiness;


use App\Business\SmsBusiness\HhirdParty\Aliyun\AliyunSMS;
use PHPZlc\PHPZlc\Abnormal\Errors;
use PHPZlc\PHPZlc\Bundle\Business\AbstractBusiness;
use PHPZlc\Validate\Validate;
use Psr\Container\ContainerInterface;

class SmsBusiness extends AbstractBusiness
{
    /**
     * @var AbstractSms
     */
    public $sms;

    public function __construct(ContainerInterface $container)
    {
        $this->sms = new AliyunSMS($container);
        parent::__construct($container);
    }

    /**
     * 发送手机验证码
     *
     * @param $phone
     * @param $type 1: 绑定手机号 2:修改手机号
     * @return bool|mixed
     */
    public function sendPhoneCode($phone, $type)
    {
        if(empty($phone)){
            Errors::setErrorMessage('手机号不能为空');
            return false;
        }

        if(!Validate::isMobile($phone)){
            Errors::setErrorMessage('手机号格式不正确');
            return false;
        }

        if(empty($type)){
            Errors::setErrorMessage('类型不能为空');
            return false;
        }

        return $this->sms->sendPhoneSalt($phone, $type);
    }

    public function isSale($phone, $salt, $type)
    {
        if(empty($phone)){
            Errors::setErrorMessage('手机号不能为空');
            return false;
        }

        if(!Validate::isMobile($phone)){
            Errors::setErrorMessage('手机号格式不正确');
            return false;
        }

        if(empty($type)){
            Errors::setErrorMessage('类型不能为空');
            return false;
        }

        if(empty($salt)){
            Errors::setErrorMessage('短信验证码不能为空');
            return false;
        }

        return $this->sms->isSalt($phone, $salt, $type);
    }
}