<?php
namespace Botamp\Botamp\Utils;

class ResourceProxy {

  private $resource;
  private $backendSession;

  public function __construct($resource) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $this->resource = $resource;
    $this->backendSession = $objectManager->create('\Magento\Backend\Model\Session');
  }

  public function __call($method, $arguments) {
    $this->backendSession->setBotampAuthStatus('ok');
    try {
      $result = call_user_func_array([$this->resource, $method], $arguments);
      return $result;
    }
    catch (\Botamp\Exceptions\Unauthorized $e) {
      $this->backendSession->setBotampAuthStatus('unauthorized');
    }
  }
}
