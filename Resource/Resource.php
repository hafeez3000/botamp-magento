<?php
namespace Botamp\Botamp\Resource;

class Resource {
  protected $botamp;

  public function __construct() {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $configHelper = $objectManager->create('\Botamp\Botamp\Helper\ConfigHelper');
    $configReader = $objectManager->create('\Magento\Framework\App\DeploymentConfig\Reader');

    $configData = $configReader->load(\Magento\Framework\Config\File\ConfigFilePool::APP_ENV);

    $apiKey = $configHelper->getApiKey();
    $this->botamp = new \Botamp\Client($apiKey);

    if(isset($configData['botamp']['api_base']))
        $this->botamp->setApiBase($configData['botamp']['api_base']);

  }
}
