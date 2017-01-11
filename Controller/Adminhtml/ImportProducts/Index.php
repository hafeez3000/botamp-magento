<?php

namespace Botamp\Botamp\Controller\Adminhtml\ImportProducts;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action {
  protected $resultPageFactory;
  protected $entity;

  public function __construct(
    Context $context,
    PageFactory $resultPageFactory,
    \Botamp\Botamp\Resource\Entity $entity
  ) {
    parent::__construct($context);
    $this->resultPageFactory = $resultPageFactory;
    $this->entity = $entity;
  }

  public function execute(){
    $this->entity->importAllProducts();
    die("All your posts have been successfully imported into Botamp");
    // $resultPage = $this->resultPageFactory->create();
    // $resultPage->setActiveMenu('Botamp_Botamp::menu');
    // $resultPage->getConfig()->getTitle()->prepend(__('Botamp Menu'));
    // return $resultPage;
  }
}
