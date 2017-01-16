<?php
namespace Botamp\Botamp\Helper;


class ConfigHelper
{
    protected $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
      $this->scopeConfig = $scopeConfig;
    }

    public function getValue($field)
    {
      return $this->scopeConfig->getValue(
        'settings/general/'.$field,
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
    }
}
