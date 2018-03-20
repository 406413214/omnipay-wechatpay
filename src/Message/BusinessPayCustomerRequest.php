<?php
/**
 * Created by PhpStorm.
 * User: xiawenqiang
 * Date: 2018/2/24
 * Time: 下午2:50
 */

namespace Omnipay\WechatPay\Message;

use Omnipay\WechatPay\Helper;


class BusinessPayCustomerRequest extends BaseAbstractRequest
{

    protected $endpoint = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

    public function sendData($data)
    {
        $options = array (
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERTTYPE    => 'PEM',
            CURLOPT_SSLKEYTYPE     => 'PEM',
            CURLOPT_SSLCERT        => $this->getCertPath(),
            CURLOPT_SSLKEY         => $this->getKeyPath(),
        );
        $request      = $this->httpClient->post($this->endpoint)->setBody(Helper::array2xml($data));
        $request->getCurlOptions()->overwriteWith($options);
        $response     = $request->send()->getBody();
        $responseData = Helper::xml2array($response);
        return $this->response = new BusinessPayCustomerResponse($this, $responseData);
    }

    public function getData()
    {
        $this->validate('app_id', 'mch_id', 'partner_trade_no', 'openid', 're_user_name', 'amount', 'desc', 'spbill_create_ip');
        $data = array (
            'mch_appid'        => $this->getAppId(),
            'mchid'       => $this->getMchId(),
            'partner_trade_no' => $this->getPartnerTradeNo(),
            'openid' => $this->getOpenId(),
            'check_name' => 'FORCE_CHECK',
            're_user_name' => $this->getReUserName(),
            'amount' => $this->getAmount(),
            'desc' => $this->getDesc(),
            'spbill_create_ip' => $this->getSpbillCreateIp(),
            'nonce_str'    => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $this->getApiKey());

        return $data;
    }

    public function getPartnerTradeNo()
    {
        return $this->getParameter('partner_trade_no');
    }

    public function setPartnerTradeNo($partnerTradeNo)
    {
        $this->setParameter('partner_trade_no', $partnerTradeNo);
    }

    public function getOpenId()
    {
        return $this->getParameter('openid');
    }

    public function setOpenId($openid)
    {
        $this->setParameter('openid', $openid);
    }

    public function getReUserName()
    {
        return $this->getParameter('re_user_name');
    }

    public function setReUserName($reUserName)
    {
        $this->setParameter('re_user_name', $reUserName);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($amount)
    {
        $this->setParameter('amount', $amount);
    }

    public function getDesc()
    {
        return $this->getParameter('amount');
    }

    public function setDesc($desc)
    {
        $this->setParameter('desc', $desc);
    }

    public function getSpbillCreateIp()
    {
        return $this->getParameter('spbill_create_ip');
    }

    public function setSpbillCreateIp($spbillCreateIp)
    {
        $this->setParameter('spbill_create_ip', $spbillCreateIp);
    }

    public function getCertPath()
    {
        return $this->getParameter('cert_path');
    }

    public function setCertPath($certPath)
    {
        $this->setParameter('cert_path', $certPath);
    }

    function getKeyPath()
    {
        return $this->getParameter('key_path');
    }

    public function setKeyPath($keyPath)
    {
        $this->setParameter('key_path', $keyPath);
    }
}