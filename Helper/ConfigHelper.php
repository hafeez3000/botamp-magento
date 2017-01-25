<?php
namespace Botamp\Botamp\Helper;


class ConfigHelper
{
    protected $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
      $this->scopeConfig = $scopeConfig;
    }

    private function getValue($field)
    {
      return $this->scopeConfig->getValue(
        'botamp_settings/general/'.$field,
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
    }

    public function orderNotificationsEnabled(){
      return (int)$this->getValue('order_notifications');
    }

    public function getApiKey(){
      return $this->getValue('api_key');
    }
}
