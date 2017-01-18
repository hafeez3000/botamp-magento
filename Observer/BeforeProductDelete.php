<?php
namespace Botamp\Botamp\Observer;

class BeforeProductDelete implements \Magento\Framework\Event\ObserverInterface {

  protected $productEntity;
  protected $productModel;

  public function __construct(
    \Magento\Catalog\Model\Product $productModel,
    \Botamp\Botamp\Resource\ProductEntity $productEntity
  ) {
    $this->productEntity = $productEntity;
    $this->productModel = $productModel;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $product_id = $observer->getEvent()->getProduct()->getId();
    $product = $productModel->load($product_id);
    $this->productEntity->delete($product);
  }
}
