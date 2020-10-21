<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2018/8/19
 * Time: 下午6:01
 *
 * 第三方短信接口抽象类
 */
namespace App\Business\SmsBusiness;

use App\Entity\SMSRecord;
use App\Repository\SMSRecordRepository;
use PHPZlc\PHPZlc\Abnormal\Errors;
use PHPZlc\PHPZlc\Bundle\Business\AbstractBusiness;
use Psr\Container\ContainerInterface;

abstract class AbstractSms extends AbstractBusiness implements SmsInterface
{
    /**
     * @var Config|null
     */
    protected static $config = null;

    /**
     * @var SMSRecordRepository
     */
    private $smsRecordRepository;

    public function __construct(ContainerInterface $container = null)
    {
        parent::__construct($container);
        if(self::$config == null){
            self::$config = Config::getSDKConfig($container);
        }

        $this->smsRecordRepository = $this->getDoctrine()->getRepository('App:SMSRecord');
    }

    public function isSalt($phone, $salt, $type = null)
    {
        if(empty($salt)){
            Errors::setErrorMessage(self::MSG_SALE_EMPTY_ERROR);
            return false;
        }

        $saltInfo = $this->smsRecordRepository->findAssoc([
            'phone' => $phone,
            'type' => $type,
            'salt' => $salt
        ]);

        //是否存在
        if(empty($saltInfo)){
            Errors::setErrorMessage(self::MSG_SALE_ERROR);
            return false;
        }

        //是否只可用一次
        if( self::$config->usedTime == 1 ){
            //需验证是否使用
            if ( $saltInfo->getIsUsed() ) {
                Errors::setErrorMessage(self::MSG_SALE_USED_ERROR);
                return false;
            }
        }else{
            //是否超出限制
            if ($saltInfo->getTimes() >= self::$config->usedTime) {
                Errors::setErrorMessage(self::MSG_SALE_USED_OUT_ERROR);
                return false;
            }
        }

        //是否过期
        if (time() - $saltInfo->getHappenTime() > self::$config->expireTime) {
            Errors::setErrorMessage(self::MSG_SALE_EXPIRE_ERROR);
            return false;
        }

        //改为已使用
        if( !$saltInfo->getIsUsed() ){
            $saltInfo->setIsUsed(true);
        }

        $saltInfo->setTimes($saltInfo->getTimes() + 1);

        $this->em->flush();
        $this->em->clear();

        return true;
    }

    function sendPhoneSalt($phone, $type, $params = null)
    {
        //注销以前的验证码
        $this->conn->executeUpdate(
            "UPDATE {$this->smsRecordRepository->getTableName()} SET happen_time = ?,is_used = ? WHERE phone = ? and type = ? and happen_time >= ?",
            array(
                time() - self::$config->expireTime,
                1,
                $phone,
                $type,
                time() - self::$config->expireTime
            )
        );

        $stringUtil = new StringUtil();
        $smsRecord = new SMSRecord();
        $smsRecord->setSalt($stringUtil->generateRandom(self::$config->saltType, self::$config->saltNum));
        $smsRecord->setPhone($phone);
        $smsRecord->setIp($_SERVER['REMOTE_ADDR']);
        $smsRecord->setType($type);
        $smsRecord->setTimes(0);
        $smsRecord->setIsUsed(false);
        $smsRecord->setHappenTime(time());

        if(self::$config->trueSend){
            $sendParams = $this->getSendPhoneSaltParams($phone, $type, $smsRecord->getSalt(), $params);
            if(!$this->sendSms($phone, $sendParams)){
                return false;
            }
        }

        $this->em->persist($smsRecord);
        $this->em->flush();
        $this->em->clear();

        if(self::$config->isBeta){
            return array(
                'salt' => $smsRecord->getSalt()
            );
        }else{
            return array(
                'salt' => ''
            );
        }
    }
}
