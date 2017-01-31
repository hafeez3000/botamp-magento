<?php
namespace Botamp\Botamp\Controller\Order;

class Unsubscribe extends \Magento\Framework\App\Action\Action
{

    private $resultPageFactory;
    private $orderFactory;
    private $resourceProxy;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Botamp\Botamp\Utils\ResourceProxy $resourceProxy
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->orderFactory = $orderFactory;
        $this->resourceProxy = $resourceProxy;
        parent::__construct($context);
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('id');
        $order = $this->orderFactory->create()->load($orderId);

        $this->resourceProxy->setCurrentResource('subscription');
        $this->resourceProxy->delete($order->getBotampSubscriptionId());

        $order->setBotampSubscriptionId(null);
        $order->save();

        return $this->resultPageFactory->create();
    }
}
