<?php
namespace Botamp\Botamp\Controller\Adminhtml\Import;

class Import extends \Magento\Backend\App\Action
{

    private $jsonResultFactory;
    private $resourceProxy;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        \Botamp\Botamp\Utils\ResourceProxy $resourceProxy
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->resourceProxy = $resourceProxy;
        parent::__construct($context);
    }

    public function execute()
    {
        $jsonResult = $this->jsonResultFactory->create();

        $productId = $this->getRequest()->getParam('id');
        $this->resourceProxy->setCurrentResource('product_entity');
        $response = $this->resourceProxy->import($productId);

        return $jsonResult->setData($response);
    }
}
