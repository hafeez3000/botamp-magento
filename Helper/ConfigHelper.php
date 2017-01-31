<?php
namespace Botamp\Botamp\Helper;

class ConfigHelper
{
    private $scopeConfig;
    private $configReader;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\DeploymentConfig\Reader $configReader
    ) {
    
        $this->scopeConfig = $scopeConfig;
        $this->configReader = $configReader;
    }

    private function getValue($field)
    {
        return $this->scopeConfig->getValue(
            'botamp_settings/general/'.$field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function orderNotificationsEnabled()
    {
        return (int)$this->getValue('order_notifications');
    }

    public function getApiKey()
    {
        return $this->getValue('api_key');
    }

    public function getApiBase()
    {
        $configData = $this->configReader->load(\Magento\Framework\Config\File\ConfigFilePool::APP_ENV);
        return isset($configData['botamp']['api_base']) ? $configData['botamp']['api_base'] : null;
    }
}
