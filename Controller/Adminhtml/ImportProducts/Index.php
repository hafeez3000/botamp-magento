<?php

namespace Botamp\Botamp\Controller\Adminhtml\ImportProducts;

class Index extends \Magento\Backend\App\Action {
  private $productEntity;

  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Botamp\Botamp\Resource\ProductEntity $productEntity
  ) {
    parent::__construct($context);
    $this->productEntity = $productEntity;
  }

  public function execute(){
    $this->productEntity->importAllProducts();

    $this->_view->loadLayout();
    $this->_view->renderLayout();
  }
}
