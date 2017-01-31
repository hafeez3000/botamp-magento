<?php
namespace Botamp\Botamp\Observer;

class BeforeProductSaveObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->resourceProxy->setCurrentResource('product_entity');
        $product = $observer->getEvent()->getProduct();
        $this->resourceProxy->createOrUpdate($product);
    }
}
