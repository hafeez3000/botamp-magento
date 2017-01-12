<?php

namespace Botamp\Botamp\Controller\Adminhtml\ImportProducts;

use Magento\Backend\App\Action\Context;
use \Botamp\Botamp\Resource\Entity;

class Index extends \Magento\Backend\App\Action {
  protected $entity;

  public function __construct(Context $context, Entity $entity) {
    parent::__construct($context);
    $this->entity = $entity;
  }

  public function execute(){
    $this->entity->importAllProducts();

    $this->_view->loadLayout();
    $this->_view->renderLayout();
  }
}
