<?php
namespace Botamp\Botamp\Observer;

use Botamp\Botamp\Utils;

abstract class AbstractObserver {

  protected $resourceProxy;

  public function __construct($resource) {
    $this->resourceProxy = new \Botamp\Botamp\Utils\ResourceProxy($resource);
  }
}
