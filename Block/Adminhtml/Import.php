<?php
namespace Botamp\Botamp\Block\Adminhtml;

class Import extends \Magento\Backend\Block\Template
{

    private $productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
    ) {
        parent::__construct($context, []);
        $this->productFactory = $productFactory;
    }

    public function getAjaxUrl()
    {
        return $this->getUrl("botamp/import/import");
    }

    public function getProductsIds()
    {
        $products = $this->productFactory->create()->addAttributeToSelect('entity_id')->load();
        return implode(',', $products->getColumnValues('entity_id'));
    }
}
