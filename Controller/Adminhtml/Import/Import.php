<?php
namespace Botamp\Botamp\Controller\Adminhtml\Import;

class Import extends \Magento\Backend\App\Action {

  protected $jsonResultFactory;
  protected $resourceProxy;

  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
    \Botamp\Botamp\Resource\ProductEntity $productEntity
  ) {
    $this->jsonResultFactory = $jsonResultFactory;
    $this->resourceProxy = new \Botamp\Botamp\Utils\ResourceProxy($productEntity);
    return parent::__construct($context);
  }

  public function execute() {
    $jsonResult = $this->jsonResultFactory->create();

    $productId = $this->getRequest()->getParam('id');
    $response = $this->resourceProxy->import($productId);

    return $jsonResult->setData($response);
  }
}
