<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * DateTime: 18-8-23下午3:47
 */

namespace App\Business\SmsBusiness;

use PHPZlc\PHPZlc\Bundle\Business\AbstractBusiness;
use Psr\Container\ContainerInterface;

class Config extends AbstractBusiness
{
    private static $_config = null;

    public static function getSDKConfig(ContainerInterface $container){
        if (Config::$_config == null ) {
            Config::$_config = new Config($container);
        }
        return Config::$_config;
    }

    /**
     * @var integer
     */
    public $expireTime;//短信过期时长

    /**
     * @var integer
     */
    public $usedTime;//短信可用次数

    /**
     * @var boolean
     */
    public $trueSend;//是否调用第三发接口发送

    /**
     * @var boolean
     */
    public $isBeta;//是否显示短信

    /**
     * @var integer
     */
    public $saltType;//验证码类型

    /**
     * @var integer
     */
    public $saltNum;//短信验证码长度

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->expireTime = $this->getParameter('SMS_expireTime');
        $this->usedTime = $this->getParameter('SMS_usedTime');
        $this->trueSend = $this->getParameter('SMS_trueSend');
        $this->isBeta = $this->getParameter('SMS_isBeta');
        $this->saltType =  $this->getParameter('SMS_saltType');
        $this->saltNum = $this->getParameter('SMS_saltNum');
    }
}