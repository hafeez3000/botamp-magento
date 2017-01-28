<?php
namespace Botamp\Botamp\Utils;

class ResourceProxy {

  protected $resource;
  protected $backendSession;

  public function __construct($resource) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $this->resource = $resource;
    $this->backendSession = $objectManager->create('\Magento\Backend\Model\Session');
  }

  public function __call($method, $arguments) {
    $this->backendSession->setBotampAuthStatus('ok');
    try {
      // @codingStandardsIgnoreStart
      $result = call_user_func_array([$this->resource, $method], $arguments);
      // @codingStandardsIgnoreEnd
      return $result;
    }
    catch (\Botamp\Exceptions\Unauthorized $e) {
      $this->backendSession->setBotampAuthStatus('unauthorized');
    }
  }
}
