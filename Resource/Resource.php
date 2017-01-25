<?php
namespace Botamp\Botamp\Resource;

class Resource {
  protected $botamp;

  public function __construct() {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $configHelper = $objectManager->create('\Botamp\Botamp\Helper\ConfigHelper');

    $apiKey = $configHelper->getApiKey();

    $this->botamp = new \Botamp\Client($apiKey);
    $this->botamp->setApiBase('http://localhost:3000/api');
  }
}
