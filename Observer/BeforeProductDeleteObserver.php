<?php
namespace Botamp\Botamp\Observer;

class BeforeProductDeleteObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{

    private $productModel;

    public function __construct(
        \Magento\Catalog\Model\Product $productModel,
        \Botamp\Botamp\Utils\ResourceProxy $resourceProxy
    ) {
    
        $this->productModel = $productModel;
        parent::__construct($resourceProxy);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->resourceProxy->setCurrentResource('product_entity');
        $product_id = $observer->getEvent()->getProduct()->getId();
        $product = $this->productModel->load($product_id);
        $this->resourceProxy->delete($product);
    }
}
