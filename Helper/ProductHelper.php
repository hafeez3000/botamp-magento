<?php
namespace Botamp\Botamp\Helper;


class ProductHelper {

  protected $imageHelper;

  public function __construct(\Magento\Catalog\Helper\Image $imageHelper) {
    $this->imageHelper = $imageHelper;
  }

  protected function getProducImageUrl($product) {
    return $this->imagehelper->init($product,'product_base_image')->getUrl();
  }
}
