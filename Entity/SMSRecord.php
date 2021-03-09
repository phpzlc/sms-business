<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SMSRecordRepository;

/**
 * @ORM\Table(name="sms_record", options={"comment"="短信动态码"}, indexes={@ORM\Index(name="phone", columns={"phone"})})
 * @ORM\Entity(repositoryClass=SMSRecordRepository::class)
 */
class SMSRecord
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="PHPZlc\PHPZlc\Doctrine\SortIdGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=11, nullable=false, options={"comment"="手机号码"})
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=4, nullable=false, options={"comment"="验证码"})
     */
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="happen_time", type="integer", nullable=false, options={"comment"="发送时间"})
     */
    private $happenTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=false, options={"comment"="短信用途 具体类型查看文档"})
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="times", type="smallint", options={"comment"="验证码验证次数"})
     */
    private $times;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_used", type="boolean",options={"comment"="验证码是否使用"})
     */
    private $isUsed;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=40, nullable=false, options={"comment"="IP地址"})
     */
    private $ip;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return SMSRecord
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return SMSRecord
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set happenTime
     *
     * @param integer $happenTime
     *
     * @return SMSRecord
     */
    public function setHappenTime($happenTime)
    {
        $this->happenTime = $happenTime;

        return $this;
    }

    /**
     * Get happenTime
     *
     * @return integer
     */
    public function getHappenTime()
    {
        return $this->happenTime;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return SMSRecord
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set times
     *
     * @param integer $times
     *
     * @return SMSRecord
     */
    public function setTimes($times)
    {
        $this->times = $times;

        return $this;
    }

    /**
     * Get times
     *
     * @return integer
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * Set isUsed
     *
     * @param boolean $isUsed
     *
     * @return SMSRecord
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = $isUsed;

        return $this;
    }

    /**
     * Get isUsed
     *
     * @return boolean
     */
    public function getIsUsed()
    {
        return $this->isUsed;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return SMSRecord
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}
