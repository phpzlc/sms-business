<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2018/8/19
 * Time: 下午5:36
 */

namespace App\Business\SmsBusiness\HhirdParty\Aliyun;

use App\Business\SmsBusiness\AbstractSms;
use PHPZlc\PHPZlc\Abnormal\Errors;
use Psr\Container\ContainerInterface;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliyunSMS extends AbstractSms
{
    /**
     * @var string
     */
    protected $accessKeyId;

    /**
     * @var string
     */
    protected $accessKeySecret;

    /**
     * @var string
     */
    protected $signName;

    public function __construct(ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->accessKeyId = (string)$this->getParameter('SMS_aliyun_accessKeyId');
        $this->accessKeySecret = (string)$this->getParameter('SMS_aliyun_accessKeySecret');
        $this->signName = (string)$this->getParameter('SMS_aliyun_signName');
    }

    function getSendPhoneSaltParams($phone, $type, $salt, $params)
    {
        $templateCode = null;
        $templateParam = null;

        return array(
            'templateCode' => $templateCode,
            'templateParam' => $templateParam
        );
    }

    public function sendSms($phone, $params = null)
    {
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try
        {
            return $this->returnRewrite(AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => $this->signName,
                        'TemplateCode' => $params['templateCode'],
                        'TemplateParam' => $params['templateParam'],
                    ],
                ])
                ->request());
        }
        catch (ClientException $e) {
            Errors::setErrorMessage($e->getErrorMessage());
            return false;
        } catch (ServerException $e) {
            Errors::setErrorMessage($e->getErrorMessage());
            return false;
        }
    }

    public function returnRewrite($r)
    {
        if ($r->Code == 'OK') {
            return true;
        }else{
            switch ($r->Code){
                case 'isp.RAM_PERMISSION_DENY':
                    $r = 'RAM权限DENY';
                    break;

                case 'isv.OUT_OF_SERVICE':
                    $r = '短信业务停机';
                    break;

                case 'isv.PRODUCT_UN_SUBSCRIPT':
                    $r = '未开通云通信产品的阿里云客户';
                    break;

                case 'isv.PRODUCT_UNSUBSCRIBE':
                    $r = '产品未开通';
                    break;

                case 'isv.ACCOUNT_NOT_EXISTS':
                    $r = '账户不存在';
                    break;

                case 'isv.ACCOUNT_ABNORMAL':
                    $r = '账户异常';
                    break;

                case 'isv.SMS_TEMPLATE_ILLEGAL':
                    $r = '短信模板不合法';
                    break;

                case 'isv.SMS_SIGNATURE_ILLEGAL':
                    $r = '短信签名不合法';
                    break;

                case 'isv.INVALID_PARAMETERS':
                    $r = '参数异常';
                    break;

                case 'isp.SYSTEM_ERROR':
                    $r = '系统错误';
                    break;

                case 'isv.MOBILE_NUMBER_ILLEGAL':
                    $r = '非法手机号';
                    break;

                case 'isv.MOBILE_COUNT_OVER_LIMIT':
                    $r = '手机号码数量超过限制';
                    break;

                case 'isv.TEMPLATE_MISSING_PARAMETERS':
                    $r = '模板缺少变量';
                    break;

                case 'isv.BUSINESS_LIMIT_CONTROL':
                    $r = '请勿重复操作';
                    break;

                case 'isv.INVALID_JSON_PARAM':
                    $r = 'JSON参数不合法，只接受字符串值';
                    break;

                case 'isv.BLACK_KEY_CONTROL_LIMIT':
                    $r = '黑名单管控';
                    break;

                case 'isv.PARAM_LENGTH_LIMIT':
                    $r = '参数超出长度限制';
                    break;

                case 'isv.PARAM_NOT_SUPPORT_URL':
                    $r = '不支持URL';
                    break;

                case 'isv.AMOUNT_NOT_ENOUGH':
                    $r = '短信业务余额不足';
                    break;

                case 'isv.TEMPLATE_PARAMS_ILLEGAL':
                    $r = '模板变量里包含非法关键字';
                    break;

                default:
                    $r = "短信次数超过限制";
                    break;
            }

            Errors::setErrorMessage($r);
            return false;
        }
    }
}