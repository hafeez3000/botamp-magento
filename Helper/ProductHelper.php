<?php
namespace Botamp\Botamp\Helper;

class ProductHelper
{

    private $imageHelper;

    public function __construct(\Magento\Catalog\Helper\Image $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    private function getProducImageUrl($product)
    {
        return $this->imagehelper->init($product, 'product_base_image')->getUrl();
    }
}
