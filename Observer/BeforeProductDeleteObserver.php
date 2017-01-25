<?php
namespace Botamp\Botamp\Observer;

class BeforeProductDeleteObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface {

  protected $productModel;

  public function __construct(
    \Magento\Catalog\Model\Product $productModel,
    \Botamp\Botamp\Resource\ProductEntity $productEntity
  ) {
    $this->productModel = $productModel;
    parent::__construct($productEntity);
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $product_id = $observer->getEvent()->getProduct()->getId();
    $product = $productModel->load($product_id);
    $this->resourceProxy->delete($product);
  }
}
