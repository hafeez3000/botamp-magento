<?php
namespace Botamp\Botamp\Observer;

class BeforeProductSaveObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface {

  public function __construct(\Botamp\Botamp\Resource\ProductEntity $productEntity) {
    parent::__construct($productEntity);
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $product = $observer->getEvent()->getProduct();
    $this->resourceProxy->createOrUpdate($product);
  }
}
