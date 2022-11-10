<?php

namespace BlueExpress\Shipping\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;


class Data extends AbstractHelper
{

    /**
     * 
     * 
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

     /**
      * Data constructor.
      *
      * @param ScopeConfigInterface $scopeConfig
      */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig  = $scopeConfig;
    }

    /**
     * function de cuenta del cliente de la Api
     * 
     * @return string
     */
    public function getClientAccount()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/clientaccount',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function de user code de la Api
     * 
     * @return string
     */
    public function getUserCode()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/usercode',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function de key de la Api precio
     * 
     * @return string
     */
    public function getBxapiKey()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/bxapiKey',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

     /**
      * function de url de la Api geo
      * 
      * @return string
      */
    public function getBxapiGeo()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/bxurlgeo',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
    * function de url de la Api precio
    * 
    * @return string
    */
    public function getBxapiPrice()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/bxurlprice', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function de token para la Api
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/token', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function para conexion webhook
     * 
     * @return string
     */
    public function getWebHook()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/webhook', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function para la key del webhook
     * 
     * @return string
     */
    public function getKeyWebhook()
    {
        return $this->_scopeConfig->getValue(
            'carriers/bluexpress/keywebhook', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * function para obtener peso del producto
     * 
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->_scopeConfig->getValue(
            'general/locale/weight_unit',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
