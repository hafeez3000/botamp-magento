<?php
namespace Botamp\Botamp\Observer;

class BeforeProductDelete implements \Magento\Framework\Event\ObserverInterface
{
  protected $entity;

  public function __construct(\Botamp\Botamp\Resource\Entity $entity) {
    $this->entity = $entity;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    $product_id = $observer->getEvent()->getProduct()->getId();
    $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);

    $this->entity->delete($product);
  }
}
