<?php
namespace Botamp\Botamp\Observer;

class BeforeProductSave implements \Magento\Framework\Event\ObserverInterface
{
  protected $entity;

  public function __construct(\Botamp\Botamp\Resource\Entity $entity) {
    $this->entity = $entity;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $product = $observer->getEvent()->getProduct();

    $this->entity->createOrUpdate($product);
  }
}
